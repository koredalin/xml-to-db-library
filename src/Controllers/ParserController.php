<?php

namespace Library\Controllers;

use Library\Request;

use Library\Services\Database;
use Library\Services\XmlIterator;
use Library\Repositories\AuthorRepository;
use Library\Repositories\BookRepository;
use Library\Services\RecordManager;

/**
 * Description of ParserController
 *
 * @author H1
 */
class ParserController extends Controller
{
    public function parseXml()
    {
        $xmlIterator = new XmlIterator();
        $recordManager = new RecordManager(
            $this->database,
            $xmlIterator,
            new AuthorRepository($this->db),
            new BookRepository($this->db)
        );

        // XML data directory path.
//        var_dump(XmlIterator::XML_FOLDER_PATH);
//exit;
        
        
        $xmlInputAsArray = $recordManager->iterateXml(XmlIterator::XML_FOLDER_PATH);

//        echo '<pre>';
//        print_r($xmlInputAsArray);
//        echo '</pre>';


        $recordManager->insertAll($xmlInputAsArray);
        
        
        $parsedXmlAsText = $xmlIterator->parseXMLFilesAsText(XmlIterator::XML_FOLDER_PATH, XmlIterator::XML_FOLDER_PATH);
//echo "<pre>";
//        print_r($parsedXmlAsText);
//echo "</pre>";
        
        
        
        $jsonResponseData = self::RETURN_JSON_SCELETON;
        $jsonResponseData['data']['parsed_xml_as_text'] = $parsedXmlAsText;

        $this->returnJson($jsonResponseData);
    }
}
