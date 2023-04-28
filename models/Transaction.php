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

use lib\Auth\Auth;
use lib\Database\Database;
use lib\types\Transaction\Transaction;
use lib\utils\Helpers\Helpers;
use stdClass;



class TransactionModel {


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
    public function create( Transaction $transaction ) : object
    {
        $create_new_transaction = $this->database
            ->table('transactions')
            ->cols('name, budget, amount, type, date, user_id')
            ->values(" '$transaction->name', '$transaction->budget', '$transaction->amount', '$transaction->type', '$transaction->date', '" . Auth::user_id() . "' ")
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
            'data'  => $transaction,
        ];
    }

    /**
     * 
     * @method Edit a transaction
     * 
     */
    public function update( Transaction $transaction ) : bool {

        return $this->database
            ->table('transactions')
            ->set("
                name    = '$transaction->name', 
                budget  = '$transaction->budget', 
                amount  = '$transaction->amount', 
                type    = '$transaction->type', 
                date    = '$transaction->date', 
                user_id = '$transaction->user_id' 
            ")
            ->where("id = '$transaction->id' ")
            ->update();
    }

    /**
     * 
     * @method Get currently logged in users transactions.
     * 
     */
    public function get_all( int $page = 1, int $per_page = 10 ) : object
    {
        $start = ($page - 1) * $per_page;

        $list = $this->database
            ->table('transactions')
            ->select('*')
            ->where("user_id = '" . Auth::user_id() . "' ")
            ->limit(" $start, $per_page ")
            ->order('date DESC, id DESC')
            ->list( Transaction::class );
            
        $count = $this->database
            ->table('transactions')
            ->select('*')
            ->where("user_id = '" . Auth::user_id() . "' ")
            ->count();

        $transactions              = new stdClass();
        $transactions->list        = $list;
        $transactions->total_pages = (int) ceil( $count / $per_page );

        return $transactions;
    }

    /**
     * 
     * @method Get a Transactions by name
     * 
     */
    public function get_monthly_transaction_trend( ?string $budget = null ) : object
    {
        $user_id = Auth::user_id();

        $where = $budget 
            ? "budget = '$budget' AND user_id = '$user_id' "
            : "user_id = '$user_id' ";

        $transactions = $this->database
            ->table('transactions')
            ->select('*')
            ->where($where)
            ->order('date DESC, id DESC')
            ->list( Transaction::class );

        $budget_trend                                = new stdClass();
        $budget_trend->transactions_chunked_by_month = Helpers::chunk_transactions_by_month( $transactions );

        $budget_trend->monthly_net_totals            = Helpers::calc_monthly_net_totals(                                
            $budget_trend->transactions_chunked_by_month 
        );

        $budget_trend->monthly_net_spending          = Helpers::calc_monthly_net_totals( 
            $budget_trend->transactions_chunked_by_month, 'spending' 
        );

        $budget_trend->monthly_net_income          = Helpers::calc_monthly_net_totals( 
            $budget_trend->transactions_chunked_by_month, 'income' 
        );

        return $budget_trend;
    }

    /**
     * 
     * @method Get a Transaction
     * 
     */
    public function get(int $id) : ?Transaction
    {
        $transaction = $this->database
            ->table('transactions')
            ->select('*')
            ->where("id = '" . $id . "' ")
            ->single();

        return $transaction 
            ? new Transaction(... (array) $transaction)
            : null;
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