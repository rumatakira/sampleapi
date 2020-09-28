<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\Auth;

use Dyorg\TokenAuthentication\Exceptions\UnauthorizedException;
use OpenAPIServer\Auth\AbstractAuthenticator;
use OpenAPIServer\OrmEntities\User;

/**
     * Verify if token is valid on database
     * If token isn't valid, throw an UnauthorizedExceptionInterface
     *
     * @param string $token
     *
     * @return array User object or associative array
     * @throws UnauthorizedExceptionInterface on invalid token
     */
class BasicAuthenticator extends AbstractAuthenticator
{
    public function __construct()
    {
        /** @var ORM\ORMInterface $orm */
        include "bootstrap.php";
        $database = $orm->getSource(User::class)->getDatabase();
        $this->database = $database;
    }

    public function getUserByToken(string $token)
    {
        // by ORM tools
        $result = $this->database->table('users')->select()->where('token', $token)->fetchAll();
        // raw SQL
        // $result = $this->database->query("SELECT * FROM users WHERE token = ?", [$token])->fetchAll();
        if (!empty($result)) {
            return $result;
        } else {
            throw new UnauthorizedException('Invalid Token');
        }
    }
}
