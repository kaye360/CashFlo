<?php
/**
 * 
 * Users Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for pages related to the 'users' table
 * 
 */
declare(strict_types=1);
namespace controllers\UsersController;

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use utils\GenericUtils\GenericUtils;
use stdClass;



class UsersController extends Controller {

    private $userModel;



    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * 
     * @method Sign up form
     * 
     */
    public function new() : void
    {
        $this->view('signup');
    }

    /**
     * 
     * @method Sign up a user 
     * 
     */
    public function create() : void
    {
        
        if( $_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            header('Location: /signup');
            exit();
        }

        $validator = InputHandler::validate([
            'username' => ['required', 'unique', 'max:15', 'min:6'],
            'confirm_password_1' => ['required', 'min:6', 'confirm_password'],
            'confirm_password_2' => ['required']
        ]);
            
        $data = new stdClass() ;
        $data->username = InputHandler::sanitize('username');
        $data->password = trim($_POST['confirm_password_1']);
        $data->confirm_password = trim($_POST['confirm_password_2']);
        $data->errors = $validator->errors;
        $data->errors->query = false;
        $data->success = $validator->success;

        if( $data->success ) 
        {
            $new_user = $this->userModel->create(data: $data);
            
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
     * @method Sign in form
     * 
     */
    public function sign_in() : void
    {
        $this->view('signin');
    }

    /**
     * 
     * @method Sign in a user
     * 
     */
    public function authenticate() : void
    {

        if( $_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            header('Location: /signup');
            exit();
        }

        $validator = InputHandler::validate([
            'username' => ['required'],
            'password' => ['required', 'user_pass_verify']
        ]);
        
        $data = new stdClass();
        $data->username = InputHandler::sanitize('username');
        $data->password = InputHandler::sanitize('password');
        $data->errors = $validator->errors;
        $data->success = $validator->success;
        
        if( $data->success) 
        {
            $this->userModel->destroy_session();
            $session = GenericUtils::make_UUID();
            $this->userModel->update_session(
                session: $session, username: $data->username
            );
            setcookie('session', $session, strtotime( '+30 days' ));
        }

        $this->view('signin', $data);
    }

    /**
     * 
     * @method Sign out a user form and sign out user
     * 
     */
    public function sign_out() : void
    {
        $data = new stdClass();
        $data->success = false;

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) 
        {
            $data->success = true;
            $this->userModel->destroy_session();
        }

        $this->view('signout', $data);
    }    
    
    /**
    * 
    * @return Dashboard Page
    * 
    */
   public function dashboard() : void
   {
       $this->view('dashboard');
   }

    /**
     * 
     * @method User settings form
     * 
     */
    public function settings() : void
    {
        $this->view('settings');
    }

    /**
     * 
     * @method Update user settings 
     * 
     */
    public function update_settings() : void
    {
        $validator = InputHandler::validate([
            'password' => ['user_pass_verify'],
            'confirm_password_1' => ['confirm_password', 'min:6']
        ]);

        $data = new stdClass();
        $data->success = false;
        $data->username = InputHandler::sanitize('username');
        $data->password = InputHandler::sanitize('password');
        $data->confirm_password_1 = InputHandler::sanitize('confirm_password_1');
        $data->confirm_password_2 = InputHandler::sanitize('confirm_password_2');
        $data->errors = $validator->errors;
        $data->success = $validator->success; 

        if( $data->success )
        {
            $this->userModel->update_settings($data);
        }

        $this->view('settings', $data);
    }

}