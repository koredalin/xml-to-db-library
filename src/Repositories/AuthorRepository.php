<?php

namespace Library\Repositories;

use PDO;
use Library\Entities\Factories\AuthorFactory;
use Library\Entities\Author;

/**
 * Description of AuthorRepository
 *
 * @author H1
 */
class AuthorRepository
{
    public const TABLE_NAME = "authors";

    public function __construct(
        private PDO $conn
    ) {}
    
    public function getOneBy(string $field, $value): ?Author
    {
        var_dump(__LINE__);
        $field = trim($field);
        $stmt = $this->conn->prepare("SELECT id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE {$field} = :{$field}");
        $stmt->bindParam(":{$field}", $value);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $authorAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
        print_r($authorAssoc);
        $author = AuthorFactory::createFromDb($authorAssoc);

        return $author;
    }

    /**
     * Inserts an author into the "authors" db table.
     * If the record exists - returns its id only.
     * 
     * @param string $authorName
     * @return \Library\Entities\Author
     */
    public function insertOne(string $authorName): Author
    {
        var_dump(__LINE__);
        // New author insert.
        $insertAuthor = $this->conn->prepare("INSERT INTO ".self::TABLE_NAME." (name) VALUES (:name)");
        $insertAuthor->bindParam(":name", $authorName);
        $insertAuthor->execute();
        $authorId = $this->conn->lastInsertId();
        
        // We read the new record.
        $newAuthor = $this->getOneBy('id', (int) $authorId);

        return $newAuthor;
    }
}
