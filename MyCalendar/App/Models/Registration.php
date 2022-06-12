<?php

namespace App\Models;

use App\Database\Database;

class Registration{
    
    public ?int $user_id;
    public ?string $name = '';
    public ?string $last_name = '';
    public ?string $email = '';
    public ?string $login = '';
    public ?string $password = '';
    public ?string $password_confirm = '';

    protected $errors = [];

    public function __construct(array $data = []){
        $this->fillRegistration($data);
    }

    public function fillRegistration(array $data = [])
    {
        if ($data)
        {      
            $this->name = trim($data['name']) ?? '';
            $this->last_name = trim($data['last_name']) ?? '';
            $this->email = trim($data['email']) ?? '';
            $this->login = trim($data['login']) ?? '';
            $this->password = trim($data['password']) ?? '';
            $this->password_confirm = trim($data['password_confirm']) ?? '';
        } 
    }

    public function validate_registration() : bool{
        $this -> errors = [];

        // Проверка заполнености полей
        if (!$this -> name)
        {
            $this -> errors[]='Поле имя не заполнено';
        }
        if (!$this -> last_name)
        {
            $this -> errors[]= 'Поле фамилия не заполнено';
        }
        if (!$this -> email)
        {
            $this -> errors[]= 'Поле электронный адрес не заполнено';
        }      
        if (!$this -> login)
        {
            $this -> errors[]='Поле логин не заполнено';
        }
        if (!$this -> password)
        {
            $this -> errors[]= 'Поле пароль не заполнено';
        }
        if (!$this -> password_confirm)
        {
            $this -> errors[]= 'Поле подтверждения пароля не заполнено';
        }
                
        // Проверка паролей
        if ($this -> password != $this -> password_confirm)
        {
            $this -> errors[]='Введенные пароли не совпадают';
        }

        //Регулярки для проверки коректности данных
        if ($this -> name && !preg_match('~^[А-ЯЁ][а-яё]+$~u',$this -> name))
        {
            //Регулярка Имя или Фамилия (кириллица, только буквы, первая заглавная)
            $this -> errors[]='Введено некоретное имя';
        }
        if ($this -> last_name && !preg_match('~^[А-ЯЁ][а-яё]+$~u',$this -> last_name))
        {
            //Регулярка Фамилия (кириллица, только букв, первая заглавная)
            $this -> errors[]='Введена некоретная фамилия';
        }
        if ($this -> email && !preg_match('~^[a-z\d\-_\.]+@[a-z\d\-]+\.[a-z]{2,65}$~',$this -> email))
        {
            //Регулярка email
            $this -> errors[]='Введен некоретный электронный адрес';
        }
        if ($this -> login && !preg_match('~^[A-Z][A-Za-z\d]{2,24}$~',$this -> login))
        {
            //Регулярка Логин (с ограничением 3-25 символов, которыми могут быть латинские буквы и цифры, первый символ обязательно буква)
            $this -> errors[]='Введен некоретный логин';
        }
        if ($this -> password && !preg_match('~^[A-Za-z\d]{8,}$~',$this -> password))
        {
            //Регулярка Пароль (строчные и прописные латинские буквы, цифры, минимальная длина - 8 символов)
            $this -> errors[]='Введен некоретный пароль';
        }

        //Проверка уникальности логина
        $qty = $this->checkUniqeLogin();
        if($this -> login && $qty != 0)
        {
            $this -> errors[]='Пользователь с таким логином уже существует';
        }
        return ! $this->hasErrorsRegistration();
    }

    public function hasErrorsRegistration() : bool{
        return ! empty($this->errors);
    }
    
    public function getErrorsRegistration(): array{
        return $this->errors; 
    }

    public function saveUser(){ 
        $sql = Database::prepare('INSERT INTO `users` (`name`, `last_name`, `login`, `password`, `email`, `registered_at`) 
        VALUES (:name, :last_name, :login, :password, :email, NOW());');
        $sql->execute([
            'name'=>$this->name, 
            'last_name'=>$this->last_name, 
            'login'=>$this->login, 
            'password'=>sha1($this->password), 
            'email'=>$this->email, 
        ]);
    }

    public function getUserId($login){
        $user_id = Database::queryOne("
        SELECT `id`
        FROM users 
        WHERE `login` = '" . $login . "';");
        return $user_id['id'];
    }

    public function checkUniqeLogin(){
        $sql = Database::queryOne("
        SELECT COUNT(`login`) as `qty` 
        FROM `users`
         WHERE `login` = '" . $this->login . "';");
        return $sql['qty'];
    }
}
