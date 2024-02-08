<?php

namespace Library\Services\Databases;

use PDO;

/**
 * Description of AbstractDatabase
 *
 * @author H1
 */
abstract class AbstractDatabase
{
    protected ?PDO $conn;

    public function __construct(
        protected string $host,
        protected string $dbName,
        protected string $userName,
        protected string $password
    ) {
        $this->conn = null;
    }
    
    abstract public function getConnection(): PDO;
}
