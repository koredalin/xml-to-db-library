<?php

namespace Library\Services\Databases;

use PDO;

/**
 * Description of MySqlDatabase
 *
 * @author H1
 */
class MySqlDatabase extends AbstractDatabase
{
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
