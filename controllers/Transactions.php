<?php
/**
 * 
 * Transactions Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to control routes related to Transactions
 * 
 */
declare(strict_types=1);
namespace controllers\TransactionsController;

use lib\Auth\Auth;
use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use lib\Router\Route\Route;
use lib\services\Transaction\Transaction;
use stdClass;



class TransactionsController extends Controller {

    private $transactionsModel;
    

    public function __construct()
    {
        $this->transactionsModel = $this->model('Transaction');
    }

    /**
     * 
     * @method Transaction Home page
     * 
     */
    public function index() : void
    {
        $budgetsModel      = $this->model('Budget');

        $data                  =         new stdClass();
        $data->transactions    = (array) $this->transactionsModel->get_all();
        $data->budgets         =         $budgetsModel->get_all( Auth::user_id() );
        $data->prompt          =         $_GET['prompt'] ?? false;
        $data->date            =         date('Y-m-d');
        $data->selected_budget = '';

        $this->view('transactions/index', $data);
    }
    
    /**
     * 
     * @method Edit Transaction Form
     * 
     */
    public function edit() : void
    {
        $transaction  = $this->transactionsModel->get(id: (int) Route::params()->id );
        $budgetsModel = $this->model('Budget');

        Auth::authorize($transaction?->user_id);

        $data              = new stdClass();
        $data->transaction = $transaction;
        $data->referer     = parse_url( $_SERVER['HTTP_REFERER'] ?? '/transactions' , PHP_URL_PATH);
        $data->budgets     = $budgetsModel->get_all( Auth::user_id() );

        $this->view('transactions/edit', $data);
    }

    /**
     * 
     * @method Create a Transaction
     * 
     */
    public function create() : void
    {
        $validator = InputHandler::validate([
            'name'    => ['required', 'max:20' , 'has_spaces'],
            'amount'  => ['required', 'number'],
            'budgets' => ['required', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);
        
        $budgetsModel = $this->model('Budget');

        $amount  = (float) InputHandler::sanitize($_POST['amount']);
        $amount  = (float) number_format( $amount, 2, '.', '' );

        $transaction = new Transaction(
            id:      null,
            name:    InputHandler::sanitize($_POST['name']),
            budget:  InputHandler::sanitize($_POST['budgets']),
            type:    InputHandler::sanitize($_POST['type']),
            date:    InputHandler::sanitize($_POST['date']),
            amount:  $amount,
            user_id: Auth::user_id()
        );

        $data              = new stdClass();
        $data->transaction = $transaction;
        $data->budgets     = $budgetsModel->get_all( Auth::user_id() );
        $data->errors      = $validator->errors;
        $data->success     = $validator->success;
        
        if( $data->success ) 
        {
            $new_transaction = $this->transactionsModel->create( $transaction );
            
            if( $new_transaction->error ) 
            {
                $data->success       = false;
                $data->errors->query = true;

            } else {
                header('Location: /transactions?prompt=add_transaction');
                die();
            }
        }
        
        $data->transactions = $this->transactionsModel->get_all();
        
        $this->view('transactions/index', $data);
    }

    /**
     * 
     * @method Edit a Transaction
     * 
     */
    public function update()
    {
        // Authorize Edit Transaction
        $db_transaction = $this->transactionsModel->get( id: (int) Route::params()->id );
        Auth::authorize($db_transaction->user_id);

        $validator = InputHandler::validate([
            'name'    => ['required', 'max:20', 'has_spaces'],
            'amount'  => ['required', 'number'],
            'type'    => ['required'],
            'budgets' => ['required', 'max:20', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);

        $transaction = new Transaction(
            id:      (int)   Route::params()->id,
            name:            InputHandler::sanitize( $_POST['name'] ),
            budget:          InputHandler::sanitize( $_POST['budgets'] ),
            type:            InputHandler::sanitize( $_POST['type'] ),
            date:            InputHandler::sanitize( $_POST['date'] ),
            user_id: (int)   Auth::user_id(),
            amount:  (float) InputHandler::sanitize( $_POST['amount'] )
        );

        $data              = new stdClass();
        $data->transaction = $transaction;
        $data->budgets     = ($this->model('Budget'))->get_all( (int) Auth::user_id() );
        $data->referer     = InputHandler::sanitize( $_POST['referer'] );
        $data->errors      = $validator->errors;
        $data->success     = $validator->success;

        if ( $data->success )
        {
            $this->transactionsModel->update( $transaction );
        }

        $this->view('transactions/edit', $data);
    }

    /**
     * 
     * @method Edit a transaction
     * 
     */
    public function destroy()
    {
        // Authorize Delete Transaction
        $transaction = $this->transactionsModel->get(id: (int) Route::params()->id );
        Auth::authorize($transaction->user_id);

        $referer        = $_POST['referer'] ?: '/transactions';
        $referer        = parse_url($referer, PHP_URL_PATH);

        // Delete Transaction
        $this->transactionsModel->destroy(id: $transaction->id);

        // Return to referer
        header("Location: $referer?prompt=delete_transaction ");
        die();
    }

}
