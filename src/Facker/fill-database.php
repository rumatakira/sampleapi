<?php
/** @author Kirill A.Lapchinsky rumatakira74@gmail.com
 *  @copyright 2019 Kirill A.Lapchinsky All Rights Reserved
 *  @license MIT
 */

require_once __DIR__ . "/../../vendor/autoload.php";

use Cycle\ORM;
use Cycle\ORM\Schema;
use OpenAPIServer\OrmEntities\Company;
use OpenAPIServer\OrmEntities\Division;
use OpenAPIServer\OrmEntities\City;
use OpenAPIServer\OrmEntities\User;

// crate Faker to fill Entities
$faker = Faker\Factory::create();
$faker->seed(8888);
/** @var ORM\ORMInterface $orm */
include "bootstrap.php";

// drop tables if exists
{
    $entitiesClasses = [Company::class, Division::class, City::class, User::class];
    foreach ($entitiesClasses as $value) {
        $database = $orm->getSource($value)->getDatabase();
        $table = $orm->getSource($value)->getTable();
        $database->query("DROP TABLE IF EXISTS $table CASCADE");
    }
}

// cycle orm schema:sync
system("./vendor/bin/cycle schema:sync") . "\n";

// get table columns instead of entity properties
function getTableColumns($entityClass, &$orm): array
{
    $tableColumns = [];
    $columns = $orm->getSchema()->define($entityClass, Schema::COLUMNS);
    $fieldsAmmount = count($columns);
    foreach ($columns as &$value) {
        $tableColumns[] = $value;
    }
    $tableColumns = array_slice($tableColumns, 1);
    return $tableColumns;
}

// fill database for company
{
    $tableColumns = getTableColumns(Company::class, $orm);
    $database = $orm->getSource(Company::class)->getDatabase();
    $table = $orm->getSource(Company::class)->getTable();
    $insert = $database->insert($table);
    $insert->columns($tableColumns);
    for ($i = 1; $i <= 9; $i++) {
        //We don't need to specify key names in this case
        $insert->values([
            $faker->unique()->company,
            $faker->randomElement($array = array('Business Corporation', 'Limited Liability Company', 'Partnership', 'Joint venture')),
            $faker->unique()->name
        ]);
    }
    $insert->run();
}

// fill database for division
{
    $tableColumns = getTableColumns(Division::class, $orm);
    $database = $orm->getSource(Division::class)->getDatabase();
    $table = $orm->getSource(Division::class)->getTable();
    $insert = $database->insert($table);
    $insert->columns($tableColumns);
    for ($i = 0; $i < 20; $i++) {
        $insert->values([
            $faker->unique()->streetName,
            $faker->unique()->streetAddress,
            "Latitude " . $faker->latitude . " Longitude " . $faker->longitude,
            $faker->randomElement($array = array('cafe', 'office')),
            $faker->randomDigitNotNull
        ]);
    }
    $insert->run();
}

// fill database for City
{
    $tableColumns = getTableColumns(City::class, $orm);
    $database = $orm->getSource(City::class)->getDatabase();
    $table = $orm->getSource(City::class)->getTable();
    $insert = $database->insert($table);
    $insert->columns($tableColumns);
    $city = null;
    for ($i = 0; $i < 20; $i++) {
        if ($i == 1 or $i == 4) {
            $city = "Moscow";
            $timeZone = 'Europe/Moscow';
            $companyId = '2';
        } else {
            $city = $faker->city;
            $timeZone = date_default_timezone_get();
            $companyId = $faker->randomDigitNotNull;
        }
        $insert->values([
            $city,
            $timeZone,
            $companyId,
            $faker->unique()->numberBetween($min = 1, $max = 20)
        ]);
    }
    $insert->run();
}

// fill database for User
{
    $tableColumns = getTableColumns(User::class, $orm);
    $database = $orm->getSource(User::class)->getDatabase();
    $table = $orm->getSource(User::class)->getTable();
    $insert = $database->insert($table);
    $insert->columns($tableColumns);
    for ($i = 0; $i < 20; $i++) {
        $user_login_status = $faker->randomElement($array = array('true', 'false'));
        if ($user_login_status === 'false') {
            $token = null;
        } else {
            $token = "bearerToken";
        }
        $insert->values([
            $faker->unique()->name,
            $faker->unique()->userName,
            $faker->unique()->password,
            $faker->unique()->phoneNumber,
            $user_login_status,
            $token,
            $faker->randomDigitNotNull
        ]);
    }
    $insert->run();
}
