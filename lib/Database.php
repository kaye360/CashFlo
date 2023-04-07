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
declare(strict_types=1);
namespace lib\Database;

use PDO;
use Exception;
use exceptions\DatabaseException\DatabaseException;
use utils\GenericUtils\GenericUtils;

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
    protected ?string $select = '*';
    protected ?string $order  = 'id DESC';
    protected ?string $table  = null;
    protected ?string $where  = null;
    protected ?string $limit  = null;
    protected ?string $cols   = null;
    protected ?string $values = null;
    protected ?string $set    = null;
 
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
        return json_decode(file_get_contents('php://input'));
    }

    /**
     * 
    * @method return an error array
    * 
    */
    public function error(string $message) {
        return (object) ['success' => false, 'message' => $message];
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
    public function single() : object | false
    {
        try 
        {
            if( !$this->select || !$this->table || !$this->where ) 
            {
                throw DatabaseException::missingQueryMethod('select(), $table(), $where()');
            }

            $sql = " SELECT $this->select FROM $this->table ";
            
            if( isset($this->where) ) $sql .= " WHERE $this->where ";
            if( isset($this->order) ) $sql .= " ORDER BY $this->order";

            $this->stmt = $this->dbh->prepare($sql);
            $this->stmt->execute();

            $row = $this->stmt->fetch(PDO::FETCH_OBJ);

            return $row;

        } catch (Exception $error) {
            echo $error->getMessage();
            return null;
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
    public function list() : array
    {
        try 
        {
            if( !$this->select || !$this->table ) 
            {
                throw DatabaseException::missingQueryMethod('select(), table()');
            }

            $sql = " SELECT $this->select FROM $this->table ";
            
            if( isset($this->where) ) $sql .= " WHERE $this->where ";
            if( isset($this->order) ) $sql .= " ORDER BY $this->order";
            if( isset($this->limit) ) $sql .= " LIMIT $this->limit ";
            
            $this->stmt = $this->dbh->prepare($sql);
            $this->stmt->execute();

            $rows = $this->stmt->fetchAll(PDO::FETCH_OBJ);

            return $rows;

        } catch (Exception $e) {

            GenericUtils::render_exception($e);
            return [];
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
    public function new() : string | false
    {
        try 
        {
            if( !$this->table || !$this->cols || !$this->values ) 
            {
                throw DatabaseException::missingQueryMethod('table(), where(), values()');
            }

            $sql = " INSERT INTO $this->table ($this->cols) VALUES ($this->values)";

            $this->stmt = $this->dbh->prepare($sql);
            $this->stmt->execute();

            $new_data = $this->dbh->lastInsertId();
            return $new_data;

        } catch (Exception $e) {

            GenericUtils::render_exception($e);
            return false;
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
    public function destroy() : bool
    {
        try 
        {
            if( !$this->table || !$this->where ) 
            {
                throw DatabaseException::missingQueryMethod('table(), where()');
            }

            $sql        = " DELETE FROM $this->table WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            $execute    = $this->stmt->execute();

            return $execute;

        } catch (Exception $e) {

            GenericUtils::render_exception($e);
            return false;
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
    public function update() : bool
    {
        try {

            if( !$this->table || !$this->set || !$this->where ) 
            {
                throw DatabaseException::missingQueryMethod('table(), set(), where()');
            }

            $sql        = " UPDATE $this->table SET $this->set WHERE $this->where";
            $this->stmt = $this->dbh->prepare($sql);
            $execute    = $this->stmt->execute();

            return $execute;

        } catch (Exception $e) {

            GenericUtils::render_exception($e);
            return false;
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
    public function count() : int
    {
        try {

            if( !$this->table || !$this->select ) 
            {
                throw DatabaseException::missingQueryMethod('table(), select(), where()');
            }

            $sql = " SELECT COUNT($this->select) FROM $this->table";
            
            if( isset($this->where) ) $sql .= " WHERE $this->where";
            
            $this->stmt = $this->dbh->prepare($sql);
            $this->stmt->execute();

            return $this->stmt->fetchColumn();

        } catch (Exception) {

            return 0;

        }
    }

}