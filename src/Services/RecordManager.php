<?php

namespace Library\Services;

use Library\Repositories\AuthorRepository;
use Library\Repositories\BookRepository;
use Library\Services\Exceptions\ApplicationException;

/**
 * Description of RecordManager
 *
 * @author H1
 */
class RecordManager
{
    public function __construct(
        private AuthorRepository $authorRepository,
        private BookRepository $bookRepository
    ) {}
    
    public function insertAll(array $xmlBooks): bool
    {
        if (!$this->insertNewAuthors($xmlBooks)) {
            return false;
        }

        foreach ($xmlBooks as $xmlBook) {
            // Extract author name and book name from the XML
            $authorName = trim($xmlBook['xmlBook']->author);

            try {
                $author = $this->authorRepository->getOneBy('name', $authorName);
                if (is_null($author)) {
                    // We should never come inside here.
                    throw new \Exception('No such author in the database.');
                }

                $bookTitle = trim($xmlBook['xmlBook']->name);
                $book = $this->bookRepository->getOneByAuthorAndBookTitle($author, $bookTitle);
                if (is_null($book)) {
                    $book = $this->bookRepository->insertOne($author, $bookTitle);
                } else {
                    $book = $this->bookRepository->updateOne($book);
                }
            } catch (\Exception $ex) {
                // We record the real exception into the log file.
                Logger::error($ex->getMessage(), 'database_errors.log');

                // We hide the message from the common user.
                throw new ApplicationException('An application exception occured.');
            }
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
            // We record the real exception into the log file.
            Logger::error($ex->getMessage(), 'database_errors.log');

            // We hide the message from the common user.
            throw new ApplicationException('An application exception occured.');
        }
        
        return $isInsert;
    }
}
