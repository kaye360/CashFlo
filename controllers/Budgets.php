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

        $budgetModel = $this->model('Budget');
        $data->budgets = $budgetModel->select('*')
            ->table('budgets')
            ->where("id = '" . $data->id . "' ")
            ->single();

        q($data->budgets);

        $this->view('budget/edit', $data);
    }

}
