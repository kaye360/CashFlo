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
namespace controllers\BudgetsController;

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use models\BudgetModel\BudgetModel;
use stdClass;



class BudgetsController extends Controller {
    
    /**
     * 
     * @method Get users budgets, In order by type, amount
     * 
     */
    private function get_budgets()
    {
        $budgetModel = $this->model('Budget');
        $budgets = $budgetModel->select('*')
            ->table('budgets')
            ->where("user_id = '" . AUTH->user_id . "' ")
            ->order('type ASC, amount DESC')
            ->list();
        return $budgets;
    }

    /**
     * 
     * @method Get Total Amount of Income or Spending
     * 
     */
    private function get_budget_type_total(string $type, array $array)
    {
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
    private function get_budget_net_total(array $array)
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
    public function budgets_home()
    {
        $data = new stdClass();
        $data->title = 'Budgets';
        $data->h1 = 'Budgets';
        $data->budgets = $this->get_budgets();
        $data->prompt = isset($_GET['prompt'])
            ? $_GET['prompt']
            : false;
        
        $data->income_total = $this->get_budget_type_total('income', $data->budgets->data);
        $data->spending_total = $this->get_budget_type_total('spending', $data->budgets->data);
        $data->net_total = $this->get_budget_net_total($data->budgets->data);

        $this->view('budgets', $data);
    }

    /**
     * 
     * @method Create a budget
     * 
     */
    public function create_budget()
    {
        $data = new stdClass();
        $data->title = 'Budgets';
        $data->h1 = 'Budgets';
        $data->name = InputHandler::sanitize('name');
        $data->amount = InputHandler::sanitize('amount');
        $data->amount = InputHandler::money('amount');
        $data->type = InputHandler::sanitize('type');

        $validator = InputHandler::validate([
            'name' => ['required', 'max:20' , 'has_spaces'],
            'amount' => ['required', 'number'],
            'type' => ['required']
        ]);

        $data->errors = $validator->errors;
        $data->success = $validator->success;

        if( $data->success ) 
        {
            $budgetModel = $this->model('Budget');
            $new_budget = $budgetModel->create($data);
            
            if( $new_budget->error ) 
            {
                $data->success = false;
                $data->errors->query = true;

            } else {
                $data->success = true;
            }
        }

        $data->budgets = $this->get_budgets();
        $this->view('budgets', $data);
    }

    /**
     * 
     * @method Edit Budget Form
     * 
     */
    public function edit_budget_form()
    {
        $data = new stdClass();
        $data->title = 'Edit budget';
        $data->h1 = 'Edit Budget: ';
        $data->id = explode('/', $_SERVER['REQUEST_URI'])[2];

        $data->referer = parse_url(
            isset($_SERVER['HTTP_REFERER'])
                ? $_SERVER['HTTP_REFERER']
                : '/budgets',
            PHP_URL_PATH
        );

        $budgetModel = $this->model('Budget');
        $budget = $budgetModel->select('*')
            ->table('budgets')
            ->where("id = '" . $data->id . "' ")
            ->single();

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
    public function edit_budget()
    {
        $data = new stdClass();
        $data->title = 'Edit Budget';
        $data->h1 = 'Edit Budget';

        $data->name = InputHandler::sanitize('name');
        $data->amount = InputHandler::sanitize('amount');
        $data->type = InputHandler::sanitize('type');
        $data->id = InputHandler::sanitize('id');
        $data->referer = InputHandler::sanitize('referer');

        $validator = InputHandler::validate([
            'name' => ['required', 'max:20', 'has_spaces'],
            'amount' => ['required', 'number'],
            'type' => ['required']
        ]);

        $data->errors = $validator->errors;
        $data->success = $validator->success;

        // Authorize Edit Budget
        $budgetModel = new BudgetModel();
        $user = $budgetModel
            ->select('user_id')
            ->table('budgets')
            ->where("id = '$data->id' ")
            ->single();

        if( $user->data->user_id !== AUTH->user_id)
        {
            header('Location: /unauthorized');
            die();
        }

        // Edit Budget`
        $budgetModel->set("name = '$data->name', type = '$data->type', amount = '$data->amount' ")
            ->where("id = '$data->id' ")
            ->update();

        $this->view('budget/edit', $data);
    }

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function delete_budget()
    {
        $data = new stdClass();
        $data->title = 'Delete Budget';
        $data->h1 = 'Delete Budget';
        
        if( empty($_POST['referer']) || empty($_POST['id']) )
        {
            $this->view('budget/edit', $data);
            return;
        }

        $budget_id = $_POST['id'];
        $referer = $_POST['referer'];
        $referer = parse_url($referer, PHP_URL_PATH);

        // Authorize Delete Budget
        $budgetModel = new BudgetModel();
        $user = $budgetModel
            ->select('user_id')
            ->table('budgets')
            ->where("id = '$budget_id' ")
            ->single();

        if( $user->data->user_id !== AUTH->user_id )
        {
            header('Location: /unauthorized');
            die();
        }

        // Delete Budget
        $budgetModel->table('budgets')
            ->where("id = '$budget_id' ")
            ->destroy();

        // Return to referer
        header("Location: $referer?prompt=delete_budget ");
        die();
    }

}
