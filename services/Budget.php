<?php
/**
 * 
 * Budget Serive class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used for more complex logic in Budget controller
 * 
 */
declare(strict_types=1);
namespace services\BudgetService;

use stdClass;
use lib\Auth\Auth;
use lib\types\Budget\Budget;
use lib\utils\Prompt\Prompt;
use lib\InputHandler\Sanitizer\Sanitizer;
use lib\InputHandler\Validator\Validator;
use lib\Redirect\Redirect\Redirect;

class BudgetService {


    /**
     * 
     * @method Validate a budget
     * 
     * @var budget_id should be:
     * null if creating new
     * Route::params()->id if updating
     * 
     */
    public static function validate_budget( int $budget_id = null ) : object
    {
        $new_budget             = new stdClass;

        $new_budget->validation = Validator::validate([
            'name'   => ['required', 'max:20' , 'has_spaces'],
            'amount' => ['required', 'number'],
            'type'   => ['required']
        ]);

        $amount = Sanitizer::sanitize($_POST['amount']);
        $amount = Sanitizer::money($_POST['amount']);

        $new_budget->budget = new Budget(
            id:      $budget_id,
            name:    Sanitizer::sanitize($_POST['name']),
            type:    Sanitizer::sanitize($_POST['type']),
            amount:  $amount,
            user_id: Auth::user_id()
        );

        return $new_budget;
    }

    /**
     * 
     * @method Create a budget
     * 
     */
    public static function create_budget( object $model, Budget $budget ) : void
    {
        $model->create( $budget );
        Redirect::to('/budgets')->prompt('success', 'Budget created successfully')->redirect();
    }
}