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
     * @method generate Vertical budget trend bar graph ratio
     * 
     * @var net_totals is an array of positive and negative money amounts
     * 
     * @var high_limit is the maximum value of a graph. 
     * Can be used in the UI as a % or px value, for example
     * 
     * @return ratio number to multiply each amount by to fit within a graph
     * 
     */
    public static function calc_graph_ratio( array $net_totals, int $high_limit = 100 )
    {
        if( empty($net_totals) ) return;

        $max     = (float) max( $net_totals );
        $max_abs = abs($max);
        $min     = (float) min( $net_totals );
        $min_abs = abs($min);

        $largest_abs = max ($max_abs, $min_abs );
        $ratio       = $high_limit / $largest_abs;        

        return $ratio;
    }

    /**
     * 
     * @method Calculate monthly net totals
     * 
     * Create an array of net spending for each month
     * $monthly_net_totals array keys match $transactions_chunked_by_month
     * 
     */
    public static function calc_monthly_net_totals( array $transactions_chunked_by_month, string $type = 'net' ) : array
    {
        /**
         * /@var monthly_net_totals Container for totals
         * [ key '2023-04' => value '345.34']
         */
        $monthly_net_totals = [];
        
        // Loop through months
        foreach ( $transactions_chunked_by_month as $month )
        {
            // Loop thru transactions within a month
            foreach ( $month as $transaction)
            {
                $year_month = substr($transaction->date, 0, -3);
    
                // Create an array key in container if none exists yet
                if ( !array_key_exists( $year_month, $monthly_net_totals) )
                {
                    $monthly_net_totals[ $year_month ] = [];
                }
    
                $month_net_total = self::calc_net_total( 
                    transaction_list: $transactions_chunked_by_month[ $year_month ], 
                    type: $type 
                );

                $monthly_net_totals[ $year_month ] = number_format( $month_net_total ?: 0, 2, '.', '' );
            }

            unset($transaction);
        }

        unset($month);

        return $monthly_net_totals;
    }

    /**
     * 
     * @method Calculate net (total, income, spending) for a list of transactions
     * 
     */
    public static function calc_net_total( array $transaction_list, string $type ) : ?float
    {
        if( !in_array( $type, ['net', 'spending', 'income'] ) ) 
        {
            throw new InvalidArgumentException("\$type must be net, income, or spending only. $type given" );
        }

        return array_reduce( 
            $transaction_list, 
            function( $total, $current) use($type)
            {
                if ( 
                    ( $type === 'net' || $type === 'income' ) &&
                    ( $current->type === 'income' )
                    )   {
                        $total += $current->amount;
                    }
                    
                if ( 
                    ( $type === 'net' || $type === 'spending') &&
                    ( $current->type === 'spending' )
                ) {
                    $total -= $current->amount;
                }

                return $total;
            }
        );
    }

    /**
     * 
     * @method Chunk transactions by month
     * 
     */
    public static function chunk_transactions_by_month( array $transactions ) : array
    {
        $transactions_chunked_by_month = [];

        foreach ( $transactions as $transaction )
        {
            $year_month = substr($transaction->date, 0, -3);

            if ( !array_key_exists( $year_month, $transactions_chunked_by_month ) )
            {
                $transactions_chunked_by_month[ $year_month ] = [];
            }

            array_push( $transactions_chunked_by_month[ $year_month ], $transaction );
        }

        unset($transaction);
        
        return $transactions_chunked_by_month;
    }

}