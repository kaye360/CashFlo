<?php
/**
 * 
 * User Model
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Stores database actions relating to the 'users' table
 * 
 */
declare(strict_types=1);
namespace models\UserModel;

use lib\Auth\Auth;
use lib\Database\Database;
use stdClass;

class UserModel {

    /**
     * 
     * @method create the PDO object
     * 
     */
    public function __construct(private Database $database)
    {
    }

    /**
     * 
     * @method create a new user
     * 
     */
    public function create(object $data) : object
    {
        $salt                   = substr(uniqid(), -5);
        $salted_password        = $data->password . $salt;
        $salted_hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);

        $create_new_user = $this->database
            ->table('users')
            ->cols('username, password, salt')
            ->values(" '$data->username', '$salted_hashed_password', '$salt' ")
            ->new();

        if( !$create_new_user ) 
        {
            return (object) [
                'error' => true, 
                'data'  => null
            ];
        }

        return (object) [
            'error' => false,
            'data'  => $data,
        ];
    }
    
    /**
     * 
     * @method update Session
     * 
     */
    public function update_session(
        string $session, 
        string $username
    ) : bool {

        return $this->database
            ->table('users')
            ->set("session = '$session' ")
            ->where("username = '$username' ")
            ->update();
    }

    /**
     * 
     * @method Destroy a current sign in session
     * Destroys both cookie and session in DB
     * 
     */
    public function destroy_session() : void
    {
        if( !isset($_COOKIE['session'])) return;

        $session = $_COOKIE['session'];

        setcookie('session', '', 1);
        unset($_COOKIE['session']);

        $this->database
            ->table('users')
            ->set("session = null")
            ->where("session = '$session' ")
            ->update();
    }

    /**
     * 
     * @method Update Users settings
     * 
     */
    public function update_settings(object $updated_settings) : void
    {
        $user_id = Auth::user_id();
        $query   = '';

        if( $updated_settings->password )
        {
            $salt                   = substr(uniqid(), -5);
            $salted_password        = $updated_settings->password . $salt;
            $salted_hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);
            $query .= "salt = '$salt', password = '$salted_hashed_password', ";
        }
        
        $settings                        = clone Auth::settings();
        $settings->transactions_per_page = (int) $updated_settings->transactions_per_page;
        $settings_json                   = json_encode( $settings );

        $query .= "settings = '$settings_json' ";

        $this->database
            ->table('users')
            ->set($query)
            ->where("id = $user_id")
            ->update();
    }

    /**
     * 
     * @method Update a single user setting
     * 
     */
    public function update_setting( string $setting, mixed $value )
    {
        $settings = clone Auth::settings();
        $user_id  = Auth::user_id();

        if( property_exists( $settings, $setting ) )
        {
            $settings->$setting = $value;
            $settings_json     = json_encode($settings);
            
            $this->database
                ->table('users')
                ->set("settings = '$settings_json' ")
                ->where("id = '$user_id' ")
                ->update();
        }
    }
}