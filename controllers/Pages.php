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
    public function error() : void 
    {
        $data        = new stdClass();
        $data->title = 'Error';
        $data->h1    = 'Something went wrong';
        $data->type  = (int) $this->param ?? 400;

        switch ($this->param)
        {
            case 400:
                $data->message = 'An error occured. Please try again later.';
                break;
            case 401:
                $data->message = 'You must be logged in to view this page.';
                break;
            case 403:
                $data->message = 'You are not authorized to view this page.';
                break;
            case 404:
                $data->message = 'This page could not be found.';
                break;
            default:
                $data->type = 400;
                $data->message = 'Please try again later.';
                break;
        }

        $this->view('error', $data);
    }

}