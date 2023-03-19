<?php
/**
 * 
 * Database utility class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Section 1: 
 * - DB setup
 * - request, error methods
 * 
 * Section 2:
 * - SQL query builder methods
 * 
 * Section 3:
 * - SQL exectution action methods
 * 
 * How to use
 * 
 * Extend this class for a model and chain the instance with 
 * SQL query builder methods. Finalize with an action method.
 * 
 * Example:
 * $model->table('users')
 *       ->select('username, email')   
 *       ->where('online = true')
 *       ->list()   
 * 
 */
namespace lib\Database;

use lib\DBConnect\DBConnect;

class Database
{
 
     /**
      * 
      * @var $dsn $dbh $stmt - db connection/PDO variables
      * 
      */
     private $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
     public $dbh;
     public $stmt;
 
     /**
      * 
      * @var Query prep vars
      * 
      */
     private $select = '*';
     private $table;
     private $where;
     private $order = 'id DESC';
     private $limit;
     private $cols;
     private $values;
     private $set;
 
 
    /**
     * 
     * Create PDO and set up db connection
     * 
     */
    public function __construct()
    {
        $this->dbh = DB_CONNECTION->connection();
    }
 
    /**
     * 
    * @method return the current request body
    * 
    */
    public function request()
    {
        return json_decode(file_get_contents('php://input'), true);
    }




    /**
     * 
    * @method return an error array
    * 
    */
    public function error(string $message) {
        return ['success' => false, 'message' => $message];
    }
 


    /**
     * 
     * Query Prep Methods
     * 
     * Methods chained off the object, ended with an Action method
     * Used to build a sql query
     * 
    */



    public function select(string $select='*')
    {
        $this->select = $select;
        return $this;
    }

    public function table(string $table) 
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $where)
    {
        $this->where = $where;
        return $this;
    }

    public function order(string $order)
    {
        $this->order = $order;
        return $this;
    }

    public function limit(string $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function cols(string $cols)
    {
        $this->cols = $cols;
        return $this;
    }

    public function values(string $values)
    {
        $this->values = $values;
        return $this;
    }

    public function set(string $set)
    {
        $this->set = $set;
        return $this;
    }
 


    /**
     * 
     * Query Execute Methods
     * 
     * Methods at the end of the chain that call the final mysql query
     * 
    */


 
    /**
     * 
     * @method Select single rows action query
     * Executes a sql select of a single row
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * select('column1, column2')
     * table('table_name')
     * 
     * Optional local methods
     * 
     * where('column = value')
     * order('ASC')
     * 
     */
    public function single() {
        try {

            if( is_null($this->select) || is_null($this->table) ) {
                return $this->error('$select, $table are required in single method');
            }

            $sql = " SELECT $this->select FROM $this->table ";
            
            if( isset($this->where) ) $sql .= " WHERE $this->where ";
            if( isset($this->order) ) $sql .= " ORDER BY $this->order";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) {
                return $this->error('Failed to execute query');
            }

            $row = $this->stmt->fetch(\PDO::FETCH_ASSOC);
            if($row === false) return $this->error('No rows found');
            return [ 'success' => true, 'data' => $row ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 
    /**
     * 
     * @method Select multiple rows action query
     * Executes a sql select of multiple rows
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * select('column1, column2')
     * table('table_name')
     * 
     * Optional local methods:
     * 
     * where('column = value')
     * order('ASC')
     * limit('#')
     * 
     */
    public function list() {
        try {

            if( is_null($this->select) || is_null($this->table) ) {
                return $this->error('$select, $table are required in list method');
            }

            $sql = " SELECT $this->select FROM $this->table ";
            
            if( isset($this->where) ) $sql .= " WHERE $this->where ";
            if( isset($this->order) ) $sql .= " ORDER BY $this->order";
            if( isset($this->limit) ) $sql .= " LIMIT $this->limit ";
            
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) {
                return $this->error('Failed to execute query');
            }

            $rows = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
            if($rows === false) return $this->error('No rows found');
            return [ 'success' => true, 'data' => $rows ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 
    /**
     * 
     * @method Create new row action query
     * Executes a sql create row
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * table()
     * cols()
     * values()
     * 
     */
    public function new() {
        try {

            if( is_null($this->table) || is_null($this->cols) || is_null($this->values) ) {
                return $this->error('$table, $where, $values are required in destroy method.');
            }

            $sql = " INSERT INTO $this->table ($this->cols) VALUES ($this->values)";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) {
                return $this->error('Failed to execute query');
            }

            $data = $this->dbh->lastInsertId();

            return [ 'success' => true, 'data' => $data ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 
    /**
     * 
     * @method Destroy action query
     * Executes a sql delete row
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * table('table_name')
     * where('column = value')
     * 
     */
    public function destroy() {
        try {

            if( is_null($this->table) || is_null($this->where) ) {
                return $this->error('$table, $where are required in destroy method.');
            }

            $sql = " DELETE FROM $this->table WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) {
                return $this->error('Failed to execute query');
            }

            return [ 'success' => true ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
     

    /**
     * 
     * @method Update action query
     * Executes a sql update row
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * table('table_name')
     * set('column = value')
     * where('column = value')
     * 
     */
    public function update() {
        try {

            if( is_null($this->table) || is_null($this->set) || is_null($this->where) ) {
                return $this->error('$table, $set, $where are required in update method.');
            }

            $sql = " UPDATE $this->table SET $this->set WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) {
                return $this->error('Failed to execute query');
            }

            return [ 'success' => true ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 
    /**
     * 
     * @method Count action query
     * Executes a count of a given table
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * select('column1, column2')
     * table('table_name')
     * 
     * Optional local methods:
     * 
     * where()
     * 
     */
    public function count() {
        try {

            if( is_null($this->table) || is_null($this->select) ) {
                return $this->error('$table, $select, $where are required in update method.');
            }

            $sql = " SELECT COUNT($this->select) FROM $this->table";
            if( isset($this->where) ) $sql .= " WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) {
                return $this->error('Failed to execute query');
            }

            $count = $this->stmt->fetchColumn();

            return $count;

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 

 

 


    /**
     * 
    * @method validates are request inputs are specified
    * 
    * @return bool
    * 
    */
    public function is_valid_request(array|null $request, array $required_keys) 
    {
        if( !is_array($request) || !is_array($required_keys) ) return false;

        $validated = true;

        foreach($required_keys as $key) {
            if( !array_key_exists($key, $request) ) {
                $validated = false;
            }
        }

        return $validated;
    }
 
}