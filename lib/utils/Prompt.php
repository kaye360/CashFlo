<?php
/**
 * 
 * User Prompt Functions
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
declare(strict_types=1);
namespace lib\utils\Prompt;

use stdClass;

class Prompt {

    /**
     * 
     * @method Set a prompt
     * 
     */
    public static function set(string $type, string $message) :void
    {
        $prompt = new stdClass();
        $prompt->type = $type;
        $prompt->message = $message;

        $_SESSION['prompt'] = json_encode( $prompt );
    }

    /**
     * 
     * @method Get a prompt type
     * 
     */
    public static function get_type() : ?string
    {
        if ( isset( $_SESSION['prompt'] ) )
        {
            $prompt = json_decode( $_SESSION['prompt'] );
            return $prompt->type;

        } else {
            
            return null;
        }
    }

    /**
     * 
     * @method Get a prompt message
     * 
     */
    public static function get_message() : ?string
    {
        if ( isset( $_SESSION['prompt'] ) )
        {
            $prompt = json_decode( $_SESSION['prompt'] );
            return $prompt->message;

        } else {
            
            return null;
        }
    }

    /**
     * 
     * @method Is a prompt set
     * 
     */
    public static function is_set() : bool
    {
        return isset( $_SESSION['prompt'] );
    }

}