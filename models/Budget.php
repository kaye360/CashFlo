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

use lib\Database\Database;



class BudgetModel {

    /**
     * 
     * @method create the PDO object
     * 
     */
    public function __construct(protected Database $database)
    {
        // parent::__construct();
    }

    /**
     * 
     * @method Get users budgets, In order by type, amount
     * 
     */
    public function get_budgets() : object
    {
        return $this->database
            ->select('*')
            ->table('budgets')
            ->where("user_id = '" . AUTH->user_id . "' ")
            ->order('type ASC, amount DESC')
            ->list();
    }

    /**
     * 
     * @method create a new user
     * 
     */
    public function create(object $data): object
    {
        $create_new_budget = $this->database
            ->table('budgets')
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

    /**
     * 
     * @method Edit a budget
     * 
     */
    public function edit(
        string $name,
        string $type,
        float $amount,
        int $id
    ) : object {

        return $this->database
            ->set("name = '$name', type = '$type', amount = '$amount' ")
            ->where("id = '$id' ")
            ->update();
    }

    /**
     * 
     * @method Get a Budget
     * 
     */
    public function get_budget(int $id) : object
    {
        return $this->database
            ->select('*')
            ->table('budgets')
            ->where("id = '" . $id . "' ")
            ->single();
    }
}