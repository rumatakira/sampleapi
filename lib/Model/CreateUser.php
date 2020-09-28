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
 */
namespace OpenAPIServer\Model;

use OpenAPIServer\BaseModel;

/**
 * CreateUser
 *
 * @package OpenAPIServer\Model
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 */
class CreateUser extends BaseModel
{
    /**
     * @var string Models namespace.
     * Can be required for data deserialization when model contains referenced schemas.
     */
    protected const MODELS_NAMESPACE = '\OpenAPIServer\Model';

    /**
     * @var string Constant with OAS schema of current class.
     * Should be overwritten by inherited class.
     */
    protected const MODEL_SCHEMA = <<<'SCHEMA'
{
  "required" : [ "companyUserBelogsTo", "userLoginName", "userPassword", "userPhone", "userRealName" ],
  "type" : "object",
  "properties" : {
    "userRealName" : {
      "type" : "string",
      "example" : "Adam A.Williams"
    },
    "userLoginName" : {
      "type" : "string",
      "example" : "rod48"
    },
    "userPassword" : {
      "type" : "string",
      "example" : "123456789"
    },
    "userPhone" : {
      "type" : "string",
      "example" : "201-886-0269 x3767"
    },
    "companyUserBelogsTo" : {
      "type" : "string",
      "example" : "Schimmel-Balistreri"
    }
  }
}
SCHEMA;
}
