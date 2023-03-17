<?php
namespace model\UserModel;

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
        $data->hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

        $create_new_user = $this->table('users')
            ->cols('username, password')
            ->values(" '$data->username', '$data->hashed_password' ")
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



	public function validate_password(
		string $username,
		string $password
	) {

		$user = $this->table('users')
			->select('username, password, id')
			->where("username = '$username'")
			->single();

		/**
		 * @todo make this return a one liner
		 */
		if (
			$user['success'] && 
			password_verify($password, $user['data']['password'])
		) {
			return true;
		} else {
			return false;
		}
	}



    public function make_UUID()
	{
		// Found Here: https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
	}

}