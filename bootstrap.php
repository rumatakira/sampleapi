<?php
// bootstrap.php

use Cycle\Bootstrap;
use Doctrine\Common\Annotations\AnnotationRegistry;

require_once "vendor/autoload.php";

AnnotationRegistry::registerLoader('class_exists');

$config = Bootstrap\Config::forDatabase(
    'pgsql:host=127.0.0.1;dbname=apidb',           // connection dsn
        'postgres',                                    // username
        'postgres'                                     // password
);

// which directory contains our entities
$config = $config->withEntityDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'src/OrmEntities');

// log all SQL messages to STDERR
// $config = $config->withLogger(new Bootstrap\StderrLogger(true));

// enable schema cache (use /vendor/bin/cycle schema:update to flush cache), keep commented to disable caching
// $config = $config->withCacheDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'cache');
$orm = Bootstrap\Bootstrap::fromConfig($config);
