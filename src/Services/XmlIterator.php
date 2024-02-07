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
    // Default XML content folder path.
    public const XML_FOLDER_PATH = __DIR__ . DIRECTORY_SEPARATOR
        . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        . 'xml_data' . DIRECTORY_SEPARATOR;
    
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
                // Record the errors into log.
                foreach (libxml_get_errors() as $error) {
                    $parserError = "XML error in file {$file->getRealPath()}: {$error->message}";
                    Logger::error($parserError, 'xml_parser_errors.log');
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

    /**
     * $defaultDir is an optional parameter.
     * If we want to trim the general folder path from each file path in the final result - we should set it same as the original $dir parameter.
     * 
     * @param string $dir
     * @param string $defaultDir
     * @return array
     */
    public function parseXMLFilesAsText(string $dir, string $defaultDir = ''): array
    {
        $results = [];
        $files = scandir($dir);

        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                if (strpos($path, '.xml') !== false) {
                    $contents = file_get_contents($path);
                    // The URI folder.
                    $subfolderPath = strlen($defaultDir) > 0 ? str_replace(realpath($defaultDir . DIRECTORY_SEPARATOR), '', $path) : $path;
                    $results[] = ['path' => $subfolderPath, 'content' => $contents]; // Добавяне на пътя и съдържанието към масива
                }
            } elseif ($value != "." && $value != "..") {
                // Recursive subfolders reading.
                $results = array_merge($results, $this->parseXMLFilesAsText($path, $defaultDir));
            }
        }

        return $results;
    }

}
