<?php
/**
 * 
 * Database utility class
 * 
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 * Used to build MYSQL queries
 * This class is to be extended by and model Class. It may also
 * be called on its own when a query is needed on the fly
 * 
 * This class includeds query builder methods (select, table, where,
 * limit, etc) and action methods (single, list, destroy etc.). Action
 * methods are chained at the end
 * 
 * @example
 * $users = $this->select('username, id)    // Query Builder
 *  ->table('users')                        // Query Builder
 *  ->where('id = 10')                      // Query Builder
 *  ->single();                             // Action
 * 
 */
namespace lib\Database;


class Database
{
 
    /**
    * 
    * @var PDO vars
    * 
    */
    public $dbh;
    public $stmt;
 
    /**
    * 
    * @var Query Builder vars
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
     * Call singleton db connection
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
     * Used to build a sql query
     * Methods chained off the instance. Chain must ende with an Action method
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
     * Query Action Methods
     * 
     * Methods at the end of the chain that call the final mysql query
     * 
    */
 
    /**
     * 
     * @method SQL Select single row action query
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
        try 
        {
            if( is_null($this->select) || is_null($this->table) ) 
            {
                return $this->error('$select, $table are required in single method');
            }

            $sql = " SELECT $this->select FROM $this->table ";
            
            if( isset($this->where) ) $sql .= " WHERE $this->where ";
            if( isset($this->order) ) $sql .= " ORDER BY $this->order";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) 
            {
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
     * @method SQL Select multiple rows action query
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
        try 
        {
            if( is_null($this->select) || is_null($this->table) ) 
            {
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

            $rows = $this->stmt->fetchAll(\PDO::FETCH_OBJ);
            if($rows === false) return $this->error('No rows found');
            return (object) [ 'success' => true, 'data' => $rows ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 
    /**
     * 
     * @method SQL Create new row action query
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * table()
     * cols()
     * values()
     * 
     */
    public function new() {
        try 
        {
            if( is_null($this->table) || is_null($this->cols) || is_null($this->values) ) 
            {
                return $this->error('$table, $where, $values are required in destroy method.');
            }

            $sql = " INSERT INTO $this->table ($this->cols) VALUES ($this->values)";
            $this->stmt = $this->dbh->prepare($sql);
            
            
            if( !$this->stmt->execute() ) 
            {
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
     * @method SQL Destroy action query
     * 
     * Requires the following local methods to be pre-chained:
     * 
     * table('table_name')
     * where('column = value')
     * 
     */
    public function destroy() {
        try 
        {
            if( is_null($this->table) || is_null($this->where) ) 
            {
                return $this->error('$table, $where are required in destroy method.');
            }

            $sql = " DELETE FROM $this->table WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) 
            {
                return $this->error('Failed to execute query');
            }

            return [ 'success' => true ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }

    /**
     * 
     * @method SQL Update action query
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

            if( is_null($this->table) || is_null($this->set) || is_null($this->where) ) 
            {
                return $this->error('$table, $set, $where are required in update method.');
            }

            $sql = " UPDATE $this->table SET $this->set WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) 
            {
                return $this->error('Failed to execute query');
            }

            return [ 'success' => true ];

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }
 
    /**
     * 
     * @method SQL Count action query
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

            if( is_null($this->table) || is_null($this->select) ) 
            {
                return $this->error('$table, $select, $where are required in update method.');
            }

            $sql = " SELECT COUNT($this->select) FROM $this->table";
            if( isset($this->where) ) $sql .= " WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            
            if( !$this->stmt->execute() ) 
            {
                return $this->error('Failed to execute query');
            }

            $count = $this->stmt->fetchColumn();
            return $count;

        } catch (\Exception $error) {
            return $this->error('Fatal error with query: ' . $error->getMessage());
        }
    }

    /**
    * @todo verify if this is dead code
    * @method validates are request inputs are specified
    * 
    * @return bool
    * 
    */
    // public function is_valid_request(array|null $request, array $required_keys) 
    // {
    //     if( !is_array($request) || !is_array($required_keys) ) return false;

    //     $validated = true;

    //     foreach($required_keys as $key) {
    //         if( !array_key_exists($key, $request) ) {
    //             $validated = false;
    //         }
    //     }

    //     return $validated;
    // }
 
}