<?php

/* 
 * Base Controller
 */
class Controller 
{    
    /* 
     * Default actionName
     */
    public $action = 'index';
    
    
    /*
     * Container fo request params
     */
    public $params = array();
    
    
    /*
     * Path to directory with views
     */
    public $viewPath = 'views/';
    
    
    /*
     * Root directory of application
     */
    public $basePath = 'views/';
    
    
    /*
     * Base url of application
     */
    public $baseUrl = '';
    
   
    /*
     * Method defines action, request parameters 
     * and executes action method
     */
    public function run($params = array()) 
    {
        $action = $this->action;
        
        if(count($params) > 0) {
            $action = $params[0];
        }
                
        if(method_exists($this, $action . 'Action')) {
            $this->action = array_shift($params);
        }
        
        $this->{$action . 'Action'}();
        
        return true; 
    }
    
    
    /* 
     * Default action
     */ 
    public function indexAction()
    {
        return true;
    }
    
    
    /*
     * Shows requested view file
     */
    public function renderView($view, $data = array())
    {
        if(is_file($this->viewPath . $view . '.php')) {
            extract($data, EXTR_OVERWRITE);
                    
            require $this->viewPath . $view . '.php';
        } else {
            
        }
    }
}
