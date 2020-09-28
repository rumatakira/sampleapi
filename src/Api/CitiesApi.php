<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\Api;

use Cycle\ORM;
use OpenAPIServer\OrmEntities\City;
use OpenAPIServer\Api\AbstractCitiesApi;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CitiesApi extends AbstractCitiesApi
{
    public function __construct()
    {
        /** @var ORM\ORMInterface $orm */
        include "bootstrap.php";
        $database = $orm->getSource(City::class)->getDatabase();
        $this->database = $database;
    }

    /**
     * GET getCities
     * Summary: Get cities list
     * Notes: Get cities list from the system;
     * Output-Formats: [application/json]
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */
    public function getCities(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        try {
            // by ORM tools
            $result = $this->database->table('cities')->select()->distinct()->columns('city_name', 'time_zone')->orderBy('city_name')->fetchAll();
            // raw SQL
            //$result = $this->database->query('SELECT DISTINCT city_name, time_zone FROM cities ORDER BY city_name')->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        $payload = json_encode($result);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
