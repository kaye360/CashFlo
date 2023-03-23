<?php
/**
 * 
 * Budgets Controller
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for pages relative to the 'budgets' table
 * 
 */
namespace controllers\BudgetsController;

use lib\Controller\Controller;
use lib\InputHandler\InputHandler;
use models\BudgetModel\BudgetModel;
use stdClass;



class BudgetsController extends Controller {


    public function budgets_home()
    {
        $data = new stdClass();
        $data->title = 'Budgets';
        $data->h1 = 'Budgets';

        $budgetModel = $this->model('Budget');
        $data->budgets = $budgetModel->select('*')
            ->table('budgets')
            ->where("user_id = '" . AUTH->user_id . "' ")
            ->order('name ASC')
            ->list();

        $this->view('budgets', $data);
    }

    public function create_budget()
    {
        $data = new stdClass();
        $data->title = 'Budgets';
        $data->h1 = 'Budgets';
        $data->name = InputHandler::sanitize('name');
        $data->amount = InputHandler::sanitize('amount');
        $data->amount = InputHandler::money('amount');
        $data->type = InputHandler::sanitize('type');
        q($data->amount);
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

        $budgetModel = $this->model('Budget');
        $data->budgets = $budgetModel->select('*')
            ->table('budgets')
            ->where("user_id = '" . AUTH->user_id . "' ")
            ->order('name ASC')
            ->list();

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

        $data->referer = $_SERVER['HTTP_REFERER'];

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

        // Authorize Delete Budget
        $budgetModel = new BudgetModel();
        $user = $budgetModel
            ->select('user_id')
            ->table('budgets')
            ->where("id = '$budget_id' ")
            ->single();

        if( $user->data->user_id !== AUTH->user_id )
        {
            header('Location: /error');
            die();
        }

        // Delete Budget
        $budgetModel->table('budgets')
            ->where("id = '$budget_id' ")
            ->destroy();

        // Return to referer
        header("Location: $referer ");
        die();
    }

}
