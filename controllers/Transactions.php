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
use lib\InputHandler\Sanitizer\Sanitizer;
use lib\InputHandler\Validator\Validator;
use lib\Router\Route\Route;
use lib\types\Transaction\Transaction;
use lib\utils\Prompt\Prompt;
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

        $page = isset( Route::params()->page )
            ? (int) Route::params()->page
            : 1;

        if( $page <= 0)
        {
            header('Location: /transactions/1');
            die();
        }

        $transactions = $this->transactionsModel->get_all( 
            page: $page, 
            per_page: (int) Auth::settings()->transactions_per_page 
        );

        if( $page > $transactions->total_pages )
        {
            header('Location: /transactions/' . $transactions->total_pages );
            die();
        }

        $data                  = new stdClass();
        $data->page            = $page;
        $data->transactions    = $transactions->list;
        $data->total_pages     = $transactions->total_pages;
        $data->budgets         = $budgetsModel->get_all( Auth::user_id() );
        $data->prompt          = $_GET['prompt'] ?? false;
        $data->date            = date('Y-m-d');
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
        $validator = Validator::validate([
            'name'    => ['required', 'max:20' , 'has_spaces'],
            'amount'  => ['required', 'number'],
            'budgets' => ['required', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);
        
        $budgetsModel = $this->model('Budget');

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

        $page = isset( Route::params()->page )
            ? (int) Route::params()->page
            : 1;

        if( $page <= 0)
        {
            header('Location: /transactions/1');
            die();
        }

        $transactions = $this->transactionsModel->get_all( 
            page: $page, 
            per_page: (int) Auth::settings()->transactions_per_page 
        );

        $data              = new stdClass();
        $data->page            = $page;
        $data->transactions    = $transactions->list;
        $data->total_pages     = $transactions->total_pages;
        $data->transaction = $transaction;
        $data->budgets     = $budgetsModel->get_all( Auth::user_id() );
        $data->errors      = $validator->errors;
        $data->success     = $validator->success;
        
        if( $data->success ) 
        {
            $this->transactionsModel->create( $transaction );
            
            Prompt::set('success', 'Transaction added successfully');

            header("Location: /transactions");
            die();
        }
                
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

        $validator = Validator::validate([
            'name'    => ['required', 'max:20', 'has_spaces'],
            'amount'  => ['required', 'number'],
            'type'    => ['required'],
            'budgets' => ['required', 'max:20', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);

        $transaction = new Transaction(
            id:      (int)   Route::params()->id,
            name:            Sanitizer::sanitize( $_POST['name'] ),
            budget:          Sanitizer::sanitize( $_POST['budgets'] ),
            type:            Sanitizer::sanitize( $_POST['type'] ),
            date:            Sanitizer::sanitize( $_POST['date'] ),
            user_id: (int)   Auth::user_id(),
            amount:  (float) Sanitizer::sanitize( $_POST['amount'] )
        );

        $data              = new stdClass();
        $data->transaction = $transaction;
        $data->budgets     = ($this->model('Budget'))->get_all( (int) Auth::user_id() );
        $data->referer     = Sanitizer::sanitize( $_POST['referer'] );
        $data->errors      = $validator->errors;
        $data->success     = $validator->success;

        if ( $data->success )
        {
            Prompt::set('success', 'Transaction updated successfully');
            
            $this->transactionsModel->update( $transaction );
        } else {
            
            Prompt::set('error', 'Transaction not updated. Please check your form fields.');
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

        $referer = $_POST['referer'] ?: '/transactions';
        $referer = parse_url($referer, PHP_URL_PATH);

        // Delete Transaction
        $this->transactionsModel->destroy(id: $transaction->id);

        // Return to referer
        header("Location: $referer");
        die();
    }

}
