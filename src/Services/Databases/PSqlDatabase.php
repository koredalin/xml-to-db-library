<?php

namespace Library\Services\Databases;

use PDO;

/**
 * Description of PSqlDatabase
 *
 * @author H1
 */
class PSqlDatabase extends AbstractDatabase
{
    public function getConnection(): PDO
    {
        if ($this->conn) {
            return $this->conn;
        }

        try {
            $dsn = "pgsql:host=" . $this->host . ";dbname=" . $this->dbName . ";options='--client_encoding=UTF8'";
            $this->conn = new PDO($dsn, $this->userName, $this->password);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
