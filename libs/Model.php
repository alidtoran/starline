<?php

/* 
 * Base Model
 */
class Model 
{    
    /* 
     * Container for errors
     */
    public $errors = array();
    
    
    /* 
     * Container for attributes
     */
    public $attributes = array();
    
    
    /* 
     * Container for attribute rules
     */
    public $rules = array();
    
    
    /* 
     * Container for PDO Object
     */
    public $db = null;
    
    
    /*
     * Construct method
     */
    public function __construct()
    {
        $this->init();
        
        return true;
    }
       
     
    /*
     * Initialization method starts on object construction
     */  
    public function init()
    {
        return true;
    }
    
    
    /*
     * Attribute setter
     */
    public function setAttribute($name, $value) 
    {
        if(!isset($this->attributes[$name])) return false;
        
        $this->attributes[$name] = $value;
        
        return true;
    }     
      
    
    /*
     * Attributes setter
     */
    public function setAttributes($attributes = array()) 
    {
        foreach($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
        
        return true;
    }  
    
    
    /*
     * Attribute getter
     */
    public function getAttribute($name) 
    {        
        if(!isset($this->attributes[$name])) return NULL;
        
        return $this->attributes[$name]; 
    } 
    
    
    /*
     * Attributes getter
     */
    public function getAttributes() 
    {        
        return $this->attributes; 
    }  
    
    
    /*
     * Error setter
     */
    public function addError($attribute, $message) 
    {    
        $this->errors[$attribute] = $message;
        
        return true; 
    }
    
    
    /*
     * Error setter
     */
    public function addErrors($errors) 
    {  
        foreach($attributes as $attribute => $message) {
            $this->errors[$attribute] = $message;
        }
        
        return true; 
    }
    
    
    /*
     * Error setter
     */
    public function getError($attribute) 
    {    
        if(!isset($this->errors[$attribute])) return NULL;
        
        return $this->errors[$attribute]; 
    }
    
    
    /*
     * Error setter
     */
    public function getErrors() 
    {  
        return $this->_errors; 
    }
    
    
    /*
     * Method validates attributes by rules
     */
    public function validateAttributes()
    {
        $this->errors = array();
        
        foreach($this->rules as $attr => $rules) {
            if(!isset($this->attributes[$attr])) continue;
            
            foreach($rules as $rule => $value) {
                if($rule === 'match' && trim($this->attributes[$attr]) != '') {
                    if(!preg_match($value, $this->attributes[$attr])) {
                        $this->addError($attr, 'Неверный формат');
                    }
                } else if($rule === 'required') {                
                    if(trim($this->attributes[$attr]) === '') {
                        $this->addError($attr, 'Поле должно быть заполнено');
                    }
                }
            }
        }
                
        return count($this->errors) > 0 ? false : true;
    }
    
    
}


