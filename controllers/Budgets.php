<?php
/**
 * 
 * Budgets Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for pages related to the 'budgets' table
 * 
 */
declare(strict_types=1);
namespace controllers\BudgetsController;

use lib\Auth\Auth;
use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use lib\Router\Route\Route;
use stdClass;



class BudgetsController extends Controller {

    private $budgetModel;
    

    public function __construct(protected mixed $param = null)
    {
        $this->budgetModel = $this->model('Budget');
    }

    /**
     * 
     * @method Get Total Amount of Income or Spending
     * 
     */
    private function get_budget_type_total(
        string $type, 
        array $array
    ) : float {

        if( empty($array) ) return 0;

        return array_reduce($array, function($total, $budget) use($type)
        {
            if($budget->type === $type) $total += $budget->amount;
            return $total;
        }) ?? 0;
    }

    /**
     * 
     * @method Get Net Budget Worth
     * 
     */
    private function get_budget_net_total(array $array) : float
    {
        if( empty($array )) return 0;

        return array_reduce($array, function($total, $budget)
        {
            if ($budget->type === 'income')   $total += $budget->amount;
            if ($budget->type === 'spending') $total -= $budget->amount;
            return $total;
        });
    }

    /**
     * 
     * @method Budget Home page
     * 
     */
    public function index() : void
    {
        $data                 =         new stdClass();
        $data->budgets        = (array) $this->budgetModel->get_all();
        $data->income_total   = (float) $this->get_budget_type_total('income',   $data->budgets);
        $data->spending_total = (float) $this->get_budget_type_total('spending', $data->budgets);
        $data->net_total      = (float) $this->get_budget_net_total($data->budgets);
        $data->prompt         =         $_GET['prompt'] ?? false;
        $this->view('budgets/index', $data);
    }

    /**
     * 
     * @method Create a budget
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
        $data->amount  = InputHandler::sanitize($_POST['amount']);
        $data->type    = InputHandler::sanitize($_POST['type']);
        $data->amount  = InputHandler::money($_POST['amount']);
        $data->errors  = $validator->errors;
        $data->success = $validator->success;

        if( $data->success ) 
        {
            $new_budget = $this->budgetModel->create($data);
            
            if( $new_budget->error ) 
            {
                $data->success       = false;
                $data->errors->query = true;

            } else {
                $data->success = true;
                $data->name    = '';
                $data->amount  = '';
            }
        }
        
        $data->budgets        = $this->budgetModel->get_all();
        $data->income_total   = $this->get_budget_type_total('income',   $data->budgets->data ?? []);
        $data->spending_total = $this->get_budget_type_total('spending', $data->budgets->data ?? []);
        $data->net_total      = $this->get_budget_net_total($data->budgets->data ?? []);
        
        $this->view('budgets/index', $data);
    }

    /**
     * 
     * @method Edit Budget Form
     * 
     */
    public function edit() : void
    {
        $id = (int) Route::params()->id;
        
        // Authorize Edit Budget
        $user = $this->budgetModel->get(id: $id);
        Auth::authorize($user->user_id ?? 0);

        $budget        = $this->budgetModel->get(id: $id);
        $data          = new stdClass();
        $data->id      = $id;
        $data->referer = parse_url( $_SERVER['HTTP_REFERER'] ?? '/budgets' , PHP_URL_PATH);
        $data->name    = $budget->name;
        $data->type    = $budget->type;
        $data->amount  = $budget->amount;

        $this->view('budgets/edit', $data);
    }

    /**
     * 
     * @method Edit a budget
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
        $data->name    =         InputHandler::sanitize($_POST['name']);
        $data->amount  = (float) InputHandler::sanitize($_POST['amount']);
        $data->type    =         InputHandler::sanitize($_POST['type']);
        $data->id      = (int)   InputHandler::sanitize($_POST['id']);
        $data->referer =         InputHandler::sanitize($_POST['referer']);
        $data->errors  = $validator->errors;
        $data->success = $validator->success;

        // Authorize Edit Budget
        $user = $this->budgetModel->get(id: $data->id);
        Auth::authorize($user->user_id);

        /**
         * 
         * @todo add validation!!
         */

        // Edit Budget`
        $this->budgetModel->update(
            name:   $data->name,
            type:   $data->type,
            amount: $data->amount,
            id:     $data->id
        );

        $this->view('budgets/edit', $data);
    }

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function destroy()
    {
        $data = new stdClass();
        
        if( empty($_POST['referer']) || empty($_POST['id']) )
        {
            $this->view('budgets/edit', $data);
            return;
        }

        $budget_id = (int) $_POST['id'];
        $referer   = $_POST['referer'];
        $referer   = parse_url($referer, PHP_URL_PATH);

        // Authorize Delete Budget
        $user = $this->budgetModel->get(id: $budget_id);
        Auth::authorize($user->user_id);

        // Delete Budget
        $this->budgetModel->destroy(id: $budget_id);

        // Return to referer
        header("Location: $referer?prompt=delete_budget ");
        die();
    }

}
