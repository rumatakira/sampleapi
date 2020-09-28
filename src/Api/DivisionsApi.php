<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\Api;

use Cycle\ORM;
use OpenAPIServer\OrmEntities\Division;
use OpenAPIServer\Api\AbstractDivisionsApi;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotImplementedException;

class DivisionsApi extends AbstractDivisionsApi
{
    public function __construct()
    {
        /** @var ORM\ORMInterface $orm */
        include "bootstrap.php";
        $database = $orm->getSource(Division::class)->getDatabase();
        $this->database = $database;
    }

    /**
     * POST createDivision
     * Summary: Create division
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function createDivision($request, $response, $args)
    {
        $body = $request->getParsedBody();
        $divisionName = (key_exists('divisionName', $body)) ? $body['divisionName'] : null;
        $divisionCity = (key_exists('divisionCity', $body)) ? $body['divisionCity'] : null;
        $divisionTimeZone = (key_exists('divisionTimeZone', $body)) ? $body['divisionTimeZone'] : date_default_timezone_get();
        $divisionAddress = (key_exists('divisionAddress', $body)) ? $body['divisionAddress'] : null;
        $divisionCoordinates = (key_exists('divisionCoordinates', $body)) ? $body['divisionCoordinates'] : null;
        $divisionType = (key_exists('divisionType', $body)) ? $body['divisionType'] : null;
        $divisionCompanyName = (key_exists('divisionCompanyName', $body)) ? $body['divisionCompanyName'] : null;
        if (empty($divisionName) or empty($divisionCity) or empty($divisionAddress) or empty($divisionType) or empty($divisionCompanyName)) {
            return $response->withStatus(400);
        }

        // find division company
        try {
            // by ORM tools
            $select = $this->database->table('companies as c')->select('*');
            $divisionCompany = $select->where('c.company_name', $divisionCompanyName)->fetchAll();
            // raw SQL
            // $divisionCompany = $this->database->query("SELECT * FROM companies c WHERE c.company_name = ?", [$divisionCompanyName])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }
        
        // if company exist create new division
        if (empty($divisionCompany)) {
            return $response->withStatus(400, 'No such company');
        } else {
            $divisionCompanyId = intval($divisionCompany[0]['id']);
            try {
                // by ORM tools
                $insert = $this->database->insert('divisions');
                $insert->values([
                    'division_name' => $divisionName,
                    'division_address' => $divisionAddress,
                    'division_coordinates' => $divisionCoordinates,
                    'division_type' => $divisionType,
                    'company_id' => $divisionCompanyId]);
                $divisionId = $insert->run();

                $insertCity = $this->database->insert('cities');
                $insertCity->values([
                    'city_name' => $divisionCity,
                    'time_zone' => $divisionTimeZone,
                    'company_id' => $divisionCompanyId,
                    'division_id' => $divisionId]);
                $insertCity->run();
                return $response->withStatus(200, 'Division created');
            } catch (\Throwable $e) {
                return $response->withStatus(500);
            }
        }
    }

    /**
     * DELETE deleteDivision
     * Summary: Delete division
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function deleteDivision(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $divisionName = (key_exists('divisionName', $queryParams)) ? $queryParams['divisionName'] : null;
        $divisionCity = (key_exists('divisionCity', $queryParams)) ? $queryParams['divisionCity'] : null;
        
        if (empty($divisionName) or empty($divisionCity)) {
            return $response->withStatus(400);
        }

        // find division and the city
        try {
            // by ORM tools
            $select = $this->database->table('divisions as d')->select('*');
            $division = $select->leftJoin('cities as c')->on('c.division_id', 'd.id')->where('d.division_name', $divisionName)->fetchAll();
            // raw SQL
            // $division = $this->database->query("SELECT * FROM divisions d LEFT JOIN cities c ON c.division_id = d.id WHERE d.division_name = ?", [$divisionName])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        // if division exist delete division
        if ($division[0]['city_name'] != $divisionCity) {
            return $response->withStatus(400);
        } else {
            try {
                // by ORM tools
                $this->database->table('divisions as d')->delete()->where('d.division_name', $divisionName)->run();
                // raw SQL
                // $this->database->execute("DELETE FROM divisions d WHERE d.division_name = ?", [$divisionName]);
            } catch (\Throwable $e) {
                return $response->withStatus(500);
            }
        }
        
        return $response->withStatus(200, 'Division deleted');
    }

    /**
     * GET getCityDivisions
     * Summary: Get divisions in the specified city
     * Output-Formats: [application/json]
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function getCityDivisions(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $cityName = (key_exists('cityName', $queryParams)) ? $queryParams['cityName'] : null;
        if (empty($cityName)) {
            return $response->withStatus(400);
        }

        try {
            // by ORM tools
            $select = $this->database->table('cities as c')->select('*');
            $result = $select->rightJoin('divisions as d')->on('c.division_id', 'd.id')->where('c.city_name', $cityName)->orderBy('d.division_name')->fetchAll();
            // raw SQL
            // $result = $this->database->query("SELECT * FROM cities c RIGHT JOIN divisions d ON c.division_id = d.id WHERE c.city_name = ? ORDER BY d.division_name", [$cityName])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }
        if (!empty($result)) {
            $payload = json_encode($result);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(404, 'Divisions in the specified city not found');
    }

    /**
     * GET getDivision
     * Summary: Get division
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function getDivision(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $divisionName = (key_exists('divisionName', $queryParams)) ? $queryParams['divisionName'] : null;
        $divisionCity = (key_exists('divisionCity', $queryParams)) ? $queryParams['divisionCity'] : null;
        
        if (empty($divisionName) or empty($divisionCity)) {
            return $response->withStatus(400);
        }

        // find division and the city
        try {
            // by ORM tools
            $select = $this->database->table('divisions as d')->select('*');
            $division = $select->leftJoin('cities as c')->on('c.division_id', 'd.id')->where('d.division_name', $divisionName)->fetchAll();
            // raw SQL
            // $division = $this->database->query("SELECT * FROM divisions d LEFT JOIN cities c ON c.division_id = d.id WHERE d.division_name = ?", [$divisionName])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        if ($division[0]['city_name'] != $divisionCity) {
            return $response->withStatus(404, 'Divisions in the specified city not found');
        } else {
            if (!empty($division)) {
                $payload = json_encode($division);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                return $response->withStatus(404);
            }
        }
    }

    /**
     * GET getDivisionsAndCities
     * Summary: Get cities and divisions in them
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function getDivisionsAndCities(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        try {
            // by ORM tools
            $select = $this->database->table('cities as c')->select('*');
            $result = $select->rightJoin('divisions as d')->on('c.division_id', 'd.id')->orderBy('c.city_name')->fetchAll();
            // raw SQL
            // $result = $this->database->query("SELECT * FROM cities c RIGHT JOIN divisions d ON c.division_id = d.id ORDER BY c.city_name")->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }
        if (!empty($result)) {
            $payload = json_encode($result);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            return $response->withStatus(404, 'Empty database');
        }
    }

    /**
     * PUT updateDivision
     * Summary: Update division
     * Notes: This can only be done by the logged in user.
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function updateDivision(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $body = $request->getParsedBody();
        $divisionId = (key_exists('divisionId', $body)) ? intval($body['divisionId']) : null;
        $divisionName = (key_exists('divisionName', $body)) ? $body['divisionName'] : null;
       
        $divisionAddress = (key_exists('divisionAddress', $body)) ? $body['divisionAddress'] : null;
        $divisionCoordinates = (key_exists('divisionCoordinates', $body)) ? $body['divisionCoordinates'] : null;
        $divisionType = (key_exists('divisionType', $body)) ? $body['divisionType'] : null;
        $divisionCompanyName = (key_exists('divisionCompanyName', $body)) ? $body['divisionCompanyName'] : null;

        if (empty($divisionId) or empty($divisionName) or empty($divisionAddress) or empty($divisionCoordinates) or empty($divisionType) or empty($divisionCompanyName)) {
            return $response->withStatus(400);
        }

        // find division company
        try {
            // by ORM tools
            $select = $this->database->table('companies as c')->select('*');
            $divisionCompany = $select->where('c.company_name', $divisionCompanyName)->fetchAll();
            // raw SQL
            // $divisionCompany = $this->database->query("SELECT * FROM companies c WHERE c.company_name = ?", [$divisionCompanyName])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }
        // if company exist update division
        if (empty($divisionCompany)) {
            return $response->withStatus(404, 'No such company');
        } else {
            $divisionCompanyId = intval($divisionCompany[0]['id']);
            try {
                // by ORM tools
                $update = $this->database->table('divisions')->update([
                    'division_name' => $divisionName,
                    'division_address' => $divisionAddress,
                    'division_coordinates' => $divisionCoordinates,
                    'division_type' => $divisionType,
                    'company_id' => $divisionCompanyId]);
                $update->where('id', '=', $divisionId)->run();
                // raw SQL
                // $this->database->execute("UPDATE divisions d SET (division_name, division_address, division_coordinates, division_type, company_id) = (?,?,?,?,?) WHERE d.id = '{$divisionId}'", [$divisionName, $divisionAddress, $divisionCoordinates, $divisionType, $divisionCompanyId]);
                return $response->withStatus(200, 'Division updated');
            } catch (\Throwable $e) {
                return $response->withStatus(500);
            }
        }
    }
}
