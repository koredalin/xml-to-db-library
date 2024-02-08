<?php

namespace Library\Services;

use Library\Repositories\AuthorRepository;
use Library\Repositories\BookRepository;
use Library\Entities\Factories\AuthorFactory;
use Library\Entities\Author;
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
        // Insert all new authors with one DB query.
        if (!$this->insertNewAuthors($xmlBooks)) {
            return false;
        }

        // Read all DB authors with one DB query.
        $dbAuthors = $this->authorRepository->findAll();
        foreach ($xmlBooks as $xmlBook) {
            try {
                // Extract author name and book name from the XML
                $authorName = trim($xmlBook['xmlBook']->author);
                $bookTitle = trim($xmlBook['xmlBook']->name);
                
                // We filter current xml parsed author from database authors.
                $author = $this->filterAuthor($dbAuthors, $authorName);

                // TODO New books insert with one operation. Similar to the new authors insert.
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
            $authorsArr = $this->authorRepository->findAll();
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
    
    /**
     * Filters single author.
     * Creates an Author entity.
     *
     * @param array $dbAuthors
     * @param string $authorName
     * @return Author
     * @throws ApplicationException
     */
    private function filterAuthor(array $dbAuthors, string $authorName): Author
    {
        $filteredAuthors = array_filter($dbAuthors, function ($item) use ($authorName) {
            return $item['name'] === $authorName;
        });
        $authorAssoc = count($filteredAuthors) > 0 ? array_shift($filteredAuthors) : [];
        $author = AuthorFactory::createFromDb($authorAssoc);
        if (is_null($author)) {
            // We should never come inside here.
            throw new ApplicationException('No such author in the database.');
        }
        
        return $author;
    }
}
