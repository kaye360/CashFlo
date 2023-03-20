<?php
/**
 * 
 * MVC base controller class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * This class is to be extended by any controller class
 * to call a model and pass model data to a view. Views
 * that require login may be called with auth() pre-chained 
 * before in the router callback function
 * 
 * @example Standard implementation
 * (new PagesController())->about();
 * 
 * @example Authorized immplementation
 * (new UsersController())->auth()->dashboard();
 * 
 */
namespace lib\Controller;

use lib\TemplateEngine\TemplateEngine;



class Controller {

    /**
     * 
     * @method Get a specific model while in a controller class
     * 
     */
    public function model($model)
    {
        $model_file = './models/' . ucwords($model) . '.php';
        $model_class = 'models\\' . ucwords($model) . 'Model\\' . ucwords($model) . 'Model';

        if (file_exists($model_file) ) 
        {
            require_once($model_file);
        } else {
            die("Model File $model_file not Found");
        }

        if ( class_exists($model_class) ) 
        {
            return new $model_class;
        } else {
            die("Model Class $model_class not found");
        }
    }

    /**
     * 
     * @method Get a specific from view while in a controller class
     * 
     */
    public function view(string $view, object $data)
    {
        ob_start();

        if( !is_object($data) )
        {
            echo '$data must be an object';
            return;
        }

        require_once './views/layout/header.php';

        if (file_exists('./views/' . $view . '.php') ) 
        {
            require_once './views/' . $view . '.php';
        } else {
            $data = (object) [
                'type' => '404',
                'message' => 'View file does not exist',
            ];

            require_once './views/error.php';
        }

        require_once './views/layout/footer.php';

        $view = ob_get_contents();
        $view = TemplateEngine::apply(view: $view, data: $data);
        ob_end_clean();
        echo $view;
    }


    /**
     * 
     * @method Make a specific view require authorization
     * 
     */
    public function auth()
    {
        if( !AUTH->is_logged_in() )
        {
            header('Location: /unauthorized');
            die();
        }
        return $this;
    }


}