<?php
/**
 * 
 * Transaction Serive class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for more complex logic in Transactions controller
 * 
 */
declare(strict_types=1);
namespace services\TransactionService;

use lib\Auth\Auth;
use lib\InputHandler\Sanitizer\Sanitizer;
use lib\InputHandler\Validator\Validator;
use lib\Redirect\Redirect\Redirect;
use lib\Router\Route\Route;
use lib\types\Transaction\Transaction;
use lib\utils\Prompt\Prompt;
use stdClass;

class TransactionService {

    /**
     * 
     * @method Gets the current page
     * 
     */
    public static function get_current_page_param() : int
    {
        $page = isset( Route::params()->page )
            ? (int) Route::params()->page
            : 1;
            
        if( $page <= 0)
        {
            Redirect::to('/transactions/1')->redirect();
        }

        return $page;
    }

    /**
     * 
     * @method Validate a transaction
     * 
     * @var transaction_id The ID of the transaction if it has one yet
     * Should be null if creating a new transaction
     * Should be Route::params()->id if updating an existing
     * 
     */
    public static function validate_transaction( string $transaction_id = null, object $input_names = null) : stdClass
    {
        if( !$input_names )
        { 
            $input_names = (object) [
                'name'    => 'name',
                'amount'  => 'amount',
                'type'    => 'type',
                'budgets' => 'budgets',
                'date'    => 'date'
            ];
        }

        $validation = Validator::validate([
            $input_names->name    => ['required', 'max:20' , 'has_spaces'],
            $input_names->amount  => ['required', 'number'],
            $input_names->type    => ['required'],
            $input_names->budgets => ['required', 'has_spaces'],
            $input_names->date    => ['required', 'date']
        ]);

        $amount  = (float) Sanitizer::sanitize($_POST[$input_names->amount]);
        $amount  = (float) number_format( $amount, 2, '.', '' );

        $transaction = new Transaction(
            id:      (int) $transaction_id,
            user_id: (int) Auth::user_id(),
            name:          Sanitizer::sanitize($_POST[$input_names->name]),
            budget:        Sanitizer::sanitize($_POST[$input_names->budgets]),
            type:          Sanitizer::sanitize($_POST[$input_names->type]),
            date:          Sanitizer::sanitize($_POST[$input_names->date]),
            amount:        $amount
        );

        $new_transaction              = new stdClass();
        $new_transaction->validation  = $validation;
        $new_transaction->transaction = $transaction;

        return $new_transaction;
    }

    /**
     * 
     * @method Validate multiple Transactions
     * 
     */
    public static function validate_multiple() : array
    {
        $transactions = [];

        for($i = 1; $i <= 10; $i++ )
        {
            $input_names = (object) [
                'name'    => 'name-'   . $i,
                'amount'  => 'amount-'  . $i,
                'type'    => 'type-'    . $i,
                'budgets' => 'budgets-' . $i,
                'date'    => 'date-'    . $i
            ];

            // Validate only if amount is not 0, otherwise ignore
            if( empty( (int) $_POST[ $input_names->amount]) ) 
            {
                $transactions[$i] = null;
            } else {
                $transactions[$i] = TransactionService::validate_transaction(null, $input_names);
            }
        }

        return $transactions;
    }

    /**
     * 
     * @method Extract errors from multiple validated Transactions
     * 
     */
    public static function extract_errors_from_multiple( array $transactions ) : array
    {
        $errors = [];

        foreach ($transactions as $transaction)
        {
            $errors[] = $transaction ? $transaction->validation : null;
        }

        // Start array with index 1
        array_unshift($errors, "");
        unset($errors[0]);

        return $errors;
    }

    /**
     * 
     * @method Check if errors on multiple transactions
     * 
     */
    public static function multiple_has_no_errors( array $transactions ) : bool
    {
        return empty(
            array_filter( $transactions, function($transaction)
            {
                if( $transaction )
                {
                    return $transaction->success === false;
                }
            })
        );

    }

    /**
     * 
     * @method Store multiple transactions and redirect
     * 
     */
    public static function store_multiple_transactions( object $model, array $transactions ) : void
    {
        foreach( $transactions as $transaction )
        {
            if( !$transaction ) continue;
            $model->create( $transaction->transaction );
        }
        Redirect::to('/transactions')->prompt('success', 'Transactions added successfully')->redirect();
    }

    /**
     * 
     * @method Create a transaction and redirect
     * 
     */
    public static function store_transaction( object $model, Transaction $transaction ) : void
    {
        $model->create( $transaction );
        Redirect::to('/transactions')->prompt('success', 'Transaction added successfully')->redirect();
    }

    /**
     * 
     * @method Redirect to last page if current page is too high
     * 
     */
    public static function redirect_if_page_exceeds_total( int $current, int $total ) : void
    {
        if($total <= 1) return; // This prevents an infinite loop if self::get_current_page_param = 0 or 1

        if( $current > $total )
        {
            Redirect::to("/transactions/$total")->redirect();
        }
    }

}