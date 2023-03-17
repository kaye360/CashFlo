<?php
/**
 * 
 * App Boostrap file for autoloading classes and config file.
 * 
 * Note:
 * The string 'Controller' is removed from namespaced class name,
 * but also allows the file to be called Controller.php
 * 
 * Example:
 * Controller class name with be UsersController, but we only 
 * want to include Users.php, while also allowing for the posssilbilty
 * of a file name Controller.php
 * 
 */

spl_autoload_register(function($class)
{
    $class_array = explode('\\', $class);
     
    if( $class_array[1] !== 'Controller') {
        $class_array[1] = str_replace('Controller' , '', $class_array[1]);
    }

    $class_file_path = './' . $class_array[0] . '/' . $class_array[1] . '.php';

    require_once $class_file_path;
});

require_once './config.php';