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
    
    public function getOneBy(string $field, $value): ?Book
    {
        $field = trim($field);
        $stmt = $this->conn->prepare("SELECT id, author_id, title, created_at, updated_at FROM ".self::TABLE_NAME." WHERE {$field} = :{$field}");
        $stmt->bindParam(":{$field}", $value);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $bookAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
        $book = BookFactory::createFromDb($bookAssoc);

        return $book;
    }
    
    public function getOneByAuthorAndBookTitle(Author $author, string $bookTitle): ?Book
    {
        $authorId = $author->getId();
        $stmt = $this->conn->prepare("SELECT id, author_id, title, created_at, updated_at FROM ".self::TABLE_NAME." WHERE author_id = :author_id AND title = :title");
        $stmt->bindParam(":author_id", $authorId);
        $stmt->bindParam(":title", $bookTitle);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $bookAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
        $book = BookFactory::createFromDb($bookAssoc);

        return $book;
    }

    /**
     * Inserts a book into the "books" db table.
     * If the record exists - returns its id only.
     * 
     * @param string $book
     * @return int
     */
    public function insertOne(Author $author, string $bookTitle): Book
    {
        $authorId = $author->getId();
        // New book insert.
        $insertBook = $this->conn->prepare("INSERT INTO ".self::TABLE_NAME." (author_id, title) VALUES (:author_id, :title)");
        $insertBook->bindParam(":author_id", $authorId);
        $insertBook->bindParam(":title", $bookTitle);
        $insertBook->execute();
        $bookId = $this->conn->lastInsertId();

        $newBook = $this->getOneBy('id', $bookId);

        return $newBook;
    }
    
    public function updateOne(Book $book): Book
    {
        $bookId = $book->getId();
        $timestamp = time();
        $updateStmt = $this->conn->prepare("UPDATE ".self::TABLE_NAME." SET author_id = :author_id, title = :title, updated_at = {$timestamp} WHERE id = :id");
        $updateStmt->bindParam(":author_id", $book->author_id);
        $updateStmt->bindParam(":title", $book->title);
        $updateStmt->bindParam(":id", $bookId);
        $updateStmt->execute();

        $updatedBook = $this->getOneBy('id', $bookId);

        return $updatedBook;
    }
}
