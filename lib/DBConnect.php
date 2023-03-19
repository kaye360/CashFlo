<?php
/**
 * 
 * PDO Connection Class (Singleton)
 * 
 */
namespace lib\DBConnect;


class DBConnect {



    private static $instance = null;
    private $query = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    private $connection;



    private function __construct()
    {
        $options = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        );
        $this->connection = new \PDO($this->query, DB_USER, DB_PASS, $options);
    }



    public static function connect()
    {
        if (!self::$instance) {
            self::$instance = new DBConnect();
        }
        return self::$instance;
    }



    public function connection()
    {
        return $this->connection;
    }
}