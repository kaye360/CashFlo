<?php
/**
 * 
 * User Auth Class (Singleton)
 * 
 */
namespace lib\Auth;

use lib\Database\Database;

class Auth {



    private static $instance = null;

    private $is_logged_in;
    private $username;
    private $user_id;



    private function __construct()
    {
        $this->is_logged_in = false;

        if( !isset($_COOKIE['session']) ) return;

        $userModel = new Database();

        // Check if there is an active session in the DB
        $user = $userModel->table('users')
            ->select('username, id')
            ->where("session = '$_COOKIE[session]' ")
            ->single();

        if( 
            isset($user['data']['username']) &&
            isset($user['data']['id']) 
        ) {
            $this->username = $user['data']['username'];
            $this->user_id = $user['data']['id'];
            $this->is_logged_in = true;
        }
    }



    public static function init()
    {
        if (!self::$instance) {
            self::$instance = new Auth();
        }
        return self::$instance;
    }



    public function username()
    {
        echo $this->username;
    }



    public function user_id()
    {
        echo $this->user_id;
    }



    public function is_logged_in()
    {
        return $this->is_logged_in;
    }


}