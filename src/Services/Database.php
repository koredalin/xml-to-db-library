<?php

namespace Library\Services;

use PDO;

/**
 * Description of Database
 *
 * @author H1
 */
class Database
{
    private ?PDO $conn;

    public function __construct(
        private string $host,
        private string $dbName,
        private string $userName,
        private string $password
    ) {
        $this->conn = null;
    }

    public function getConnection(): PDO
    {
        if ($this->conn) {
            return $this->conn;
        }
        
        try {
            $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbName, $this->userName, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Database connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
