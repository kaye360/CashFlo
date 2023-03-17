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
        $model_file = './models/' . ucwords($model) . '.php';
        $model_class = ucwords($model) . 'Model\\' . ucwords($model) . 'Model';

        if (file_exists($model_file) ) {
            require_once($model_file);
        } else {
            die("Model File $model_file not Found");
        }

        if ( class_exists($model_class) ) {
            return new $model_class;
        } else {
            die("Model Class $model_class not found");
        }
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



    public function auth()
    {
        if( !isset($_COOKIE['session']) ) {
            header('Location: /unauthorized');
            die();
        }

        $user = $this->model('User');

        $is_user_authorized_query = $user->table('users')
            ->select('*')
            ->where("session = '$_COOKIE[session]' ")
            ->count();
            
        $is_user_authorized = $is_user_authorized_query['data'] !== 0;

        if( !$is_user_authorized ) {
            header('Location: /unauthorized');
            die();
        }

        return $this;
    }


}