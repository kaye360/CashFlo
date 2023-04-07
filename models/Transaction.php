<?php
/**
 * 
 * Transaction Model
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Stores database actions relating to the 'transactions' table
 * 
 */
declare(strict_types=1);
namespace models\TransactionModel;

use DateTimeImmutable;
use lib\Auth\Auth;
use lib\Database\Database;



class TransactionModel {


    private string $table = 'transactions';

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
     * @method create a new Transaction
     * 
     */
    public function create(object $data): object
    {
        $create_new_transaction = $this->database
            ->table('transactions')
            ->cols('name, budget, amount, type, date, user_id')
            ->values(" '$data->name', '$data->selected_budget', '$data->amount', '$data->type', '$data->date', '" . Auth::user_id() . "' ")
            ->new();

        if( !$create_new_transaction ) 
        {
            return (object) [
                'error' => true, 
                'data'  => null
            ];
        }

        return (object) [
            'error' => false,
            'data'  => $data,
        ];
    }

    /**
     * 
     * @method Edit a transaction
     * 
     */
    public function update(
        ?string $name,
        ?string $budget,
        ?float $amount,
        string $type,
        string $date,
        int $user_id,
        int $id
    ) : bool {

        return $this->database
            ->table('transactions')
            ->set("
                name = '$name', 
                budget = '$budget', 
                amount = '$amount', 
                type = '$type', 
                date = '$date', 
                user_id = '$user_id' 
            ")
            ->where("id = '$id' ")
            ->update();
    }

    /**
     * 
     * @method Get currently logged in users transactions.
     * 
     */
    public function get_all() : array | null
    {
        $transactions = $this->database
            ->table('transactions')
            ->select('*')
            ->where("user_id = '" . Auth::user_id() . "' ")
            ->order('date DESC, id DESC')
            ->list();
        
        foreach($transactions as $transaction)
        {
            $date = $transaction->date;
            $date = new DateTimeImmutable($date);
            $transaction->date_as_words = $date->format('M d Y');
        }

        return $transactions;
    }

    /**
     * 
     * @method Get a Transaction
     * 
     */
    public function get(int $id) : object | false
    {
        return $this->database
            ->table('transactions')
            ->select('*')
            ->where("id = '" . $id . "' ")
            ->single();
    }

    /**
     * 
     * @method Destroy a transaction
     * 
     */
    public function destroy(int $id)
    {
        return $this->database
            ->table('transactions')
            ->where("id = '$id' ")
            ->destroy();
    }
}