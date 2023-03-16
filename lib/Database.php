<?php
namespace Database;

use \DB_USER;
/**
 * 
 * Database utility methods
 * @author Josh Kaye
 * https://joshkaye.dev
 * 
 */



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
 
 
 
     public function __construct()
     {
         // Set Connection Options
         $options = array(
             \PDO::ATTR_PERSISTENT => true,
             \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
         );
 
         // Connect to DB
         try {
             $this->dbh = new \PDO($this->dsn, DB_USER, DB_PASS, $options);
         } catch (\PDOException $err) {
             // http_response_code(500);
             return [
                 'success' => false,
                 'message' => 'Failed PDO connection'
             ];
         }
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
      * Query Execute + Return Methods
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
             if( isset($this->limit) ) $sql .= " LIMIT $this->limit ";
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
 
             return [ 'success' => true, 'data' => $count ];
 
         } catch (\Exception $error) {
             return $this->error('Fatal error with query: ' . $error->getMessage());
         }
     }
 
     /**
      * 
      * @method is value of column already taken. For unique columns
      * 
      * @return bool
      * 
      */
     public function is_taken(
         string $column,
         string $value,
         string $table
     ) {
 
         $sql = "SELECT $column, id from $table WHERE $column = '$value'";
         $this->stmt = $this->dbh->prepare($sql);
         $this->stmt->execute();
 
         $result = $this->stmt->fetch(\PDO::FETCH_ASSOC);
 
         return empty($result) ? false : true;
     }
 
     /** 
      * 
      * @method checks if array of strings has forbidden characters
      * 
      * @return bool
      * 
      */
     public function has_forbidden_chars(
         array $values,
         string $regex = null
     ) {
 
         $has_forbidden_chars = null;
 
         foreach ($values as $value) {
             $tester = $regex
                 ? preg_match($regex, $value)
                 : preg_match('/^[a-zA-Z0-9_\-]+$/', $value);
             if (!(bool) $tester) $has_forbidden_chars = true;
         }
 
         return $has_forbidden_chars;
     }
 
     /**
      * 
      * @method checks if string too long
      * 
      * @return bool
      * 
      */
     public function has_too_many_chars(string $string, int $limit)
     {
         return strlen($string) > $limit;
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