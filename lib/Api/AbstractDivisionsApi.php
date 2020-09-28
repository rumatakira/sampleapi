<?php

/**
 * Sample API
 * PHP version 7.2
 *
 * @package OpenAPIServer
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 */

/**
 * This is a sample API
 * The version of the OpenAPI document: 1.0.0
 * Contact: rumatakira74@gmail.com.com
 * Generated by: https://github.com/openapitools/openapi-generator.git
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */
namespace OpenAPIServer\Api;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotImplementedException;

/**
 * AbstractDivisionsApi Class Doc Comment
 *
 * @package OpenAPIServer\Api
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 */
abstract class AbstractDivisionsApi
{

    /**
     * @var ContainerInterface|null Slim app container instance
     */
    protected $container;

    /**
     * Route Controller constructor receives container
     *
     * @param ContainerInterface|null $container Slim app container instance
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
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
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function createDivision(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $body = $request->getParsedBody();
        $message = "How about implementing createDivision as a POST method in OpenAPIServer\Api\DivisionsApi class?";
        throw new HttpNotImplementedException($request, $message);
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
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function deleteDivision(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $divisionName = (key_exists('divisionName', $queryParams)) ? $queryParams['divisionName'] : null;
        $divisionCity = (key_exists('divisionCity', $queryParams)) ? $queryParams['divisionCity'] : null;
        $message = "How about implementing deleteDivision as a DELETE method in OpenAPIServer\Api\DivisionsApi class?";
        throw new HttpNotImplementedException($request, $message);
    }

    /**
     * GET getCityDivisions
     * Summary: Get divisions in the specified city
     *
     * @param ServerRequestInterface $request  Request
     * @param ResponseInterface      $response Response
     * @param array|null             $args     Path arguments
     *
     * @return ResponseInterface
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function getCityDivisions(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $cityName = (key_exists('cityName', $queryParams)) ? $queryParams['cityName'] : null;
        $message = "How about implementing getCityDivisions as a GET method in OpenAPIServer\Api\DivisionsApi class?";
        throw new HttpNotImplementedException($request, $message);
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
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function getDivision(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $queryParams = $request->getQueryParams();
        $divisionName = (key_exists('divisionName', $queryParams)) ? $queryParams['divisionName'] : null;
        $divisionCity = (key_exists('divisionCity', $queryParams)) ? $queryParams['divisionCity'] : null;
        $message = "How about implementing getDivision as a GET method in OpenAPIServer\Api\DivisionsApi class?";
        throw new HttpNotImplementedException($request, $message);
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
     * @throws HttpNotImplementedException to force implementation class to override this method
     */
    public function getDivisionsAndCities(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $message = "How about implementing getDivisionsAndCities as a GET method in OpenAPIServer\Api\DivisionsApi class?";
        throw new HttpNotImplementedException($request, $message);
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
        $message = "How about implementing updateDivision as a PUT method in OpenAPIServer\Api\DivisionsApi class?";
        throw new HttpNotImplementedException($request, $message);
    }
}