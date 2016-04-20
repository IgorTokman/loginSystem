<?php

class DB{

    private static $instance = null;
   
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;

    /**
     * Creates new db connection
     * @return DB|null
     */
    public static function getInstance()
    {
        if(is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * DB constructor.
     */
    private function __construct(){
        try{
           $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
                        Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e){
            die($e->getMessage());
        }
    }

    /**
     * Performs some sql statement
     * @param $sql
     * @param array $params
     * @return $this
     */
    public function query($sql, $params = array()){
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            }
            else{
                $this->_error = true;
            }
        }
        return $this;
    }

    /**
     * Performs some CRUD action with db table
     * @param $action
     * @param $table
     * @param array $where
     * @return $this|bool
     */
    private function action($action, $table, $where = array()){

        if(count($where) === 3){
            $operators = array('=', '>', '<', '>=', '<=', '=');

            list($field, $operator, $value) = $where;

            if(in_array($operator, $operators)){
                $sql = "{$action} * FROM {$table} WHERE {$field} {$operator} ?";

                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        return false;
    }

    /**
     * Fetches records from db table
     * @param $table
     * @param $where
     * @return bool|DB
     */
    public function get($table, $where){
        return $this->action('SELECT *', $table, $where);
    }

    /**
     * Deletes records from db table
     * @param $table
     * @param $where
     * @return bool|DB
     */
    public function delete($table, $where){
        return $this->action('DELETE', $table, $where);
    }

    /**
     * Gets the _error property
     * @return bool
     */
    public function error(){
        return $this->_error;
    }

    /**
     * Gets the _count property
     * @return int
     */
    public function count(){
        return $this->_count;
    }
}