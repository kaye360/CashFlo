<?php
namespace controllers\UsersController;

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use models\UserModel\UserModel;
use stdClass;

/**
 * 
 * Users Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 */



class UsersController extends Controller {
    


    public function sign_up_post()
    {
        
        if( $_SERVER['REQUEST_METHOD'] !== 'POST') {
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
            'password' => ['required', 'min:6', 'confirm_password']
        ]);

        $data->errors = $validator->errors;
        $data->errors->query = false;
        $data->success = $validator->success;

        // echo '<pre>';
        // var_dump($data->errors);
        // echo '</pre>';
        
        if( $data->success ) {
            
            $userModel = $this->model('User');
            $new_user = $userModel->create($data);
            
            if( $new_user->error ) {
                
                $data->success = false;
                $data->errors->query = true;

            } else {
                $data->success = true;
            }
        }


        $this->view('signup', $data);
    }
    
    public function sign_up_get()
    {
        $data = new stdClass();
        $data->title = 'Sign Up';
        $data->username = '';
        $data->password = '';
        $data->confirm_password = '';
        $this->view('signup', $data);
    }



    /**
     * 
     * 
     */
    public function sign_in() 
    {
        $data = new stdClass();
        $data->title = 'Sign In to Spendly';
        $data->errors = false;
        $data->error_invalid_username_password = false;
        $data->success = false;
        
        if( $_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->destroy_current_session();

            $user = $this->model('User');

            $data->username = trim($_POST['username']);
            $data->username = htmlspecialchars($data->username);
            $data->password = trim($_POST['password']);
            $data->password = htmlspecialchars($data->password);

            if( !$user->validate_password($data->username, $data->password)) {
                $data->errors = true;
                $data->error_invalid_username_password = true;
            }

            if( !$data->errors) {
                $data->success =  true;
                $session = $user->make_UUID();

                $user->table('users')
                    ->set("session = '$session' ")
                    ->where("username = '$data->username' ")
                    ->update();

                setcookie('session', $session, strtotime( '+30 days' ));
            }

            
        } else { // GET REQUEST

            $data->username = '';
            
        }

        $this->view('signin', $data);
    }



    public function sign_out()
    {
        $data = new stdClass();
        $data->title = 'Sign Out';
        $data->success = false;

        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            $data->success = true;
            $this->destroy_current_session();
        }

        $this->view('signout', $data);
    }



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