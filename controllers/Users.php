<?php
/**
 * 
 * Users Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for pages relative to the 'users' table
 * 
 */
namespace controllers\UsersController;

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use models\UserModel\UserModel;
use utils\GenericUtils\GenericUtils;
use stdClass;



class UsersController extends Controller {

    /**
     * 
     * @method Sign up a user 
     * 
     */
    public function sign_up_post()
    {
        
        if( $_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            header('Location: /signup');
            exit();
        }
            
        $data = new stdClass();
        $data->title = 'Sign Up';
        $data->username = InputHandler::sanitize('username');
        $data->password = trim($_POST['password']);
        $data->confirm_password = trim($_POST['confirm_password']);

        $validator = InputHandler::validate([
            'username' => ['required', 'unique', 'max:15', 'min:6'],
            'password' => ['required', 'min:6', 'confirm_password'],
            'confirm_password' => ['required']
        ]);

        $data->errors = $validator->errors;
        $data->errors->query = false;
        $data->success = $validator->success;

        if( $data->success ) 
        {
            $userModel = $this->model('User');
            $new_user = $userModel->create($data);
            
            if( $new_user->error ) 
            {
                $data->success = false;
                $data->errors->query = true;

            } else {
                $data->success = true;
            }
        }

        $this->view('signup', $data);
    }
    
    /**
     * 
     * @method Sign up form
     * 
     */
    public function sign_up_get()
    {
        $data = new stdClass();
        $data->title = 'Sign Up';
        $data->username = '';
        $data->password = '';
        $data->confirm_password = '';

        $data->success = false;
        $data->errors = new stdClass();
        $data->errors->username = new StdClass();
        $data->errors->username->has_error = false;
        $data->errors->password = new stdClass();
        $data->errors->password->has_error = false;
        $data->errors->confirm_password = new stdClass();
        $data->errors->confirm_password->has_error = false;
        $data->errors->query = false;
        $this->view('signup', $data);
    }

    /**
     * 
     * @method Sign in form
     * 
     */
    public function sign_in_get() 
    {
        $data = new stdClass();
        $data->title = 'Sign In to Spendly';
        $data->errors = new stdClass();
        $data->errors->password = new stdClass();
        $data->errors->password->has_error = false;
        $data->success = false;
        $data->username = '';

        $this->view('signin', $data);
    }

    /**
     * 
     * @method Sign in a user
     * 
     */
    public function sign_in_post()
    {

        if( $_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            header('Location: /signup');
            exit();
        }

        $this->destroy_current_session();

        $data = new stdClass();
        $data->title = 'Sign In to Spendly';
        $data->username = '';

        $data->username = InputHandler::sanitize('username');
        $data->password = InputHandler::sanitize('password');

        $validator = InputHandler::validate([
            'username' => ['required'],
            'password' => ['required', 'user_pass_verify']
        ]);

        $data->errors = $validator->errors;
        $data->success = $validator->success;

        if( $data->success) 
        {
            $userModel = $this->model('User');
            $session = GenericUtils::make_UUID();

            $userModel->table('users')
                ->set("session = '$session' ")
                ->where("username = '$data->username' ")
                ->update();

            setcookie('session', $session, strtotime( '+30 days' ));
        }

        $this->view('signin', $data);
    }

    /**
     * 
     * @method Sign out a user form and sign out user
     * 
     */
    public function sign_out()
    {
        $data = new stdClass();
        $data->title = 'Sign Out';
        $data->success = false;

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
        {
            $data->success = true;
            $this->destroy_current_session();
        }

        $this->view('signout', $data);
    }

    /**
     * 
     * @method Destroy a current sign in session
     * Destroys both cookie and session in DB
     * 
     */
    private function destroy_current_session()
    {
        if( !isset($_COOKIE['session'])) return;

        $session = $_COOKIE['session'];
        setcookie('session', '', 1);
        unset($_COOKIE['session']);

        $users = new UserModel();
        $users->table('users')
              ->set("session = null")
              ->where("session = '$session' ")
              ->update();
    }
}