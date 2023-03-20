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
namespace models\UserModel;

use lib\Database\Database;



class UserModel extends Database {

    /**
     * 
     * @method create the PDO object
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @method create a new user
     * 
     */
    public function create(object $data)
    {
        $salt = substr(uniqid(), -5);
        $salted_password = $data->password . $salt;
        $salted_hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);

        $create_new_user = $this->table('users')
            ->cols('username, password, salt')
            ->values(" '$data->username', '$salted_hashed_password', '$salt' ")
            ->new();

        if( !$create_new_user ) 
        {
            return (object) [
                'error' => true, 
                'data' => null
            ];
        }

        return (object) [
            'error' => false,
            'data' => $data,
        ];
    }

}