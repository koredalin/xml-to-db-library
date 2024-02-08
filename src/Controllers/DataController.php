<?php

namespace Library\Controllers;

use Library\Repositories\AuthorRepository;
use Library\Services\Logger;

/**
 * Description of DataController
 *
 * @author H1
 */
class DataController extends Controller
{
    public function searchBooksByAuthorName()
    {
        $jsonResponseData = self::RETURN_JSON_SCELETON;

        try {
            $authorNameSearchStr = $this->request->get('author_name');
            $authorRepository = new AuthorRepository($this->db);
            $jsonResponseData['data'] = $authorRepository->findBooksByName($authorNameSearchStr);
        } catch(\Exception $ex) {
            Logger::error($ex->getMessage(), 'db_errors.log');
            $jsonResponseData['success'] = false;
            $jsonResponseData['message'] = 'Database input failed. Please, review the error logs.';
        }
        
        $this->returnJson($jsonResponseData);
    }
}
