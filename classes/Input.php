<?php

class Input{
    
    /**
     * Checks if appropriate global array exists
     * @param string $type
     * @return bool
     */
    public static function exists($type = 'post'){
        switch ($type){
            case 'post':
                    return (!empty($_POST))? true : false;
                break;

            case 'get':

                break;

            default:
                    return false;
                break;
        }
    }

    /**
     * Fetches the variable from POST or GET array
     * @param $item
     * @return string
     */
    public static function get($item){
        if(isset($_POST[$item]))
            return $_POST[$item];
        else
            if(isset($_GET[$item]))
                return $_GET[$item];

        return '';
    }
}