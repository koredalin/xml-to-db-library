<?php

namespace Library\Repositories;

use PDO;
use Library\Entities\Author;
use Library\Entities\Factories\BookFactory;
use Library\Entities\Book;

/**
 * Description of BookRepository
 *
 * @author H1
 */
class BookRepository
{
    public const TABLE_NAME = "books";

    public function __construct(
        private PDO $conn
    ) {}

    /**
     * Inserts a book into the "books" db table.
     * If the record exists - returns its id only.
     * 
     * @param string $book
     * @return int
     */
    public function insertOrUpdateOne(Author $author, string $bookName): Book
    {
        $authorId = $author->getId();
        var_dump($bookName);
        $stmt = $this->conn->prepare("SELECT id, author_id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE author_id = :author_id AND name = :name");
        $stmt->bindParam(":author_id", $authorId);
        $stmt->bindParam(":name", $bookName);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            var_dump(__LINE__);
            $timestamp = time();
            // Актуализация на съществуваща книга
            $updateStmt = $this->conn->prepare("UPDATE ".self::TABLE_NAME." SET author_id = :author_id, name = :name, updated_at = {$timestamp} WHERE id = :id");
            $updateStmt->bindParam(":author_id", $authorId);
            $updateStmt->bindParam(":name", $bookName);
            $bookArr = $stmt->fetch();
            $updateStmt->bindParam(":id", $bookArr['id']);
            $updateStmt->execute();

//            return $bookArr['id'];
//            $bookArr = $stmt->fetch(PDO::FETCH_ASSOC);
            $bookId = $bookArr['id'];
        } else {
            var_dump(__LINE__);
            // New book insert.
            $insertBook = $this->conn->prepare("INSERT INTO ".self::TABLE_NAME." (author_id, name) VALUES (:author_id, :name)");
            $insertBook->bindParam(":author_id", $authorId);
            $insertBook->bindParam(":name", $bookName);
            $insertBook->execute();
            $bookId = $this->conn->lastInsertId();
        }
            var_dump(__LINE__);
        // We read the new record.
        $stmt = $this->conn->prepare("SELECT id, author_id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE id = " . (int) $bookId);
        $stmt->execute();

        $bookArr = $stmt->fetch(PDO::FETCH_ASSOC);
        $book = BookFactory::createFromDb($bookArr);

        return $book;
    }
}
