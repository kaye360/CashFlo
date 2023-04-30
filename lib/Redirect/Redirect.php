<?php
/**
 * 
 * Redirect Class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Allows easier redirecting with prompts
 * 
 */
declare(strict_types=1);
namespace lib\Redirect\Redirect;

use lib\utils\Prompt\Prompt;

class Redirect {

    private static $to;

    /**
     * 
     * @method Set the redirect location (Required)
     * 
     */
    public static function to( string $location )
    {
        static::$to = $location;
        return new static;
    }

    /**
     * 
     * @method Set a prompt (Optional)
     * 
     */
    public static function prompt( string $type, string $message )
    {
        Prompt::set($type, $message);
        return new static;
    }

    /**
     * 
     * @method Redirect action
     * 
     */
    public static function redirect()
    {
        header( 'Location: ' . static::$to );
        die();
    }

}