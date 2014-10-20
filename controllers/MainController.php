<?php

/* 
 * Main application controller
 */
class MainController extends Controller
{
    /*
     * Index action shows hello
     */
    public function indexAction()
    {       
        $User = new User();
        
        $this->renderView('index', array('user' => $User));
        
        return true;
    }
    
    /*
     * Login action shows login form
     */  
    public function loginAction()
    {
        $User = new User();
        $flag = false;
        
        if(isset($_POST['login'])){
            $User->setAttributes($_POST['login']);
            
            if($User->authUser()) {
                $flag = true;
            }
        }
        
        $this->renderView('login', array('user' => $User, 'flag' => $flag));
        
        return true;
    } 
    
    
    /*
     * Login action shows registration form
     */  
    public function registrationAction()
    {
        $User = new User();
        $flag = false;
        
        if(isset($_POST['register'])){
            $User->setAttributes($_POST['register']);
            
            if($User->registerUser()) {
                $flag = true;
            }
        }
        
        $this->renderView('registration', array('user' => $User, 'flag' => $flag));
        
        return true;
    } 
    
    
    /*
     * Error action is invoked when requested action not found
     */  
    public function errorAction()
    {        
        $this->renderView('error');
        
        return true;
    }
}

