<?php

use Library\Services\Databases\MySqlDatabase;
use Library\Services\Databases\PSqlDatabase;
use Library\Router;
use Library\Controllers\HomeController;
use Library\Controllers\ParserController;
use Library\Controllers\DataController;

$dbConfig = require '../config/database.php';

$database = new PSqlDatabase(
    $dbConfig['psql']['host'],
    $dbConfig['psql']['dbName'],
    $dbConfig['psql']['userName'],
    $dbConfig['psql']['password']
);

$router = new Router($database);

$router->get('/', HomeController::class, 'index');
$router->get('/parser/xml_to_db', ParserController::class, 'transferXmlToDb');
$router->get('/data/search_by_author', DataController::class, 'searchBooksByAuthorName');

$router->dispatch();