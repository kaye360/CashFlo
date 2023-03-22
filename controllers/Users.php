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
    public function sign_up()
    {
        
        if( $_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            header('Location: /signup');
            exit();
        }
            
        $data = new stdClass();
        $data->title = 'Sign Up';
        $data->username = InputHandler::sanitize('username');
        $data->password = trim($_POST['confirm_password_1']);
        $data->confirm_password = trim($_POST['confirm_password_2']);

        $validator = InputHandler::validate([
            'username' => ['required', 'unique', 'max:15', 'min:6'],
            'confirm_password_1' => ['required', 'min:6', 'confirm_password'],
            'confirm_password_2' => ['required']
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
    public function sign_up_form()
    {
        $data = new stdClass();
        $data->title = 'Sign Up';
        $data->h1 = 'Sign Up to Spendly';

        $this->view('signup', $data);
    }

    /**
     * 
     * @method Sign in form
     * 
     */
    public function sign_in_form() 
    {
        $data = new stdClass();
        $data->title = 'Sign In to Spendly';
        $data->h1 = 'Sign In to Spendly';

        $this->view('signin', $data);
    }

    /**
     * 
     * @method Sign in a user
     * 
     */
    public function sign_in()
    {

        if( $_SERVER['REQUEST_METHOD'] !== 'POST') 
        {
            header('Location: /signup');
            exit();
        }

        $this->destroy_current_session();

        $data = new stdClass();
        $data->title = 'Sign In to Spendly';
        $data->h1 = 'Sign In to Spendly';
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
        $data->h1 = 'Sign out of your account';
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
    * @return Dashboard Page
    * 
    */
   public function dashboard()
   {
       $data = new stdClass();
       $data->title = 'Dashboard';
       $data->h1 = 'Dashboard: ' . AUTH->username;
       $data->username = AUTH->username;

       $this->view('dashboard', $data);
   }

    /**
     * 
     * @method User settings form
     * 
     */
    public function settings()
    {
        $data = new stdClass();
        $data->title = 'Settings';
        $data->h1 = 'Settings';

        $this->view('settings', $data);
    }

    /**
     * 
     * @method Update user settings 
     * 
     */
    public function update_settings()
    {
        $data = new stdClass();
        $data->title = 'Settings';
        $data->h1 = 'Settings';
        $data->success = false;

        $data->username = InputHandler::sanitize('username');
        $data->password = InputHandler::sanitize('password');
        $data->confirm_password_1 = InputHandler::sanitize('confirm_password_1');
        $data->confirm_password_2 = InputHandler::sanitize('confirm_password_2');

        $validator = InputHandler::validate([
            'password' => ['user_pass_verify'],
            'confirm_password_1' => ['confirm_password', 'min:6']
        ]);

        $data->errors = $validator->errors;
        $data->success = $validator->success;

        if( $data->success )
        {
            $userModel = $this->model('user');
            $userModel->update_settings($data);
        }

        $this->view('settings', $data);
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