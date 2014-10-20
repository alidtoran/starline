<?php

/* 
 * Model User
 */
class User extends Model
{       
    const SECURITY_SALT = '1knd4nw0305iond782';
    
    const SEX_MALE = 0;
    const SEX_FEMALE = 1;
    
    /*
     * if true, allows to use login as username in authorization
     */
    const CHECK_AUTH_BY_LOGIN = true;
    /*
     * if true, allows to use mail as username in authorization
     */
    const CHECK_AUTH_BY_MAIL = true;
    /*
     * if true, allows to use phone as username in authorization
     */
    const CHECK_AUTH_BY_PHONE = true;
        
    
    const MAX_AUTH_ATTEMPTS = 3; 
    const COOLDOWN_ATTEMPTS = 30;
    
    public function init()
    {
        $this->attributes = array(
            'login' => '',
            'password' => '',
            'email' => '',
            'date_birth' => '',
            'sex' => '',
            'phone' => '',
            'name' => ''
        );     
        
        $this->rules = array(
            'login' => array('required' => true),            
            'password' => array('required' => true),            
            'email' => array('match' => '/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/', 'required' => true),            
            'date_birth' => array('match' => '/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/'),            
            'sex' => array('match' => '/^(0|1)$/'),            
            'phone' => array('match' => '/^\+7\s\([0-9]{3}\)\s[0-9]{3}\-[0-9]{2}\-[0-9]{2}$/', 'required' => true),          
        );
        
        $this->db = new PDO('mysql:host=localhost;dbname=starline', 
            'root', 'root', 
            array( PDO::ATTR_PERSISTENT => false,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        
        return true;
    }
    
    
    /*
     * Method searches and authenticates user
     */
    public function authUser()
    {
        $user = $this->searchUserByAttributes();
        
        if($user === false) {
            $this->addError('login', 'Пользователь с таким логином не существует!');
            
            return false;
        }     
        
        if($user['attempts'] > 0) {
            if(strtotime($user['date_attempt']) + self::COOLDOWN_ATTEMPTS <= time()){
                 $this->clearAttempts();
            }
        }
           
        if($user['attempts'] >= self::MAX_AUTH_ATTEMPTS) {
            $this->addError('login', 'Слишком много попыток!'); 
        } else if(md5($this->attributes['password'] . self::SECURITY_SALT) !== $user['password']) {
            $this->addError('password', 'Неверный пароль');
            
            $this->increaseAttempts();
        }
        
        if(count($this->errors) > 0) return false;
                
        $this->setAttributes($user);
        
        return true;
    }
   
   
    /*
     * Method validates data and stores it to db
     */
    public function registerUser()
    {
        if(!$this->validateAttributes()) return false;
                
        if($this->createUser() === false) {
            $this->addError('login', 'При сохранении данных возникла ошибка, повторите попытку позже!');
            
            return false;
        }
        
        return true;
    }
    
   
    /*
     * Method inserts user's data into db
     */
    public function createUser()
    {
        try {
            $q = $this->db->prepare('INSERT INTO user '
                    . '(login, password, email, phone, name, sex, date_birth, attempts, date_attempt, date_created) '
                    . 'VALUES '
                    . '(:login, :password, :email, :phone, :name, :sex, :date_birth, :attempts, :date_attempt, :date_created)');
            
            $res = $q->execute(array(
                ':login' => htmlspecialchars($this->getAttribute('login'), ENT_QUOTES),
                ':password' => md5($this->getAttribute('password') . self::SECURITY_SALT),
                ':email' => htmlspecialchars($this->getAttribute('email'), ENT_QUOTES),
                ':phone' => $this->getAttribute('phone'),
                ':name' => htmlspecialchars($this->getAttribute('name'), ENT_QUOTES),
                ':sex' => $this->getAttribute('sex'),
                ':date_birth' => $this->getAttribute('date_birth'),
                ':attempts' => 0,
                ':date_attempt' => date('Y-m-d H:i:s'),
                ':date_created' => date('Y-m-d H:i:s')));
                        
            return $res;
        } catch (Exception $ex) {
            return false;
        }
    }
    
   
    /*
     * Method searches users in db by attributes
     */
    public function searchUserByAttributes()
    {
        try {     
            list($fields, $params) = $this->prepareLoginFields();
                        
            $q = $this->db->prepare('SELECT * FROM user WHERE ' . implode(' OR ', $fields));
                
            $q->execute($params);
            
            $res = $q->fetchAll(PDO::FETCH_ASSOC);
            
            return isset($res[0]) ? $res[0] : false;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    
    /*
     * Sets attempts count to 0
     */
    public function clearAttempts()
    {
        try {
            list($fields, $params) = $this->prepareLoginFields();
                                    
            $q = $this->db->prepare('UPDATE user SET attempts = 0 WHERE ' . implode(' OR ', $fields));
            
            $res = $q->execute($params);
                                    
            return $res;
        } catch (Exception $ex) {
            return false;
        }   
    }
    
    /*
     * Increases attempts count and update time of last attempt
     */
    public function increaseAttempts()
    {
        try {
            list($fields, $params) = $this->prepareLoginFields();
            
            $params[':date_attempt'] = date('Y-m-d H:i:s');
                        
            $q = $this->db->prepare('UPDATE user SET attempts = attempts + 1 '
                    . ', date_attempt = :date_attempt  WHERE ' . implode(' OR ', $fields));
            
            $res = $q->execute($params);
                                    
            return $res;
        } catch (Exception $ex) {
            return false;
        }
    }
    
    
    public function prepareLoginFields()
    {
        $fields = array();
        $params = array();            

        if(self::CHECK_AUTH_BY_LOGIN) {
            $fields[] = 'login = :login';
            $params[':login'] = htmlspecialchars($this->getAttribute('login'), ENT_QUOTES);
        }

        if(self::CHECK_AUTH_BY_MAIL) {
            $fields[] = 'email = :email';
            $params[':email'] = htmlspecialchars($this->getAttribute('login'), ENT_QUOTES);
        }

        if(self::CHECK_AUTH_BY_PHONE) {
            $fields[] = 'phone = :phone';
            $params[':phone'] = htmlspecialchars($this->getAttribute('login'), ENT_QUOTES);
        }
        
        return array($fields, $params);
    }
}
