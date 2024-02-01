<?php

namespace Library\Services;

use XMLReader;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;


/**
 * Description of XmlIterator
 *
 * @author H1
 */
class XmlIterator
{
//    private string $dirPath;
//    private array $rowData;
//
//
//    public function setDirPath(string $path): void
//    {
//        $this->dirPath = $path;
//    }
//
//    public function getDirPath(): string
//    {
//        return $this->dirPath;
//    }

    public function iterate(string $dirPath): array
    {
        libxml_use_internal_errors(true);

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath), RecursiveIteratorIterator::LEAVES_ONLY);
//        echo '<pre>';
        $result = [];
        
        foreach ($files as $name => $file) {
            // Skip non xml files.
            if ($file->isDir() || strtolower($file->getExtension()) !== 'xml') {
                continue;
            }
            
            $xmlContent = simplexml_load_file($file->getRealPath());

            if ($xmlContent === false) {
                $recordLogToFile = 3;
                // Record the errors into log.
                foreach (libxml_get_errors() as $error) {
                    error_log(
                        "XML error in file {$file->getRealPath()}: {$error->message}",
                        $recordLogToFile,
                        __DIR__.'/../../logs/xml_errors.log'
                    );
                }
                libxml_clear_errors();
                continue;
            }

            foreach ($xmlContent->book as $xmlBook) {
//                print_r($xmlBook);
                $result[] = [
                    'filePath' => $file->getRealPath(),
                    'xmlBook' => $xmlBook,
                ];
            }
        }
//        echo '</pre>';
        libxml_use_internal_errors(false);
        
        return $result;
    }
}
