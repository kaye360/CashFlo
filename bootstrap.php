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
use lib\Router\Route\Route;

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
 * User Controller class name will be UsersController, but we only 
 * want to include Users.php, while also allowing for the posssilbilty
 * of a file name Controller.php
 * 
 */
spl_autoload_register( function($class)
{
    $class_array = explode('\\', $class);
    $file_name = $class_array[1];
     
    if( $file_name !== 'Controller') 
    {
        $file_name = str_replace('Controller' , '', $file_name);
    }
     
    if( $file_name !== 'Model') 
    {
        $file_name = str_replace('Model' , '', $file_name);
    }

    $class_array[1] = $file_name;
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
Auth::init();
define('AUTH', Auth::init() );

/**
 * 
 * @method Inititialize Route Facade and include routes
 * 
 */
Route::init();
require_once './routes/routes.php';
Route::resolve();
// q(Route::params());
/**
 * 
 * @method Error Handler
 * 
 */
set_error_handler( function(Throwable $e)
{
    echo <<<EOT
        <div class="border border-gray-400 bg-red-50 p-4 m-2">
            Error Code:  $e->getCode() <br>
            Description: $e->getMessage() <br>
            File:        $e->getFile <br>
            Line:        $e->getLine()
        </div>
    EOT;
}, E_ERROR);

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