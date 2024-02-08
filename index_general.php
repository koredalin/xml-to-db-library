<?php

require 'vendor/autoload.php';

use Library\Services\AbstractDatabase;
use Library\Services\XmlIterator;
use Library\Repositories\AuthorRepository;
use Library\Repositories\BookRepository;
use Library\Services\RecordManager;

$dbConfig = require 'config/database.php';

$database = new AbstractDatabase(
    $dbConfig['host'],
    $dbConfig['dbName'],
    $dbConfig['userName'],
    $dbConfig['password']
);
$db = $database->getConnection();

$recordManager = new RecordManager(
    $database,
    new XmlIterator(),
    new AuthorRepository($db),
    new BookRepository($db)
);

// XML data directory path.
$folderPath = __DIR__.DIRECTORY_SEPARATOR."xml_data".DIRECTORY_SEPARATOR;
var_dump($folderPath);
$xmlInputAsArray = $recordManager->iterateXml($folderPath);

echo '<pre>';
print_r($xmlInputAsArray);
echo '</pre>';


$recordManager->insertAll($xmlInputAsArray);