<?php
/**
 * 
 * App Boostrap file for:
 * - autoloading classes 
 * - set up Auth
 * - config file.
 * - DB connection
 * 
 */

use lib\Auth\Auth;
use lib\DBConnect\DBConnect;
use lib\CSSComponents\CSSComponents;

/**
 * 
 * @global Config file constants
 * 
 */
require_once './config.php';

/**
 * 
 * @method Class autoloader function
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
spl_autoload_register( function($class)
{
    $class_array = explode('\\', $class);
     
    if( $class_array[1] !== 'Controller') 
    {
        $class_array[1] = str_replace('Controller' , '', $class_array[1]);
    }
     
    if( $class_array[1] !== 'Model') 
    {
        $class_array[1] = str_replace('Model' , '', $class_array[1]);
    }

    $class_file_path = '.';

    for($i = 0; $i < count($class_array) -1; $i++ )
    {
        $class_file_path .= '/' . $class_array[$i];
    }
    $class_file_path .= '.php';

    require_once $class_file_path;
});

/**
 * 
 * @var Database PDO connection
 * 
 */
define('DB_CONNECTION', DBConnect::connect() );

/**
 * 
 * @var Auth object constant
 * 
 */
define('AUTH', Auth::init() );

/**
 * 
 * @method For development debugging. 
 * 
 */
function q($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}