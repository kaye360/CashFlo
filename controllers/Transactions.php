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

use DateTimeImmutable;
use lib\Auth\Auth;
use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use lib\Router\Route\Route;
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

        $data               =         new stdClass();
        $data->transactions = (array) $this->transactionsModel->get_all();
        $data->budgets      =         $budgetsModel->get_all( Auth::user_id() );
        $data->prompt       =         $_GET['prompt'] ?? false;
        $data->date         =         date('Y-m-d');

        // q($data->budgets);
        $this->view('transactions/index', $data);
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
        
        $budgetsModel      = $this->model('Budget');

        $data                  = new stdClass();
        $data->name            = InputHandler::sanitize($_POST['name']);
        $data->selected_budget = InputHandler::sanitize($_POST['budgets']);
        $data->budgets         = $budgetsModel->get_all( Auth::user_id() );
        $data->amount          = InputHandler::sanitize($_POST['amount']);
        $data->amount          = number_format( (float) $data->amount, 2, '.', '' );
        $data->type            = InputHandler::sanitize($_POST['type']);
        $data->date            = InputHandler::sanitize($_POST['date']);
        $data->errors          = $validator->errors;
        $data->success         = $validator->success;
        
        if( $data->success ) 
        {
            $new_transaction = $this->transactionsModel->create($data);
            
            if( $new_transaction->error ) 
            {
                $data->success       = false;
                $data->errors->query = true;

            } else {
                $data->success = true;
                $data->name    = '';
                $data->amount  = '';
                $data->date    = date('Y-m-d');
            }
        }
        
        $data->transactions = $this->transactionsModel->get_all();
        
        $this->view('transactions/index', $data);
    }

    /**
     * 
     * @method Edit Transaction Form
     * 
     */
    public function edit() : void
    {
        $id = (int) Route::params()->id;
        
        // Authorize Edit Transaction
        $user = $this->transactionsModel->get(id: $id);
        Auth::authorize($user->user_id ?? 0);

        $budgetsModel  = $this->model('Budget');

        $transaction   = $this->transactionsModel->get(id: $id);
        $data          = new stdClass();
        $data->id      = $id;
        $data->referer = parse_url( $_SERVER['HTTP_REFERER'] ?? '/transactions' , PHP_URL_PATH);
        $data->name    = $transaction->name;
        $data->budgets = $budgetsModel->get_all( Auth::user_id() );
        $data->budget  = $transaction->budget;
        $data->date    = $transaction->date;
        $data->amount  = $transaction->amount;
        $data->type    = $transaction->type;

        $this->view('transactions/edit', $data);
    }

    /**
     * 
     * @method Edit a Transaction
     * 
     */
    public function update()
    {
        $validator = InputHandler::validate([
            'name'    => ['required', 'max:20', 'has_spaces'],
            'amount'  => ['required', 'number'],
            'type'    => ['required'],
            'budgets' => ['required', 'max:20', 'has_spaces'],
            'date'    => ['required', 'date']
        ]);

        $budgetsModel  = $this->model('Budget');

        $data          =         new stdClass();
        $data->name    =         InputHandler::sanitize( $_POST['name'] );
        $data->amount  = (float) InputHandler::sanitize( $_POST['amount'] );
        $data->type    =         InputHandler::sanitize( $_POST['type'] );
        $data->budget  =         InputHandler::sanitize( $_POST['budgets'] );
        $data->budgets =         $budgetsModel->get_all( Auth::user_id() );
        $data->date    =         InputHandler::sanitize( $_POST['date'] );
        $data->referer =         InputHandler::sanitize( $_POST['referer'] );
        $data->user_id =         Auth::user_id();
        $data->id      = (int)   Route::params()->id;
        $data->errors  =         $validator->errors;
        $data->success =         $validator->success;

        // Authorize Edit Transaction
        $user = $this->transactionsModel->get(id: $data->id);
        Auth::authorize($user->user_id);

        if ( $data->success )
        {
            // Edit Transaction
            $this->transactionsModel->update(
                name:    $data->name,
                budget:  $data->budget,
                amount:  $data->amount,
                type:    $data->type,
                date:    $data->date,
                id:      $data->id,
                user_id: $data->user_id
            );
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
        $data = new stdClass();
        
        if( empty($_POST['referer']) || empty($_POST['id']) )
        {
            $this->view('transactions/edit', $data);
            return;
        }

        $transaction_id = (int) $_POST['id'];
        $referer        = $_POST['referer'];
        $referer        = parse_url($referer, PHP_URL_PATH);

        // Authorize Delete Transaction
        $user = $this->transactionsModel->get(id: $transaction_id);
        Auth::authorize($user->user_id);

        // Delete Transaction
        $this->transactionsModel->destroy(id: $transaction_id);

        // Return to referer
        header("Location: $referer?prompt=delete_transaction ");
        die();
    }

}
