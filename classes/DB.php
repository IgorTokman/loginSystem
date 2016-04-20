<?php

class DB{

    private static $instance = null;
    
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;

    public static function getInstance()
    {
        if(is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    private function __construct(){
        try{
           $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
                        Config::get('mysql/username'), Config::get('mysql/password'));
        } catch (PDOException $e){
            die($e->getMessage());
        }
    }
}