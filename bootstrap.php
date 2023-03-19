<?php
/**
 * 
 * App Boostrap file for:
 * - autoloading classes 
 * - config file.
 * - DB connection
 * 
 */

use lib\DBConnect\DBConnect;

 /*
 * 
 * Class autoloader function
 * 
 * Note:
 * The strings 'Controller' and 'Model' are removed from namespaced class name,
 * but also allows the file to be called Controller.php or Model.php
 * 
 * Example:
 * Controller class name with be UsersController, but we only 
 * want to include Users.php, while also allowing for the posssilbilty
 * of a file name Controller.php
 * 
 */

 
/**
 * 
 * App Config file
 * 
 */
require_once './config.php';



spl_autoload_register( function($class)
{
    $class_array = explode('\\', $class);
     
    if( $class_array[1] !== 'Controller') {
        $class_array[1] = str_replace('Controller' , '', $class_array[1]);
    }
     
    if( $class_array[1] !== 'Model') {
        $class_array[1] = str_replace('Model' , '', $class_array[1]);
    }

    $class_file_path = './' . $class_array[0] . '/' . $class_array[1] . '.php';

    require_once $class_file_path;
});



/**
 * 
 * Database PDO connection
 * 
 */
define('DB_CONNECTION', DBConnect::connect() );