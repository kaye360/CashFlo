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
declare(strict_types=1);
namespace controllers\PagesController;

use lib\Controller\Controller;
use stdClass;



class PagesController extends Controller {

    /**
     * 
     * @return Home Page
     * 
     */
    public function home() : void
    {
        $data = (object) [
            'title' => 'Spendly App',
            'h1' => 'Welcome to Spendly'
        ];
        $this->view('index', $data);
    }

    /**
     * 
     * @return About Page
     * 
     */
    public function about() : void
    {
        $data = (object) [
            'title' => 'About Spendly',
            'h1' => 'About Spendly'
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
    ) : void {

        $data = (object) [
            'title' => 'Error',
            'h1' => 'Error 404',
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
    public function unauthorized() : void
    {
        $data = new stdClass();
        $data->title = 'Unauthorized request';
        $data->h1 = 'Unauthorized request';
        $this->view('unauthorized', $data);
    }
}