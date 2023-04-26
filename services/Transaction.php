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
    public static function get_current_page()
    {
        $page = isset( Route::params()->page )
            ? (int) Route::params()->page
            : 1;

        if( $page <= 0)
        {
            header('Location: /transactions/1');
            die();
        }

        return $page;
    }

    /**
     * 
     * @method Create a transaction
     * 
     */
    public static function prep_transaction()
    {
        $validation = Validator::validate([
            'name'    => ['required', 'max:20' , 'has_spaces'],
            'amount'  => ['required', 'number'],
            'budgets' => ['required', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);

        $amount  = (float) Sanitizer::sanitize($_POST['amount']);
        $amount  = (float) number_format( $amount, 2, '.', '' );

        $transaction = new Transaction(
            id:      null,
            name:    Sanitizer::sanitize($_POST['name']),
            budget:  Sanitizer::sanitize($_POST['budgets']),
            type:    Sanitizer::sanitize($_POST['type']),
            date:    Sanitizer::sanitize($_POST['date']),
            amount:  $amount,
            user_id: Auth::user_id()
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
    public static function create_transaction( $model, Transaction $transaction )
    {
        $model->create( $transaction );
            
        Prompt::set('success', 'Transaction added successfully');

        header("Location: /transactions");
        die();
    }

    /**
     * 
     * @method Redirect to last page if current page is too high
     * 
     */
    public static function check_page_exceeds_total( int $current, int $total )
    {
        if( $current > $total )
        {
            header('Location: /transactions/' . $total );
            die();
        }
    }

}