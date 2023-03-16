<?php
namespace Controller;

use TemplateEngine\TemplateEngine;

require_once './lib/TemplateEngine.php';

/**
 * 
 * MVC base controller class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */


class Controller {



    public function model($model)
    {
        // require model file
        // return instance of model class
    }



    public function view(string $view, object $data)
    {
        ob_start();

        if( !is_object($data) )
        {
            echo '$data must be an object';
            return;
        }

        if (file_exists('./views/' . $view . '.php') ) {

            require_once './views/' . $view . '.php';

        } else {

            $data = (object) [
                'type' => '404',
                'message' => 'View file does not exist',
            ];

            require_once './views/error.php';
        }

        $view = ob_get_contents();
        $view = TemplateEngine::apply(view: $view, data: $data);
        ob_end_clean();
        echo $view;
    }


}