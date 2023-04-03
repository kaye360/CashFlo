<?php
/**
 * 
 * User Authorization Class (Singleton)
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * This class holds all authorization methods and variables such
 * as username, user ID, logged in status, etc.
 * It can be accessed globally through the AUTH constant
 * 
 */
declare(strict_types=1);
namespace lib\Auth;

use lib\Database\Database;



class Auth {

    /**
     * 
     * @var Instance Singleton var
     * Used to determine if Auth class has been instantiated or not
     * 
     */
    private static $instance = null;

    /**
     * 
     * @var User info
     * Stores user info to be used globally throughout the App.
     * Can be used if data needs to be returned, not echo'd
     * 
     */
    private $is_logged_in;
    private $username;
    private $user_id;

    /**
     * 
     * @method Check if user is logged in
     * Checks whether a session cookie is set and whether that
     * session is stored in the DB
     * 
     */
    private function __construct()
    {
        $this->is_logged_in = false;

        if( !isset($_COOKIE['session']) ) return;

        $userModel = new Database();

        // Check if there is an active session in the DB
        $user = $userModel
            ->table('users')
            ->select('username, id')
            ->where("session = '$_COOKIE[session]' ")
            ->single();

        if( 
            isset($user->data->username) &&
            isset($user->data->id) 
        ) {
            $this->username = $user->data->username;
            $this->user_id = $user->data->id;
            $this->is_logged_in = true;
        }
    }

    /**
     * 
     * @method Initialize Auth singleton
     * 
     */
    public static function init()
    {
        if (!self::$instance) self::$instance = new Auth();
        return self::$instance;
    }

    /**
     * 
     * @method Echo username in UI
     * To be used in UI when username is needed
     * 
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * 
     * @method Echo user ID in UI
     * To be used in UI when user ID is needed
     * 
     */
    public function user_id()
    {
        return $this->user_id;
    }

    /**
     * 
     * @method Return if the user is logged in or not
     * To be used in UI for conditional rendering based on 
     * whether the user is logged in or not.
     * 
     */
    public function is_logged_in()
    {
        return $this->is_logged_in;
    }

    /**
     * 
     * @method Authorize a user action
     * 
     */
    public function authorize(int $id)
    {
        // if id !== $this->user_id redirect and die
    }
}