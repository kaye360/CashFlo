<?php
/**
 * 
 * Transaction Service Class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to define and reference any transaction
 * 
 */
declare(strict_types=1);
namespace lib\types\Transaction;

use DateTimeImmutable;



class Transaction {

    public $id;
    public $name;
    public $budget;
    public $type;
    public $date;
    public $user_id;
    public $amount;
    public $date_english;
    public $date_month;


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
        $budget  = null,
        $type    = null,
        $date    = null,
        $user_id = null,
        $amount  = null
    ) {

        $this->id      = (int)    $this->id ?: (int) $id;

        $this->name    = (string) $this->name ?: (string) $name;

        $this->budget  = (string) $this->budget ?: (string) $budget;

        $this->type    = (string) $this->type ?: (string) $type;

        $this->date    = (string) $this->date ?: $date;

        $this->user_id = (int)    $this->user_id ?: (int) $user_id;

        $this->amount  = (string) $this->amount ?: (string) $amount;


        /**
         * Format amount to CAD with decimals
         */
        $this->amount = number_format( (float) $this->amount, 2, '.', '');
        
        /**
         * Format English, Month version of date
         */
        $date_obj           = new DateTimeImmutable($this->date);
        $this->date_english = $date_obj->format('M d Y');
        $this->date_month   = $date_obj->format('M Y');
    }

}