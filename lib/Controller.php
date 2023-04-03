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
declare(strict_types=1);
namespace lib\Controller;

use lib\Database\Database;
use lib\template\Template\Template;
use stdClass;

class Controller {

    /**
     * 
     * @method Allow all extended classes to access URL param from Router
     * 
     */
    public function __construct(protected mixed $param = null) { }

    /**
     * 
     * @method Get a specific model while in a controller class
     * 
     */
    public function model(string $model) : object | null
    {
        $model_file = './models/' . ucwords($model) . '.php';
        $model_class = 'models\\' . ucwords($model) . 'Model\\' . ucwords($model) . 'Model';

        if (file_exists($model_file) ) 
        {
            require_once($model_file);
        } else {
            $data = new stdClass();
            $data->title = 'Error';
            $data->h1 = 'Error 500';
            $data->message = 'Model File not found';
            $this->view('error', $data);
            return null;
            die();
        }

        if ( class_exists($model_class) ) 
        {
            return new $model_class(new Database);
        } else {
            $data = new stdClass();
            $data->title = 'Error';
            $data->h1 = 'Error 500';
            $data->message = 'Model class not found';
            $this->view('error', $data);
            return null;
            die();
        }
        return null;
    }

    /**
     * 
     * @method Get a specific from view while in a controller class
     * 
     */
    public function view(string $view, object $data = new stdClass() ) : void
    {
        ob_start();

        if( !is_object($data) )
        {
            echo '$data must be an object';
            return;
        }

        require_once './views/_layout/header.php';

        if (file_exists('./views/' . $view . '.php') ) 
        {
            require_once './views/' . $view . '.php';
        } else {
            $data = new stdClass();
            $data->title = 'Error';
            $data->h1 = 'Error 404';
            $data->message = 'View not found';
            require_once './views/error.php';
        }

        require_once './views/_layout/footer.php';

        $view = ob_get_contents();
        $template = new Template();
        $view = $template->apply($view, $data);
        ob_end_clean();
        echo $view;
    }

    /**
     * 
     * @method Make a specific view require authorization
     * 
     */
    public function auth() : static
    {
        if( !AUTH->is_logged_in() )
        {
            header('Location: /error/401');
            die();
        }
        return $this;
    }


}