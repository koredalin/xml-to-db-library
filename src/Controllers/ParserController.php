<?php

namespace Library\Controllers;

use Library\Services\XmlIterator;
use Library\Repositories\AuthorRepository;
use Library\Repositories\BookRepository;
use Library\Services\RecordManager;
use Library\Services\Logger;

/**
 * Description of ParserController
 *
 * @author H1
 */
class ParserController extends Controller
{
    public function transferXmlToDb()
    {
        $jsonResponseData = self::RETURN_JSON_SCELETON;

        try {
            $xmlIterator = new XmlIterator();
            $xmlInputAsArray = $xmlIterator->iterate(XmlIterator::XML_FOLDER_PATH);
            
            $recordManager = new RecordManager(
                new AuthorRepository($this->db),
                new BookRepository($this->db)
            );
            $recordManager->insertAll($xmlInputAsArray);
            
            $parsedXmlAsText = $xmlIterator->parseXMLFilesAsText(XmlIterator::XML_FOLDER_PATH, XmlIterator::XML_FOLDER_PATH);
            $jsonResponseData['data']['parsed_xml_as_text'] = $parsedXmlAsText;
        } catch(\Exception $ex) {
            Logger::error($ex->message(), 'db_errors.log');
            $jsonResponseData['success'] = false;
            $jsonResponseData['message'] = 'Database input failed. Please, review the error logs.';
        }
        
        $this->returnJson($jsonResponseData);
    }
}
