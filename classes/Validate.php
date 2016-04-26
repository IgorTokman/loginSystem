<?php

class Validate{

    private $_passed = false,
            $_errors = array(),
            $_db = null;

    /**
     * Validate constructor.
     */
    public function  __construct()
    {
        $this->_db = DB::getInstance();
    }

    /**
     * Verifies if the table record meets the requirement
     * @param $source
     * @param array $items
     * @return $this
     */
    public function check($source, $items = array()){
        foreach ($items as $item => $rules){
            foreach ($rules as $rule => $rule_value){

                $value = trim($source[$item]);
                $item = escape($item);

                if($rule === 'required' && empty($value))
                    $this->addError("{$item} is required");
                else
                    if(!empty($value)){

                    switch ($rule){

                        case 'min':
                            if(strlen($value) < $rule_value)
                                $this->addError("{$item} must be a minimum of {$rule_value}");
                            break;

                        case 'max':
                            if(strlen($value) > $rule_value)
                                $this->addError("{$item} must be a maximum of {$rule_value}");
                            break;

                        case 'matches':
                            if($value != $source[$rule_value])
                                $this->addError("{$rule_value} must matches {$item}");
                            break;

                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if($check->count())
                                $this->addError("{$item} already exists");
                            break;
                    }
                }
                
            }
        }
        if(empty($this->_errors))
            $this->_passed = true;

        return $this;
    }

    /**
     * Gets the container of error messages
     * @param $error
     */
    private function addError($error){
        $this->_errors[] = $error;
    }

    /**
     * Fetches the _error property
     * @return array
     */
    public function errors(){
        return $this->_errors;
    }

    /**
     * Fetches the _passed property
     * @return bool
     */
    public function passed(){
        return $this->_passed;
    }
}
