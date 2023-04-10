<?php
/**
 * 
 * Generic Utility Functions
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
declare(strict_types=1);
namespace lib\utils\Helpers;

use Exception;
use InvalidArgumentException;
use ReflectionClass;
use stdClass;

class Helpers {

    /**
     * 
     * @method generate a Unique Identifier
     * 
     */
    public static function make_UUID()
	{
		// Credit Here: https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
	}

    /**
     * 
     * @method Create a new Service Class from and object
     * 
     * This method simplifies copying a DB query to a Service Class
     * The object must have the required keys as args to the Service class
     */
    public static function service_class_from_obj( string $service_class_name,  object $obj )
    {
        if( !class_exists($service_class_name))
        {
            throw new InvalidArgumentException( $service_class_name . 'Doesn\'t exist');
        }

        $service_class_params = ( new ReflectionClass($service_class_name) )
            ->getConstructor()
            ->getParameters();

        foreach( $service_class_params as $param)
        {
            if( !property_exists( $obj, $param->name ) )
            {
                throw new InvalidArgumentException('Missing property ' . $param->name . ' in class service_class_from_obj: arg #2');
            }
        }

        return new $service_class_name(... (array) $obj );
    }
     

    /**
     * 
     * @method Render Exception
     * 
     */
    public static function render_exception($e)
    {
        echo <<<EOT
            <div class="p-4 max-w-3xl m-2 border border-gray-300 bg-gray-50 rounded">
                Code: {$e->getCode()} <br>
                Msg:  {$e->getMessage()} <br>
                File: {$e->getFile()} <br>
                Line: {$e->getLine()}
            </div>
        EOT;
    }

}