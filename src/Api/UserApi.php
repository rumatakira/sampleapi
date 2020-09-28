<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\Api;

use Cycle\ORM;
use OpenAPIServer\OrmEntities\User;
use OpenAPIServer\Api\AbstractUserApi;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class UserApi extends AbstractUserApi
{
    public function __construct()
    {
        /** @var ORM\ORMInterface $orm */
        include "bootstrap.php";
        $database = $orm->getSource(User::class)->getDatabase();
        $this->database = $database;
    }

    /**
     * POST createUser
     * Summary: Create user
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function createUser(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $body = $request->getParsedBody();
        $userRealName = (key_exists('userRealName', $body)) ? $body['userRealName'] : null;
        $userLoginName = (key_exists('userLoginName', $body)) ? $body['userLoginName'] : null;
        $userPassword = (key_exists('userPassword', $body)) ? $body['userPassword'] : date_default_timezone_get();
        $userPhone = (key_exists('userPhone', $body)) ? $body['userPhone'] : null;
        $companyUserBelogsTo = (key_exists('companyUserBelogsTo', $body)) ? $body['companyUserBelogsTo'] : null;
        if (empty($userRealName) or empty($userLoginName) or empty($userPassword) or empty($userPhone) or empty($companyUserBelogsTo)) {
            return $response->withStatus(400);
        }

        // find user company
        try {
            // by ORM tools
            $select = $this->database->table('companies as c')->select('*');
            $userCompany = $select->where('c.company_name', $companyUserBelogsTo)->fetchAll();
            // raw SQL
            // $userCompany = $this->database->query("SELECT * FROM companies c WHERE c.company_name = ?", [$companyUserBelogsTo])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        if (empty($userCompany)) {
            return $response->withStatus(400, 'No such company');
        } else {
            $userCompanyId = intval($userCompany[0]['id']);
            try {
                // by ORM tools
                $insert = $this->database->insert('users');
                $insert->values([
                    'user_real_name' => $userRealName,
                    'user_login_name' => $userLoginName,
                    'user_password' => $userPassword,
                    'user_phone' => $userPhone,
                    'company_id' => $userCompanyId]);
                $userId = $insert->run();

                $payload = json_encode('User created with id '.$userId);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } catch (\Throwable $e) {
                return $response->withStatus(500);
            }
        }
    }

    /**
     * DELETE deleteUser
     * Summary: Delete user
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function deleteUser(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $userId = (key_exists('userId', $queryParams)) ? $queryParams['userId'] : null;
        if (empty($userId)) {
            return $response->withStatus(400);
        }
        $userId = intval($userId);
        try {
            // by ORM tools
            $this->database->table('users as u')->delete()->where('u.id', $userId)->run();
            // raw SQL
            // $this->database->execute("DELETE FROM users u WHERE u.id = ?", [$userId]);
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        return $response->withStatus(200, 'User deleted');
    }

    /**
     * GET getUser
     * Summary: Get user
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function getUser(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $userId = (key_exists('userId', $queryParams)) ? $queryParams['userId'] : null;

        if (empty($userId)) {
            return $response->withStatus(400);
        }

        // find user
        try {
            // by ORM tools
            $select = $this->database->table('users as u')->select('*');
            $user = $select->where('u.id', $userId)->fetchAll();
            // raw SQL
            // $user = $this->database->query("SELECT * FROM users u WHERE u.id = ?", [$userId])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        if (empty($user)) {
            return $response->withStatus(404, 'No such user');
        } else {
            $payload = json_encode($user);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * GET getUsers
     * Summary: Get users which belongs to the same company
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function getUsers(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $userLoginName = (key_exists('userLoginName', $queryParams)) ? $queryParams['userLoginName'] : null;

        if (empty($userLoginName)) {
            return $response->withStatus(400);
        }

        // find users that belomgs to the same company
        try {
            // by ORM tools
            $user = $this->database->table('users as u')->select('*')->where('u.user_login_name', $userLoginName)->fetchAll();
            if (empty($user)) {
                return $response->withStatus(404, 'No such user');
            }
            $companyId = $user[0]['company_id'];
            $users = $this->database->table('users as u')->select('*')->rightJoin('companies as c')->on('u.company_id', 'c.id')->where('u.company_id', $companyId)->orderBy('u.user_real_name')->fetchAll();
            // raw SQL
            // $user = $this->database->query("SELECT * FROM users u WHERE u.user_login_name = ?", [$userLoginName])->fetchAll();
            // if (empty($user)) {
            //     return $response->withStatus(404, 'No such user');
            // }
            // $companyId = $user[0]['company_id'];
            // $users = $this->database->query("SELECT * FROM users u RIGHT JOIN companies c  ON u.company_id = c.id WHERE u.company_id = ? ORDER BY u.user_real_name", [$companyId])->fetchAll();

            $payload = json_encode($users);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }
    }

    /**
     * PUT updateUser
     * Summary: Update user
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function updateUser(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $body = $request->getParsedBody();
        $userId = (key_exists('userId', $body)) ? intval($body['userId']) : null;
        $userRealName = (key_exists('userRealName', $body)) ? $body['userRealName'] : null;
        $userLoginName = (key_exists('userLoginName', $body)) ? $body['userLoginName'] : null;
        $userPassword = (key_exists('userPassword', $body)) ? $body['userPassword'] : date_default_timezone_get();
        $userPhone = (key_exists('userPhone', $body)) ? $body['userPhone'] : null;
        $companyUserBelogsTo = (key_exists('companyUserBelogsTo', $body)) ? $body['companyUserBelogsTo'] : null;
        if (empty($userId) or empty($userRealName) or empty($userLoginName) or empty($userPassword) or empty($userPhone) or empty($companyUserBelogsTo)) {
            return $response->withStatus(400);
        }

        // find user company
        try {
            // by ORM tools
            $select = $this->database->table('companies as c')->select('*');
            $userCompany = $select->where('c.company_name', $companyUserBelogsTo)->fetchAll();
            // raw SQL
            // $userCompany = $this->database->query("SELECT * FROM companies c WHERE c.company_name = ?", [$companyUserBelogsTo])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        // if company exist update division
        if (empty($userCompany)) {
            return $response->withStatus(400, 'No such company');
        } else {
            $userCompanyId = intval($userCompany[0]['id']);
            try {
                // by ORM tools
                $update = $this->database->table('users')->update([
                    'user_real_name' => $userRealName,
                    'user_login_name' => $userLoginName,
                    'user_password' => $userPassword,
                    'user_phone' => $userPhone,
                    'company_id' => $userCompanyId]);
                $update->where('id', '=', $userId)->run();
                // raw SQL
                // $this->database->execute("UPDATE users u SET (userRealName, userLoginName, userPassword, userPhone, company_id) = (?,?,?,?,?) WHERE d.id = '{$userId}'", [$userRealName, $userLoginName, $userPassword, $userPhone, $userCompanyId]);
                return $response->withStatus(200, 'User updated');
            } catch (\Throwable $e) {
                return $response->withStatus(500);
            }
        }
    }
}
