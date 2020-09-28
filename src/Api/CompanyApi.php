<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

namespace OpenAPIServer\Api;

use Cycle\ORM;
use OpenAPIServer\OrmEntities\Company;
use OpenAPIServer\Api\AbstractCompanyApi;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class CompanyApi extends AbstractCompanyApi
{
    public function __construct()
    {
        /** @var ORM\ORMInterface $orm */
        include "bootstrap.php";
        $database = $orm->getSource(Company::class)->getDatabase();
        $this->database = $database;
    }

    /**
     * GET getCompanyInfo
     * Summary: Get company info from specified division
     * Output-Formats: [application/json]
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     */

    public function getCompanyInfo(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $divisionName = (key_exists('divisionName', $queryParams)) ? $queryParams['divisionName'] : null;
        if (empty($divisionName)) {
            return $response->withStatus(400);
        }
        
        try {
            // by ORM tools
            $select = $this->database->table('companies c')->select('*');
            $result = $select->rightJoin('divisions as d')->on('c.id', 'd.company_id')->where('d.division_name', $divisionName)->orderBy('c.company_name')->fetchAll();
            // raw SQL
            // $result = $this->database->query("SELECT * FROM companies c RIGHT JOIN divisions d ON c.id = d.company_id WHERE d.division_name = ? ORDER BY c.company_name", [$divisionName])->fetchAll();
        } catch (\Throwable $e) {
            return $response->withStatus(500);
        }

        if (!empty($result)) {
            $payload = json_encode($result);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }
        return $response->withStatus(404, 'No such division');
    }
}
