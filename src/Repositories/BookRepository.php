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
        var_dump(__LINE__);
        $field = trim($field);
        $stmt = $this->conn->prepare("SELECT id, author_id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE {$field} = :{$field}");
        $stmt->bindParam(":{$field}", $value);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $bookAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
//        print_r($bookAssoc);
        $book = BookFactory::createFromDb($bookAssoc);

        return $book;
    }
    
    public function getOneByAuthorAndBookName(Author $author, string $bookName): ?Book
    {
        var_dump($bookName);
        $authorId = $author->getId();
        $stmt = $this->conn->prepare("SELECT id, author_id, name, created_at, updated_at FROM ".self::TABLE_NAME." WHERE author_id = :author_id AND name = :name");
        $stmt->bindParam(":author_id", $authorId);
        $stmt->bindParam(":name", $bookName);
        $stmt->execute();
        if ($stmt->rowCount() === 0) {
            return null;
        }

        $bookAssoc = $stmt->fetch(PDO::FETCH_ASSOC);
//        print_r($bookAssoc);
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
    public function insertOne(Author $author, string $bookName): Book
    {
        var_dump(__LINE__);
        $authorId = $author->getId();
        // New book insert.
        $insertBook = $this->conn->prepare("INSERT INTO ".self::TABLE_NAME." (author_id, name) VALUES (:author_id, :name)");
        $insertBook->bindParam(":author_id", $authorId);
        $insertBook->bindParam(":name", $bookName);
        $insertBook->execute();
        $bookId = $this->conn->lastInsertId();

        $newBook = $this->getOneBy('id', $bookId);

        return $newBook;
    }
    
    public function updateOne(Book $book): Book
    {
        var_dump(__LINE__);
        $bookId = $book->getId();
        $timestamp = time();
        // Актуализация на съществуваща книга
        $updateStmt = $this->conn->prepare("UPDATE ".self::TABLE_NAME." SET author_id = :author_id, name = :name, updated_at = {$timestamp} WHERE id = :id");
        $updateStmt->bindParam(":author_id", $book->author_id);
        $updateStmt->bindParam(":name", $book->name);
        $updateStmt->bindParam(":id", $bookId);
        $updateStmt->execute();

        $updatedBook = $this->getOneBy('id', $bookId);

        return $updatedBook;
    }
}
