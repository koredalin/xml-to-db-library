<?php

$databaseParams = [
    'mysql' => [
        'host' => 'localhost',
        'dbName' => 'library',
        'userName' => 'root',
        'password' => '',
    ],
    'psql' => [
        'host' => 'localhost',
        'dbName' => 'library',
        'userName' => 'postgres',
        'password' => '',
    ],
];

// Secure database settings in git ignored file.
// require 'database.local.php';

return $databaseParams;