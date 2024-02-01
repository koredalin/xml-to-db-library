<?php

namespace Library\Services;

use Library\Services\Database;
use PDO;
use Library\Services\XmlIterator;
use Library\Repositories\AuthorRepository;
use Library\Repositories\BookRepository;
use Library\Entities\Author;
use Library\Entities\Book;

/**
 * Description of RecordManager
 *
 * @author H1
 */
class RecordManager
{
    private PDO $conn;
    
    public function __construct(
        private Database $database,
        private XmlIterator $iterator,
        private AuthorRepository $authorRepository,
        private BookRepository $bookRepository
    ) {
        $this->conn = $database->getConnection();
    }
    
    public function iterateXml(string $folderPath): array
    {
        return $this->iterator->iterate($folderPath);
    }
    
    public function insertAll(array $xmlBooks): bool
    {
        foreach ($xmlBooks as $xmlBook) {
            // Extract author name and book name from the XML
            $authorName = trim($xmlBook['xmlBook']->author);

            echo '<pre>';
            try {
                $author = $this->authorRepository->getOneBy('name', $authorName);
                if (is_null($author)) {
                    $author = $this->authorRepository->insertOne($authorName);
                }

                $bookName = trim($xmlBook['xmlBook']->name);
                $book = $this->bookRepository->getOneByAuthorAndBookName($author, $bookName);
                if (is_null($book)) {
                    $book = $this->bookRepository->insertOne($author, $bookName);
                } else {
                    $book = $this->bookRepository->updateOne($book);
                }

                print_r($author);
                print_r($book);
            } catch (\Exception $ex) {
                $recordLogToFile = 3;
                var_dump($ex->getMessage());
                error_log(
                    "Database exception: {$ex->getMessage()}",
                    $recordLogToFile,
                    __DIR__.'/../../logs/database_errors.log'
                );

                return false;
            }
            echo '</pre>';
        }

        return true;
    }
}
