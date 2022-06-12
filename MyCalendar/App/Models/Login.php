<?php

namespace App\Models;

use App\Database\Database;

class Login{
    public ?string $login = '';
    public ?string $password = '';

    protected $errors = [];

    public function __construct(array $data=[]){      
        $this->fillAuthorize($data);
    }

    public function fillAuthorize(array $data = [])
    {
        if ($data)
        {        
            $this->login = $data['login'] ?? '';
            $this->password = $data['password'] ?? '';
        } 
    }

    public function validate_authorize() : bool{

        $this -> errors = [];
        //проверка на заполненности полей   
        if (!$this -> login)
        {
            $this -> errors[]='Поле логин не заполнено';
        }
        if (!$this -> password)
        {
            $this -> errors[]= 'Поле пароль не заполнено';
        }
        //проверка логина и пароля   
        if ($this -> login && $this -> password)
        {
            $check = $this -> CheckUser($this -> login, $this -> password);
            if ( $check == 0)
            {
                $this -> errors[]='Ввели неправильный логин или пароль';
            }
        }
        return ! $this->hasErrorsAuthorize();
    }

    public function hasErrorsAuthorize() : bool{
        return ! empty($this->errors);
    }
    
    public function getErrorsAuthorize(): array{
        return $this->errors; 
    }

    public function CheckUser($login,$password)
    {
        $count_users = Database::queryOne("
        SELECT COUNT(*) 
        FROM users 
        WHERE `login` = '" . $login . "' 
        AND `password` = '" . sha1($password) . "';");
        return $count_users['COUNT(*)'];
    }

    public function getUserId($login){
        $user_id = Database::queryOne("
        SELECT `id`
        FROM users 
        WHERE `login` = '" . $login . "';");
        return $user_id['id'];
    }
}