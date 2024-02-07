<?php

use Library\Services\Database;
use Library\Router;
use Library\Controllers\HomeController;
use Library\Controllers\ParserController;

$dbConfig = require '../config/database.php';

$database = new Database(
    $dbConfig['host'],
    $dbConfig['dbName'],
    $dbConfig['userName'],
    $dbConfig['password']
);

$router = new Router($database);

$router->get('/', HomeController::class, 'index');
$router->get('/parser/parse_xml', ParserController::class, 'parseXml');

$router->dispatch();