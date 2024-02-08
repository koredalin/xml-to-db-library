<?php

require 'vendor/autoload.php';

$dbConfig = require 'config/database.php';

use Library\Request;
use Library\Services\Databases\PSqlDatabase;
use Library\Controllers\ParserController;

$request = new Request();

$database = new PSqlDatabase(
    $dbConfig['psql']['host'],
    $dbConfig['psql']['dbName'],
    $dbConfig['psql']['userName'],
    $dbConfig['psql']['password']
);

$controller = new ParserController($request, $database);
$controller->transferXmlToDbCron();