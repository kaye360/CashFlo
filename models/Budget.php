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
namespace models\BudgetModel;

use lib\Database\Database;



class BudgetModel extends Database {

    /**
     * 
     * @method create the PDO object
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 
     * @method create a new user
     * 
     */
    public function create(object $data)
    {
        $create_new_budget = $this->table('budgets')
            ->cols('name, type, amount, user_id')
            ->values(" '$data->name', '$data->type', '$data->amount', '" . AUTH->user_id . "' ")
            ->new();

        if( !$create_new_budget ) 
        {
            return (object) [
                'error' => true, 
                'data' => null
            ];
        }

        return (object) [
            'error' => false,
            'data' => $data,
        ];
    }
}