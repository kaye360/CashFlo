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
     * @method Home Page
     * 
     */
    public function home() : void
    {
        $this->view('index');
    }

    /**
     * 
     * @method About Page
     * 
     */
    public function about() : void
    {
        $this->view('about');
    }

    /**
     * 
     * @method Error Page
     * 
     */
    public function error(
        string $type = '404 Not Found', 
        string $message = 'There was an error.'
    ) : void {

        $data          = new stdClass();
        $data->title   = 'Error';
        $data->h1      = 'Error 404';
        $data->type    = $type;
        $data->message = $message;
        $this->view('error', $data);
    }

    /**
     * 
     * @method Unauthorized Page
     * 
     */
    public function unauthorized() : void
    {
        $data        = new stdClass();
        $data->title = 'Unauthorized request';
        $data->h1    = 'Unauthorized request';
        $this->view('unauthorized', $data);
    }
}