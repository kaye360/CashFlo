<?php
namespace models\UserModel;

use lib\Database\Database;

require_once './lib/Database.php';

/**
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */



class UserModel extends Database {



    public function __construct()
    {
        parent::__construct();
    }



    public function create(object $data)
    {
        $salt = substr(uniqid(), -5);
        $salted_password = $data->password . $salt;
        $salted_hashed_password = password_hash($salted_password, PASSWORD_DEFAULT);

        $create_new_user = $this->table('users')
            ->cols('username, password, salt')
            ->values(" '$data->username', '$salted_hashed_password', '$salt' ")
            ->new();

        if( !$create_new_user ) {
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



    public function make_UUID()
	{
		// Found Here: https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
	}

}