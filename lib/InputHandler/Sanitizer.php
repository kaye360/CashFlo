<?php
/**
 * 
 *  User Input Sanitizing Class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
declare(strict_types=1);
namespace lib\InputHandler\Sanitizer;




class Sanitizer {

    /**
     * 
     * @method Sanitize a variable and return new value.
     * 
     * 
     */
    public static function sanitize(string $input) : ?string
    {
        if( empty($input)  ) return null;

        $sanitized_input = trim($input);
        $sanitized_input = htmlspecialchars($input);
        return $sanitized_input;
    }

    /**
     * 
     * @method Format an input to CAD 
     * 
     */
    public static function money(string $input) : string
    {
        if( !is_numeric($input) ) return 0;

        $input = number_format( (float) $input, 2, '.', '' );

        return $input;
    }

}