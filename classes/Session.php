<?php

class Session{

    /**
     * @param $name
     * @param $value
     * @return mixed
     */
    public static function put($name, $value){
        return $_SESSION[$name] = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public static function exists($name){
        return isset($_SESSION[$name]) ? true : false;
    }

    /**
     * @param $name
     */
    public static function delete($name){
        if(self::exists($name))
            unset($_SESSION[$name]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name){
        return $_SESSION[$name];
    }

    public static function flash($name, $string = ''){
        if(self::exists($name)){
            $session = self::get($name);
            self::delete($name);
            return $session;
        }
        else{
            self::put($name, $string);
        }
    }
}