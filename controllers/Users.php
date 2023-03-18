<?php
namespace controllers\UsersController;

use lib\Controller\Controller;
use model\UserModel\UserModel;
use stdClass;

/**
 * 
 * Users Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 */



class UsersController extends Controller {
    


    public function sign_up()
    {
        $data = new stdClass();

        $data->title = 'Sign Up';
        $data->errors = false;
        $data->error_username_is_taken = false;
        $data->error_username_has_forbidden_chars = false;
        $data->error_username_has_too_many_chars = false;
        $data->error_passwords_dont_match = false;
        $data->error_password_too_short = false;
        $data->error_inputs_missing = false;
        $data->error_with_query = false;
        $data->success = false;

        if( $_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $user = $this->model('User');

            $data->username = trim($_POST['username']);
            $data->password = trim($_POST['password']);
            $data->confirm_password = trim($_POST['confirm_password']);

            $data = $this->validate_sign_up_form($data, $user);

            if( !$data->errors ) {

                $new_user = $user->create($data);

                if( $new_user->error ) {

                    $data->errors = true;
                    $data->error_with_query = true;

                } else {
                    $data->success = true;
                }
            }

        } else { // GET REQUEST
            $data->username = '';
            $data->password = '';
            $data->confirm_password = '';
        }

        $this->view('signup', $data);
    }



    private function validate_sign_up_form(object $data, object $user)
    {

        if( $user->is_taken( column: 'username', value: $data->username, table: 'users' )) {
            $data->errors = true;
            $data->error_username_is_taken = true;
        }

        if( $user->has_forbidden_chars([$data->username])) {
            $data->errors = true;
            $data->error_username_has_forbidden_chars = true;
        }

        if( $user->has_too_many_chars($data->username, 15)) {
            $data->errors = true;
            $data->error_username_has_too_many_chars = true;
        }

        if( $data->password !== $data->confirm_password) {
            $data->errors = true;
            $data->error_passwords_dont_match = true;
        }

        if( strlen($data->password) < 6) {
            $data->errors = true;
            $data->error_password_too_short = true;
        }

        if( empty($data->username) || empty($data->password) || empty($data->confirm_password) ) {
            $data->errors = true;
            $data->error_inputs_missing = true;
        }
        
        return $data;
    }


    /**
     * 
     * @todo before login, check if cookie 'session' is set.
     * If so, destroy it and remove it from the db so not extra
     * sessions are remaining
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

        }

        $this->view('signout', $data);
    }



    private function destroy_session()
    {
        if( !isset($_COOKIE['session'])) return;

        $session = $_COOKIE['session'];
        setcookie('session', '', 1);
        unset($_COOKIE['session']);

        $users = new UserModel();
        $users->table('users')
              ->set("session = ''")
              ->where("session = '$session' ")
              ->update();
    }
}