<?php
/**
 * 
 * Controller for static pages
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for pages that don't require a model
 * 
 */
namespace controllers\PagesController;

use lib\Controller\Controller;
use stdClass;



class PagesController extends Controller {

    /**
     * 
     * @return Home Page
     * 
     */
    public function home() {
        $data = (object) [
            'title' => 'Spendly App',
        ];
        $this->view('index', $data);
    }

    /**
     * 
     * @return About Page
     * 
     */
    public function about() {
        $data = (object) [
            'title' => 'About Spendly',
        ];
        $this->view('about', $data);
    }

    /**
     * 
     * @return Error Page
     * 
     */
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

    /**
     * 
     * @return Unauthorized Page
     * 
     */
    public function unauthorized()
    {
        $data = new stdClass();
        $data->title = 'Unauthorized request';
        $this->view('unauthorized', $data);
    }

    /**
     * 
     * @return Dashboard Page
     * 
     */
    public function dashboard()
    {
        $data = new stdClass();
        $data->title = 'Dashboard';
        $data->username = AUTH->username;

        $this->view('dashboard', $data);
    }
}