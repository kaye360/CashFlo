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

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use stdClass;



class BudgetsController extends Controller {

    private $budgetModel;
    

    public function __construct()
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
        return array_reduce($array, function($i, $budget) use($type)
        {
            if($budget->type === $type) $i += $budget->amount;
            return $i;
        });
    }

    /**
     * 
     * @method Get Net Budget Worth
     * 
     */
    private function get_budget_net_total(array $array) : float
    {
        return array_reduce($array, function($i, $budget)
        {
            if($budget->type === 'income') $i += $budget->amount;
            if($budget->type === 'spending') $i -= $budget->amount;
            return $i;
        });
    }

    /**
     * 
     * @method Budget Home page
     * 
     */
    public function new()
    {
        $data = new stdClass();
        $data->budgets = $this->budgetModel->get_all();
        $data->income_total = $this->get_budget_type_total('income', $data->budgets->data);
        $data->spending_total = $this->get_budget_type_total('spending', $data->budgets->data);
        $data->net_total = $this->get_budget_net_total($data->budgets->data);
        $data->prompt = $_GET['prompt'] ?? false;

        $this->view('budgets', $data);
    }

    /**
     * 
     * @method Create a budget
     * 
     */
    public function create() : void
    {
        $validator = InputHandler::validate([
            'name' => ['required', 'max:20' , 'has_spaces'],
            'amount' => ['required', 'number'],
            'type' => ['required']
        ]);

        $data = new stdClass();
        $data->name = InputHandler::sanitize('name');
        $data->amount = InputHandler::sanitize('amount');
        $data->amount = InputHandler::money('amount');
        $data->type = InputHandler::sanitize('type');
        $data->budgets = $this->budgetModel->index();
        $data->income_total = $this->get_budget_type_total('income', $data->budgets->data);
        $data->spending_total = $this->get_budget_type_total('spending', $data->budgets->data);
        $data->net_total = $this->get_budget_net_total($data->budgets->data);
        $data->errors = $validator->errors;
        $data->success = $validator->success;

        if( $data->success ) 
        {
            $new_budget = $this->budgetModel->create($data);
            
            if( $new_budget->error ) 
            {
                $data->success = false;
                $data->errors->query = true;

            } else {
                $data->success = true;
                $data->name = '';
                $data->amount = '';
            }
        }

        $data->budgets = $this->budgetModel->index();
        $this->view('budgets', $data);
    }

    /**
     * 
     * @method Edit Budget Form
     * 
     */
    public function edit() : void
    {
        $data = new stdClass();
        $data->id = (int) explode('/', $_SERVER['REQUEST_URI'])[2];
        $data->referer = parse_url( $_SERVER['HTTP_REFERER'] ?? '/budgets' , PHP_URL_PATH);

        $budget = $this->budgetModel->get(id: $data->id);

        $data->name = $budget->data->name;
        $data->type = $budget->data->type;
        $data->amount = $budget->data->amount;
        
        if( $data->id !== AUTH->user_id )
        {
            $data->budget = false;
        }

        $this->view('budget/edit', $data);
    }

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function update()
    {
        $validator = InputHandler::validate([
            'name' => ['required', 'max:20', 'has_spaces'],
            'amount' => ['required', 'number'],
            'type' => ['required']
        ]);

        $data = new stdClass();
        $data->name = InputHandler::sanitize('name');
        $data->amount = (float) InputHandler::sanitize('amount');
        $data->type = InputHandler::sanitize('type');
        $data->id = (int) InputHandler::sanitize('id');
        $data->referer = InputHandler::sanitize('referer');
        $data->errors = $validator->errors;
        $data->success = $validator->success;

        // Authorize Edit Budget
        $user = $this->budgetModel->get(id: $data->id);

        if( $user->data->user_id !== AUTH->user_id)
        {
            $this->view('unauthorized');
            die();
        }

        // Edit Budget`
        $this->budgetModel->update(
            name: $data->name,
            type: $data->type,
            amount: $data->amount,
            id: $data->id
        );

        $this->view('budget/edit', $data);
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
            $this->view('budget/edit', $data);
            return;
        }

        $budget_id = (int) $_POST['id'];
        $referer = $_POST['referer'];
        $referer = parse_url($referer, PHP_URL_PATH);

        // Authorize Delete Budget
        $user = $this->budgetModel->get(id: $budget_id);

        if( $user->data->user_id !== AUTH->user_id )
        {
            header('Location: /unauthorized');
            die();
        }

        // Delete Budget
        $this->budgetModel->destroy(id: $budget_id);

        // Return to referer
        header("Location: $referer?prompt=delete_budget ");
        die();
    }

}
