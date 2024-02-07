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
        $this->conn = $this->database->getConnection();
    }
    
    public function iterateXml(string $folderPath): array
    {
        return $this->iterator->iterate($folderPath);
    }
    
    public function insertAll(array $xmlBooks): bool
    {
        if (!$this->insertNewAuthors($xmlBooks)) {
            return false;
        }

        foreach ($xmlBooks as $xmlBook) {
            // Extract author name and book name from the XML
            $authorName = trim($xmlBook['xmlBook']->author);

//            echo '<pre>';
            try {
                $author = $this->authorRepository->getOneBy('name', $authorName);
                if (is_null($author)) {
                    // We should never come inside here.
                    throw new \Exception('No such author in the database.');
                }

                $bookName = trim($xmlBook['xmlBook']->name);
                $book = $this->bookRepository->getOneByAuthorAndBookName($author, $bookName);
                if (is_null($book)) {
                    $book = $this->bookRepository->insertOne($author, $bookName);
                } else {
                    $book = $this->bookRepository->updateOne($book);
                }

//                print_r($author);
//                print_r($book);
            } catch (\Exception $ex) {
                var_dump($ex->getMessage());
                Logger::error($ex->getMessage(), 'database_errors.log');

                return false;
            }
//            echo '</pre>';
        }

        return true;
    }
    
    /**
     * Filter and insert at once all new authors in the xml input list.
     *
     * @param array $xmlBooks
     * @return bool
     */
    private function insertNewAuthors(array $xmlBooks): bool
    {
        try {
            $authorsArr = $this->authorRepository->getAll();
            $authorNames = array_column($authorsArr, 'name');
            $xmlBooksAuthors = [];
            foreach ($xmlBooks as $bookKey => $xmlBook) {
                $xmlBooksAuthors[$bookKey] = trim($xmlBook['xmlBook']->author);
            }
            $newAuthors = array_diff($xmlBooksAuthors, $authorNames);
            if (empty($newAuthors)) {
                return true;
            }

            $isInsert = $this->authorRepository->insertMany($newAuthors);
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            Logger::error($ex->getMessage(), 'database_errors.log');

            return false;
        }
        
        return $isInsert;
    }
}
