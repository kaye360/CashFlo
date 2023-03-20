<?php
/**
 * 
 * Generic Utility Functions
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */
namespace utils\general;


class GenericUtils {

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

}