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
    
    public function getAll(): array
    {
        $stmt = $this->conn->prepare("SELECT id, name, created_at, updated_at FROM ".self::TABLE_NAME.";");
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }

        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $authors;
    }

    public function getAllbyIds(array $ids): array
    {
        $stmt = $this->conn->prepare("SELECT id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE id IN (:ids);");
        $stmt->bindParam(":ids", $ids);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }

        $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $authors;
    }
    
    public function getOneBy(string $field, $value): ?Author
    {
//        var_dump(__LINE__);
        $field = trim($field);
        $stmt = $this->conn->prepare("SELECT id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE {$field} = :{$field};");
        $stmt->bindParam(":{$field}", $value);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $authorAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
//        print_r($authorAssoc);
        $author = AuthorFactory::createFromDb($authorAssoc);

        return $author;
    }

    public function insertMany(array $authorNames): bool
    {
        $placeholders = implode(',', array_fill(0, count($authorNames), '(?)'));
        $sql = "INSERT INTO " . self::TABLE_NAME . " (name) VALUES " . $placeholders;

        $stmt = $this->conn->prepare($sql);

        foreach (array_values($authorNames) as $index => $name) {
            $stmt->bindValue($index + 1, $name);
        }

        return $stmt->execute();
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
//        var_dump(__LINE__);
        // New author insert.
        $insertAuthor = $this->conn->prepare("INSERT INTO ".self::TABLE_NAME." (name) VALUES (:name)");
        $insertAuthor->bindParam(":name", $authorName);
        $insertAuthor->execute();
        $authorId = $this->conn->lastInsertId();
        
        // We read the new record.
        $newAuthor = $this->getOneBy('id', (int) $authorId);

        return $newAuthor;
    }
    
    public function findByName(string $name): array
    {
//        var_dump(__LINE__);
        $field = trim($field);
        $stmt = $this->conn->prepare("SELECT id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE name ILIKE :name;");
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
