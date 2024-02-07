<?php

namespace Library\Controllers;

use Library\Request;

/**
 * Description of HomeController
 *
 * @author H1
 */
class HomeController extends Controller
{
    public function index()
    {

        $this->render('index', [
            'title' => 'XML to DB Library',
        ]);
    }
}
