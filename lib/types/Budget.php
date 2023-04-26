<?php
/**
 * 
 * Budget Service Class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to define and reference any budget
 * 
 */
declare(strict_types=1);
namespace lib\types\Budget;



class Budget {

    public $id;
    public $name;
    public $type;
    public $amount;
    public $user_id;


    /**
     * 
     * NOTE:
     * 
     * Must check if properties have already been set before the
     * constructor is called as this class is called with
     * PDO::FETCH_CLASS in Database::class
     * 
     */
    public function __construct(
        $id      = null,
        $name    = null,
        $type    = null,
        $amount  = null,
        $user_id = null
    ) {

        $this->id      = (int)    $this->id ?: (int) $id;

        $this->name    = (string) $this->name ?: (string) $name;

        $this->type    = (string) $this->type ?: (string) $type;

        $this->user_id = (int)    $this->user_id ?: (int) $user_id;

        $this->amount  = (string) $this->amount ?: (string) $amount;


        /**
         * Format amount to CAD with decimals
         */
        $this->amount = number_format( (float) $this->amount, 2, '.', '');
    }

}