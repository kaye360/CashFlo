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
use lib\Redirect\Redirect\Redirect;

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
    private static bool    $is_logged_in = false;
    private static ?string $username     = null;
    private static ?int    $user_id      = null;
    private static ?string $settings     = null;

    /**
     * 
     * @method Check if user is logged in
     * Checks whether a session cookie is set and whether that
     * session is stored in the DB
     * 
     */
    private function __construct()
    {
        self::$is_logged_in = false;

        if( !isset($_COOKIE['session']) ) return;

        $db_query = new Database();
        
        // Check if there is an active session in the DB
        $user = $db_query
            ->table('users')
            ->select('username, id, settings')
            ->where("session = '$_COOKIE[session]' ")
            ->single();

        if( 
            isset($user->username) &&
            isset($user->id) 
        ) {
            self::$username     = $user->username;
            self::$user_id      = $user->id;
            self::$is_logged_in = true;
            self::$settings     = $user->settings;
        }
    }

    /**
     * 
     * @method Initialize Auth singleton
     * 
     */
    public static function init() : object
    {
        if (!self::$instance) self::$instance = new Auth();
        return self::$instance;
    }

    /**
     * 
     * @method Get username in UI
     * To be used in UI when username is needed
     * 
     */
    public static function username() : string
    {
        return (string) self::$username;
    }

    /**
     * 
     * @method Get user ID in UI
     * To be used in UI when user ID is needed
     * 
     */
    public static function user_id() : int
    {
        return (int) self::$user_id;
    }

    /**
     * 
     * @method Return if the user is logged in or not
     * To be used in UI for conditional rendering based on 
     * whether the user is logged in or not.
     * 
     */
    public static function is_logged_in() : bool
    {
        return (bool) self::$is_logged_in;
    }

    /**
     * 
     * @method Get user settings
     * 
     */
    public static function settings() : object
    {
        $settings = json_decode( self::$settings );

        if( !isset($settings->transactions_per_page) ) $settings->transactions_per_page = 25;

        return $settings;
    }

    /**
     * 
     * @method Authorize a user action
     * 
     */
    public static function authorize( ?int $id ) : void
    {
        if($id !== self::$user_id) 
        {
            Redirect::to('error/403')->prompt('error', 'You are not authorized for this action')->redirect();
        }
    }
}