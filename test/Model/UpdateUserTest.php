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
 * Please update the test case below to test the model.
 */
namespace OpenAPIServer\Model;

use PHPUnit\Framework\TestCase;
use OpenAPIServer\Model\UpdateUser;

/**
 * UpdateUserTest Class Doc Comment
 *
 * @package OpenAPIServer\Model
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 *
 * @coversDefaultClass \OpenAPIServer\Model\UpdateUser
 */
class UpdateUserTest extends TestCase
{

    /**
     * Setup before running any test cases
     */
    public static function setUpBeforeClass(): void
    {
    }

    /**
     * Setup before running each test case
     */
    public function setUp(): void
    {
    }

    /**
     * Clean up after running each test case
     */
    public function tearDown(): void
    {
    }

    /**
     * Clean up after running all test cases
     */
    public static function tearDownAfterClass(): void
    {
    }

    /**
     * Test "UpdateUser"
     */
    public function testUpdateUser()
    {
        $testUpdateUser = new UpdateUser();
        $namespacedClassname = UpdateUser::getModelsNamespace() . '\\UpdateUser';
        $this->assertSame('\\' . UpdateUser::class, $namespacedClassname);
        $this->assertTrue(
            class_exists($namespacedClassname),
            sprintf('Assertion failed that "%s" class exists', $namespacedClassname)
        );
        $this->markTestIncomplete(
            'Test of "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test attribute "userId"
     */
    public function testPropertyUserId()
    {
        $this->markTestIncomplete(
            'Test of "userId" property in "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test attribute "userRealName"
     */
    public function testPropertyUserRealName()
    {
        $this->markTestIncomplete(
            'Test of "userRealName" property in "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test attribute "userLoginName"
     */
    public function testPropertyUserLoginName()
    {
        $this->markTestIncomplete(
            'Test of "userLoginName" property in "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test attribute "userPassword"
     */
    public function testPropertyUserPassword()
    {
        $this->markTestIncomplete(
            'Test of "userPassword" property in "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test attribute "userPhone"
     */
    public function testPropertyUserPhone()
    {
        $this->markTestIncomplete(
            'Test of "userPhone" property in "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test attribute "companyUserBelogsTo"
     */
    public function testPropertyCompanyUserBelogsTo()
    {
        $this->markTestIncomplete(
            'Test of "companyUserBelogsTo" property in "UpdateUser" model has not been implemented yet.'
        );
    }

    /**
     * Test getOpenApiSchema static method
     * @covers ::getOpenApiSchema
     */
    public function testGetOpenApiSchema()
    {
        $schemaArr = UpdateUser::getOpenApiSchema();
        $this->assertIsArray($schemaArr);
    }
}
