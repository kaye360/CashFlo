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

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use lib\Router\Route\Route;
use stdClass;



class TransactionsController extends Controller {

    private $transactionsModel;
    

    public function __construct(protected mixed $param = null)
    {
        $this->transactionsModel = $this->model('Transaction');
    }

    /**
     * 
     * @method Budget Home page
     * 
     */
    public function index() : void
    {
        $data                 =         new stdClass();
        $data->transactions   = (array) $this->transactionsModel->get_all();
        $data->prompt         =         $_GET['prompt'] ?? false;

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
            'name'   => ['required', 'max:20' , 'has_spaces'],
            'amount' => ['required', 'number'],
            'type'   => ['required']
        ]);

        $data          = new stdClass();
        $data->name    = InputHandler::sanitize($_POST['name']);
        $data->type    = InputHandler::sanitize($_POST['budget']);
        $data->amount  = InputHandler::sanitize($_POST['amount']);
        $data->amount  = InputHandler::date($_POST['date']);
        $data->amount  = InputHandler::money($_POST['amount']);
        $data->errors  = $validator->errors;
        $data->success = $validator->success;

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
            }
        }
        
        $data->transactions        = $this->transactionsModel->get_all();
        
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
        
        // Authorize Edit Budget
        $user = $this->transactionsModel->get(id: $id);
        AUTH->authorize($user->user_id ?? 0);

        $transaction   = $this->transactionsModel->get(id: $id);
        $data          = new stdClass();
        $data->id      = $id;
        $data->referer = parse_url( $_SERVER['HTTP_REFERER'] ?? '/transactions' , PHP_URL_PATH);
        $data->name    = $transaction->name;
        $data->budget  = $transaction->budget;
        $data->date    = $transaction->date;
        $data->amount  = $transaction->amount;

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
            'name'   => ['required', 'max:20', 'has_spaces'],
            'amount' => ['required', 'number'],
            'type'   => ['required']
        ]);

        $data          = new stdClass();
        $data->name    =         InputHandler::sanitize( $_POST['name'] );
        $data->amount  = (float) InputHandler::sanitize( $_POST['amount'] );
        $data->type    =         InputHandler::sanitize( $_POST['type'] );
        $data->id      = (int)   InputHandler::sanitize( $_POST['id'] );
        $data->referer =         InputHandler::sanitize( $_POST['referer'] );
        $data->errors  = $validator->errors;
        $data->success = $validator->success;

        // Authorize Edit Transaction
        $user = $this->transactionsModel->get(id: $data->id);
        AUTH->authorize($user->user_id);

        // Edit Budget`
        $this->transactionsModel->update(
            name:    $data->name,
            budget:  $data->budget,
            amount:  $data->amount,
            date:    $data->date,
            id:      $data->id,
            user_id: $data->user_id
        );

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

        // Authorize Delete Budget
        $user = $this->transactionsModel->get(id: $transaction_id);
        AUTH->authorize($user->user_id);

        // Delete Budget
        $this->transactionsModel->destroy(id: $transaction_id);

        // Return to referer
        header("Location: $referer?prompt=delete_budget ");
        die();
    }

}
