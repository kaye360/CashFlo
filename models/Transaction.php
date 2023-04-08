<?php
/**
 * 
 * Transaction Model
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Stores database actions relating to the 'transactions' table
 * 
 */
declare(strict_types=1);
namespace models\TransactionModel;

use DateTimeImmutable;
use lib\Auth\Auth;
use lib\Database\Database;
use stdClass;

class TransactionModel {


    private string $table = 'transactions';

    /**
     * 
     * @method create the PDO object
     * 
     */
    public function __construct(protected Database $database)
    {
    }

    /**
     * 
     * @method create a new Transaction
     * 
     */
    public function create(object $data): object
    {
        $create_new_transaction = $this->database
            ->table('transactions')
            ->cols('name, budget, amount, type, date, user_id')
            ->values(" '$data->name', '$data->selected_budget', '$data->amount', '$data->type', '$data->date', '" . Auth::user_id() . "' ")
            ->new();

        if( !$create_new_transaction ) 
        {
            return (object) [
                'error' => true, 
                'data'  => null
            ];
        }

        return (object) [
            'error' => false,
            'data'  => $data,
        ];
    }

    /**
     * 
     * @method Edit a transaction
     * 
     */
    public function update(
        ?string $name,
        ?string $budget,
        ?float $amount,
        string $type,
        string $date,
        int $user_id,
        int $id
    ) : bool {

        return $this->database
            ->table('transactions')
            ->set("
                name = '$name', 
                budget = '$budget', 
                amount = '$amount', 
                type = '$type', 
                date = '$date', 
                user_id = '$user_id' 
            ")
            ->where("id = '$id' ")
            ->update();
    }

    /**
     * 
     * @method Get currently logged in users transactions.
     * 
     */
    public function get_all() : array | null
    {
        $transactions = $this->database
            ->table('transactions')
            ->select('*')
            ->where("user_id = '" . Auth::user_id() . "' ")
            ->order('date DESC, id DESC')
            ->list();
        
        foreach($transactions as $transaction)
        {
            $date = $transaction->date;
            $date = new DateTimeImmutable($date);
            $transaction->date_english = $date->format('M d Y');
        }

        return $transactions;
    }
    /**
     * 
     * @method Get a Transactions by name
     * 
     */
    public function get_single_budget_trend( string $budget ) : object
    {
        $user_id = Auth::user_id();

        $transactions = $this->database
            ->table('transactions')
            ->select('*')
            ->where("budget = '$budget' AND user_id = '$user_id' ")
            ->order('date DESC, id DESC')
            ->list();

        /**
         * Add a date formatted as english words to each transactions
         */
        foreach($transactions as $transaction)
        {
            $date = $transaction->date;
            $date = new DateTimeImmutable($date);

            $transaction->date_english = $date->format('M d Y');
        }

        /**
         * Chunk transactions by month in multidimensional array
         */
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

        /**
         * Create an array of net spending for each month
         * $monthly_net_totals array keys match $transactions_chunked_by_month
         */
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
    
                $monthly_net_total = 
                    array_reduce( $transactions_chunked_by_month[ $year_month ], function( $total, $current)
                {
                    if ( $current->type === 'income' )   $total = $total + $current->amount;
                    if ( $current->type === 'spending' ) $total = $total - $current->amount;
                    return $total;
                });

                $monthly_net_totals[ $year_month ] = number_format( $monthly_net_total, 2, '.', '' );
            }

            unset($transaction);
        }

        unset($month);

        /**
         * Return an object with transactions chunked by month and monthly net totals
         * Both will have matching array keys of month in the form YYYY-MM
         */
        $budget_trend = new stdClass();
        $budget_trend->transactions_chunked_by_month = $transactions_chunked_by_month;
        $budget_trend->monthly_net_totals = $monthly_net_totals;

        return $budget_trend;
    }

    /**
     * 
     * @method Get a Transaction
     * 
     */
    public function get(int $id) : object | false
    {
        return $this->database
            ->table('transactions')
            ->select('*')
            ->where("id = '" . $id . "' ")
            ->single();
    }

    /**
     * 
     * @method Destroy a transaction
     * 
     */
    public function destroy(int $id)
    {
        return $this->database
            ->table('transactions')
            ->where("id = '$id' ")
            ->destroy();
    }
}