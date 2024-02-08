<?php

namespace Library\Controllers;

use Library\Services\AbstractDatabase;

use Library\Request;

/**
 * Description of Controller
 *
 * @author H1
 */
class Controller
{
    protected const RETURN_JSON_SCELETON = [
        'success' => true,
        'message' => 'The data was successfully processed.',
        'data' => [],
    ];

    protected $db;

    public function __construct(
        protected Request $request,
        protected AbstractDatabase $database
    ) {
        $this->db = $this->database->getConnection();
    }
    
    protected function render($view, $data = [])
    {
        extract($data);

        require __DIR__."/../views/{$view}.php";
    }
    
    protected function returnJson(array $data): void
    {
        $jsonData = json_encode($data);

        header('Content-Type: application/json');

        echo $jsonData;
    }
}
