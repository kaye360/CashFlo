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
use lib\InputHandler\Sanitizer\Sanitizer;
use lib\Router\Route\Route;
use lib\types\Budget\Budget;
use lib\utils\Prompt\Prompt;
use services\BudgetService\BudgetService;
use stdClass;



class BudgetsController extends Controller {

    private $budgetModel;
    

    public function __construct()
    {
        $this->budgetModel = $this->model('Budget');
    }

    /**
     * 
     * @method Budget Home page
     * 
     */
    public function index() : void
    {
        $data                 =         new stdClass();
        $data->budgets        =         $this->budgetModel->get_all();
        $data->income_total   = (float) $this->get_type_total('income',   $data->budgets);
        $data->spending_total = (float) $this->get_type_total('spending', $data->budgets);
        $data->net_total      = (float) $this->get_net_total($data->budgets);

        $this->view('budgets/index', $data);
    }

    /**
     * 
     * /@method Create a budget
     * 
     */
    public function create() : void
    {
        $budget = BudgetService::validate_budget();

        $data                 = new stdClass();
        $data->budget         = $budget->budget;
        $data->errors         = $budget->validation->errors;
        $data->success        = $budget->validation->success;
        $data->budgets        = $this->budgetModel->get_all();
        $data->income_total   = $this->get_type_total('income',   $data->budgets);
        $data->spending_total = $this->get_type_total('spending', $data->budgets);
        $data->net_total      = $this->get_net_total($data->budgets);

        if( $data->success ) 
        {
            BudgetService::create_budget( $this->budgetModel, $budget->budget );

        } else {
            
            Prompt::set('error', 'Budget not created. Please check form inputs.');
        }
        
        $this->view('budgets/index', $data);
    }

    /**
     * 
     * @method Edit Budget Form
     * 
     */
    public function edit() : void
    {
        $budget = $this->budgetModel->get(id: (int) Route::params()->id );
        
        // Authorize Edit Budget
        Auth::authorize( $budget->user_id );
        
        $data          = new stdClass();
        $data->budget  = $budget;
        $data->referer = parse_url( $_SERVER['HTTP_REFERER'] ?? '/budgets' , PHP_URL_PATH);

        $this->view('budgets/edit', $data);
    }

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function update()
    {
        // Authorize Edit Budget
        $db_budget = $this->budgetModel->get(id: (int) Route::params()->id );
        Auth::authorize($db_budget->user_id);

        $budget = BudgetService::validate_budget( (int) Route::params()->id );

        $data          = new stdClass();
        $data->budget  = $budget->budget;
        $data->referer = Sanitizer::sanitize($_POST['referer']);
        $data->errors  = $budget->validation->errors;
        $data->success = $budget->validation->success;

        if( $data->success )
        {
            $this->budgetModel->update( $budget->budget );

            Prompt::set('success', 'Budget updated succesfully.');
            
        } else {
            
            Prompt::set('error', 'Budget not updated. Please check your form inputs');

        }

        $this->view('budgets/edit', $data);
    }

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function destroy()
    {
        // Authorize Delete Budget
        $user = $this->budgetModel->get(id: (int) Route::params()->id );
        Auth::authorize($user->user_id);

        $referer = $_POST['referer'] ?? '/budgets';
        $referer = parse_url($referer, PHP_URL_PATH);

        // Delete Budget
        $this->budgetModel->destroy(id: (int) Route::params()->id );

        Prompt::set('success', 'Budget was deleted successfully');

        // Return to referer
        header("Location: $referer");
        die();
    }
    
    /**
     * 
     * @method Get Total Amount of Income or Spending
     * 
     */
    private function get_type_total(
        string $type, 
        array $array
    ) : float {

        if( empty($array) ) return 0;

        return array_reduce($array, function($total, $budget) use($type)
        {
            if($budget->type === $type) $total += (float) $budget->amount;
            return $total;
        }) ?? 0;
    }

    /**
     * 
     * @method Get Net Budget Worth
     * 
     */
    private function get_net_total(array $array) : float
    {
        if( empty($array )) return 0;

        return array_reduce($array, function($total, $budget)
        {
            if ($budget->type === 'income')   $total += (float) $budget->amount;
            if ($budget->type === 'spending') $total -= (float) $budget->amount;
            return $total;
        });
    }


}
