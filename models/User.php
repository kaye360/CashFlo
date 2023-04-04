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

use lib\Database\Database;



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
    public function update_settings(object $data) : void
    {
        $new_password           = $data->confirm_password_1;
        $salt                   = substr(uniqid(), -5);
        $salted_password        = $new_password . $salt;
        $salted_hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);
        $user_id                = AUTH->user_id();

        $this->database
            ->table('users')
            ->set("salt = '$salt', password = '$salted_hashed_password' ")
            ->where("id = $user_id")
            ->update();
    }
}