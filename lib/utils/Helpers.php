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
     * @method Calculate monthly net totals
     * 
     * Create an array of net spending for each month
     * $monthly_net_totals array keys match $transactions_chunked_by_month
     * 
     */
    public static function calc_monthly_net_totals( array $transactions_chunked_by_month, string $type = 'net' ) : array
    {
        $monthly_net_totals = [];
        
        foreach ( $transactions_chunked_by_month as $month )
        {
            foreach ( $month as $transaction)
            {
                $year_month = substr($transaction->date, 0, -3);
    
                if ( !array_key_exists( $year_month, $monthly_net_totals) )
                {
                    $monthly_net_totals[ $year_month ] = [];
                }
    
                $monthly_net_total = array_reduce( 
                    $transactions_chunked_by_month[ $year_month ], 
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

                $monthly_net_totals[ $year_month ] = number_format( $monthly_net_total ?: 0, 2, '.', '' );
            }

            unset($transaction);
        }

        unset($month);

        return $monthly_net_totals;
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