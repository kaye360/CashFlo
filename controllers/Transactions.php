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
use lib\Redirect\Redirect\Redirect;
use lib\Router\Route\Route;
use lib\types\Transaction\Transaction;
use lib\utils\Prompt\Prompt;
use services\TransactionService\TransactionService;
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
        $budgetsModel = $this->model('Budget');

        $page = TransactionService::get_current_page_param();

        $transactions = $this->transactionsModel->get_all( 
            page: $page, 
            per_page: (int) Auth::settings()->transactions_per_page 
        );

        TransactionService::redirect_if_page_exceeds_total($page, $transactions->total_pages );

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
     * @method Store a Transaction
     * 
     */
    public function store() : void
    {
        $budgetsModel = $this->model('Budget');

        $page = TransactionService::get_current_page_param();

        $transactions = $this->transactionsModel->get_all( 
            page: $page, 
            per_page: (int) Auth::settings()->transactions_per_page 
        );

        $transaction = TransactionService::validate_transaction();

        $data               = new stdClass();
        $data->page         = $page;
        $data->transactions = $transactions->list;
        $data->total_pages  = $transactions->total_pages;
        $data->transaction  = $transaction->transaction;
        $data->budgets      = $budgetsModel->get_all( Auth::user_id() );
        $data->errors       = $transaction->validation->errors;
        $data->success      = $transaction->validation->success;
        
        if( $data->success ) 
        {
            TransactionService::store_transaction($this->transactionsModel, $transaction->transaction);
        } else {

            Prompt::set('error', 'Transaction not created. Please check form for errors');
        }
                
        $this->view('transactions/index', $data);
    }

    /**
     * 
     * @method Create many transactions
     * 
     */
    public function create_many() : void
    {
        $budgetsModel = $this->model('Budget');

        $data               = new stdClass;
        $data->budgets      = $budgetsModel->get_all( Auth::user_id() );
        $data->transactions = [];

        // Set default input values
        for($i = 1; $i <= 10; $i++)
        {
            $data->transactions[$i]                    = new stdClass;
            $data->transactions[$i]->transaction       = new stdClass;
            $data->transactions[$i]->transaction->type = 'spending';
            $data->transactions[$i]->transaction->date = date('Y-m-d');
        }

        $this->view('transactions/create-many', $data);
    }

    public function store_many() : void
    {
        $budgetsModel = $this->model('Budget');

        $data               = new stdClass;
        $data->budgets      = $budgetsModel->get_all( Auth::user_id() );
        $data->transactions = TransactionService::validate_multiple();
        $data->errors       = TransactionService::extract_errors_from_multiple( $data->transactions );
        $data->success      = TransactionService::multiple_has_no_errors( $data->errors );

        if( $data->success )
        {
            TransactionService::store_multiple_transactions( $this->transactionsModel, $data->transactions );

        } else {
            Prompt::set('error', 'Transactions not saved. Please check for errors');
        }

        $this->view('transactions/create-many', $data);
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
     * @method Edit a Transaction
     * 
     */
    public function update()
    {
        // Authorize Edit Transaction
        $current_transaction = $this->transactionsModel->get( id: (int) Route::params()->id );
        Auth::authorize($current_transaction->user_id);

        $transaction = TransactionService::validate_transaction( transaction_id: Route::params()->id );

        $data              = new stdClass();
        $data->transaction = $transaction->transaction;
        $data->budgets     = ($this->model('Budget'))->get_all( (int) Auth::user_id() );
        $data->referer     = Sanitizer::sanitize( $_POST['referer'] );
        $data->errors      = $transaction->validation->errors;
        $data->success     = $transaction->validation->success;

        if ( $data->success )
        {
            Prompt::set('success', 'Transaction updated successfully');
            
            $this->transactionsModel->update( $transaction->transaction );

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
        Redirect::to( $referer )->prompt('success', 'Transaction deleted')->redirect();
    }

}
