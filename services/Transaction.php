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
    public static function validate_transaction( string $transaction_id = null ) : stdClass
    {
        $validation = Validator::validate([
            'name'    => ['required', 'max:20' , 'has_spaces'],
            'amount'  => ['required', 'number'],
            'type'    => ['required'],
            'budgets' => ['required', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);

        $amount  = (float) Sanitizer::sanitize($_POST['amount']);
        $amount  = (float) number_format( $amount, 2, '.', '' );

        $transaction = new Transaction(
            id:      (int) $transaction_id,
            user_id: (int) Auth::user_id(),
            name:          Sanitizer::sanitize($_POST['name']),
            budget:        Sanitizer::sanitize($_POST['budgets']),
            type:          Sanitizer::sanitize($_POST['type']),
            date:          Sanitizer::sanitize($_POST['date']),
            amount:        $amount
        );

        $new_transaction              = new stdClass();
        $new_transaction->validation  = $validation;
        $new_transaction->transaction = $transaction;

        return $new_transaction;
    }

    /**
     * 
     * @method Create a transaction and redirect
     * 
     */
    public static function create_transaction( object $model, Transaction $transaction ) : void
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