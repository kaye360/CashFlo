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
     * @method generate budget trend bar graph
     * 
     */
    public static function budget_trend_bar_graph( array $net_totals, int $max_height = 100 )
    {
        if( empty($net_totals) ) return;

        $monthly_max     = (float) max( $net_totals );
        $monthly_max_abs = abs($monthly_max);
        $monthly_min     = (float) min( $net_totals );
        $monthly_min_abs = abs($monthly_min);

        $monthly_largest_abs = max ($monthly_max_abs, $monthly_min_abs );
        $monthly_ratio       = $max_height / $monthly_largest_abs;        

        return $monthly_ratio;
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