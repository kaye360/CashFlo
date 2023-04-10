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
namespace lib\utils\GenericUtils;


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