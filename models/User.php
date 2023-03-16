<?php
namespace UserModel;

use Database\Database;

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



    public function create(object $req_data)
    {
        echo 'create user';
    }

}