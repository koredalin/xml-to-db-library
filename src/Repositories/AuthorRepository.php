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
    
    public function findAll(): array
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
        $field = trim($field);
        $stmt = $this->conn->prepare("SELECT id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE {$field} = :{$field};");
        $stmt->bindParam(":{$field}", $value);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $authorAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
        $author = AuthorFactory::createFromDb($authorAssoc);

        return $author;
    }

    public function insertMany(array $authorNames): bool
    {
        $uniqueAutorNames = array_values(array_unique($authorNames));
        $placeholders = implode(',', array_fill(0, count($uniqueAutorNames), '(?)'));
        $sql = "INSERT INTO " . self::TABLE_NAME . " (name) VALUES " . $placeholders;

        $stmt = $this->conn->prepare($sql);

        foreach ($uniqueAutorNames as $index => $name) {
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
        // New author insert.
        $insertAuthor = $this->conn->prepare("INSERT INTO ".self::TABLE_NAME." (name) VALUES (:name)");
        $insertAuthor->bindParam(":name", $authorName);
        $insertAuthor->execute();
        $authorId = $this->conn->lastInsertId();
        
        // We read the new record.
        $newAuthor = $this->getOneBy('id', (int) $authorId);

        return $newAuthor;
    }

    /**
     * The query search for author-book pairs from the database.
     *
     * @param string $name
     * @return array
     */
    public function findBooksByName(string $name): array
    {
        $name = '%' . trim(mb_strtolower($name)) . '%';
        $sqlStr = 
            'SELECT
                a.id AS author_id,
                a.name AS author_name,
                b.id AS book_id,
                b.title AS book_title
            FROM '.self::TABLE_NAME.' AS a
            LEFT JOIN '.BookRepository::TABLE_NAME.' AS b
            ON a.id = b.author_id
            WHERE LOWER(a.name) LIKE :name
            ORDER BY a.name, b.title;';
        $stmt = $this->conn->prepare($sqlStr);
        $stmt->bindParam(":name", $name);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * The query search for author-book pairs from the database.
     *
     * @param string $name
     * @return array
     */
    public function findAllBooks(): array
    {
        $sqlStr = 
            'SELECT
                a.id AS author_id,
                a.name AS author_name,
                b.id AS book_id,
                b.title AS book_title
            FROM '.self::TABLE_NAME.' AS a
            LEFT JOIN '.BookRepository::TABLE_NAME.' AS b
            ON a.id = b.author_id
            ORDER BY a.name, b.title;';
        $stmt = $this->conn->prepare($sqlStr);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
