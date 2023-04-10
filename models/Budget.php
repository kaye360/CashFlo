<?php
/**
 * 
 * Budget Model
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Stores database actions relating to the 'budgets' table
 * 
 */
declare(strict_types=1);
namespace models\BudgetModel;

use lib\Auth\Auth;
use lib\Database\Database;
use lib\services\Budget\Budget;

class BudgetModel {

    /**
     * 
     * @method create the PDO object
     * 
     */
    public function __construct(protected Database $database)
    {
    }

    /**
     * 
     * @method create a new Budget
     * 
     */
    public function create( Budget $budget ): object
    {
        $create_new_budget = $this->database
            ->table('budgets')
            ->cols('name, type, amount, user_id')
            ->values(" '$budget->name', '$budget->type', '$budget->amount', '" . Auth::user_id() . "' ")
            ->new();

        if( !$create_new_budget ) 
        {
            return (object) [
                'error' => true, 
                'data'  => null
            ];
        }

        return (object) [
            'error' => false,
            'data'  => $budget,
        ];
    }

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function update( Budget $budget ) : bool {

        return $this->database
            ->set("name = '$budget->name', type = '$budget->type', amount = '$budget->amount' ")
            ->where("id = '$budget->id' ")
            ->update();
    }

    /**
     * 
     * @method Get currently logged in users budgets.
     * In order by type, then amount so that Income comes first
     * then spending.
     * 
     */
    public function get_all() : array | null
    {
        return $this->database
            ->select('*')
            ->table('budgets')
            ->where("user_id = '" . Auth::user_id() . "' ")
            ->order('type ASC, amount DESC')
            ->list( Budget::class );
    }

    /**
     * 
     * @method Get a Budget
     * 
     */
    public function get(int $id) : ?Budget
    {
        $budget =  $this->database
            ->select('*')
            ->table('budgets')
            ->where("id = '" . $id . "' ")
            ->single();

        return !$budget
            ? null
            : new Budget(
                id:      $budget->id,
                name:    $budget->name,
                type:    $budget->type,
                amount:  $budget->amount,
                user_id: $budget->user_id
            );
        
    }

    /**
     * 
     * @method Destroy a budget
     * 
     */
    public function destroy(int $id)
    {
        return $this->database
            ->table('budgets')
            ->where("id = '$id' ")
            ->destroy();
    }
}