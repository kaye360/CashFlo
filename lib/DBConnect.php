<?php
/**
 * 
 * PDO DB Connection Class (Singleton)
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to connect to the DB. 
 * Instantiated in bootstrap.php and Assigned 
 * to DB_CONNECTION constanct
 * May be called as DB_CONNECTION->connection()
 * when needed.
 * 
 */
declare(strict_types=1);
namespace lib\DBConnect;

use PDO;



class DBConnect {

    /**
     * 
     * @var Instance Singleton var
     * Used to determine if DBConnect class has been instantiated or not     
     * 
     */
    private static $instance = null;

    /**
     * 
     * @varr PDO connection vars
     * 
     */
    private $query = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    private $connection;

    /**
     * 
     * @method Create PDO instance
     * 
     */
    private function __construct()
    {
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
        );
        $this->connection = new PDO($this->query, DB_USER, DB_PASS, $options);
    }

    /**
     * 
     * @method Instantiation method
     * This is called as DBConnect::connect() to create an instance
     * in bootstrap.php
     * 
     */
    public static function connect() : DBConnect
    {
        if (!self::$instance) self::$instance = new DBConnect();
        return self::$instance;
    }

    /**
     * 
     * @method Return the PDO object
     * 
     */
    public function connection() : PDO
    {
        return $this->connection;
    }
}