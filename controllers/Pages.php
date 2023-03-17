<?php
namespace controllers\PagesController;

use lib\Controller\Controller;
use stdClass;

require_once './lib/Controller.php';

/**
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */



class PagesController extends Controller {



    public function home() {
        $data = (object) [
            'title' => 'Spendly App',
        ];
        $this->view('index', $data);
    }



    public function about() {
        $data = (object) [
            'title' => 'About Spendly',
        ];
        $this->view('about', $data);
    }



    public function error(
        string $type = '404 Not Found', 
        string $message = 'There was an error.'
    ) {
        $data = (object) [
            'type' => $type,
            'message' => $message,
        ];
        $this->view('error', $data);
    }



    public function unauthorized()
    {
        $data = new stdClass();
        $data->title = 'Unauthorized request';
        $this->view('unauthorized', $data);
    }



    public function dashboard()
    {
        $data = new stdClass();
        $data->title = 'Dashboard';

        $this->view('dashboard', $data);
    }
}