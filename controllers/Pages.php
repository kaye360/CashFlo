<?php
/**
 * 
 * Controller for static pages
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to control routes related to Static pages
 * 
 */
declare(strict_types=1);
namespace controllers\PagesController;

use lib\Controller\Controller;
use lib\Router\Route\Route;
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
        $error_messages = [
            400 => 'An error occured. Please try again later.',
            401 => 'You must be <a href="/signin" class="underline">logged in</a> to view this page.',
            403 => 'You are not authorized to view this page.',
            404 => 'This page could not be found.',
        ];

        $error_code = isset(Route::params()->code)
            ? (int) Route::params()->code
            : 400;

        $data          = new stdClass();
        $data->title   = 'Error';
        $data->h1      = 'Something went wrong';
        $data->type    = array_key_exists( $error_code, $error_messages)
                            ? $error_code
                            : 400;
        $data->message = array_key_exists($data->type, $error_messages)
                            ? $error_messages[$data->type]
                            : $error_messages[400];

        http_response_code($data->type);

        $this->view('error', $data);
    }

}