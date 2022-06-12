<?php

namespace App\Models;

use App\Database\Database;

class UserUpdate{
    
    public ?int $user_id;
    public ?string $name = '';
    public ?string $last_name = '';
    public ?string $email = '';
    public ?string $current_login = '';
    public ?string $login = '';
    public ?string $current_password = '';
    public ?string $new_password = '';
    public ?string $new_password_confirm = '';

    public $currentUserData = [];
    protected $UpdatedUserData = [];
    protected $errors = [];

    public function __construct(array $data = null, $user_id = null){
        if(!$data)
        {
            $this->loadCurrentUserData($user_id);
            $this->fillUserCurrentData();//
        }
        else{
            $this->loadCurrentUserData($user_id);
            $this->fillUserCurrentData();//
            $this->fillUserUpdate($data);
            $this->CheckNewValue();
        }  
    }

    public function fillUserCurrentData()//
    {
        if ($this->currentUserData)
        {      
            $this->name = $this->currentUserData['name'] ?? '';
            $this->last_name = $this->currentUserData['last_name'] ?? '';
            $this->email = $this->currentUserData['email'] ?? '';
            $this->login = $this->currentUserData['login'] ?? '';
            $this->current_login = $this->currentUserData['login'] ?? '';
        } 
    }

    public function fillUserUpdate(array $data = [])
    {
        if ($data)
        {      
            $this->name = trim($data['name']) ?? '';
            $this->last_name = trim($data['last_name']) ?? '';
            $this->email = trim($data['email']) ?? '';
            $this->login = trim($data['login']) ?? '';
            $this->current_password = trim($data['current_password']) ?? '';
            $this->new_password = trim($data['new_password']) ?? '';
            $this->new_password_confirm = trim($data['new_password_confirm']) ?? '';
        } 
    }

    public function validate_UserUpdate() : bool{
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
        if (!$this -> current_password)
        {
            $this -> errors[]= 'Поле текущий пароль не заполнено';
        }
        if (!$this -> new_password && $this -> new_password_confirm)
        {
            $this -> errors[]= 'Поле новый пароль не заполнено';
        }
        if (!$this -> new_password_confirm && $this -> new_password)
        {
            $this -> errors[]= 'Поле подтверждения пароля не заполнено';
        }
                
        // Проверка паролей
        if ($this -> current_login && $this -> current_password)
        {
            $check = $this -> CheckUser($this -> current_login, $this -> current_password);
            if ( $check == 0)
            {
                $this -> errors[]='Вы ввели неверный текущий пароль';
            }
        }
        if ($this -> new_password != $this -> new_password_confirm)
        {
            $this -> errors[]='Новые пароли не совпадают';
        }
        if ($this -> current_password === $this -> new_password)
        {
            $this -> errors[]='Текущий и новый пароли совпадают';
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
        if ($this -> login && !preg_match('~^[A-Za-z][A-Za-z\d]{2,24}$~',$this -> login))
        {
            //Регулярка Логин (с ограничением 3-25 символов, которыми могут быть латинские буквы и цифры, первый символ обязательно буква)
            $this -> errors[]='Введен некоретный логин';
        }
        if ($this -> new_password && !preg_match('~^[A-Za-z\d]{8,}$~',$this -> new_password))
        {
            //Регулярка Пароль (строчные и прописные латинские буквы, цифры, минимальная длина - 8 символов)
            $this -> errors[]='Введен некоретный новый пароль';
        }

        //Проверка уникальности логина
        $qty = $this->checkUniqeLogin();
        if($this -> login && $this -> login != $this -> current_login && $qty != 0)
        {
            $this -> errors[]='Пользователь с таким логином уже существует';
        }
        return ! $this->hasErrorsUserUpdate();
    }

    public function hasErrorsUserUpdate() : bool{
        return ! empty($this->errors);
    }
    
    public function getErrorsUserUpdate(): array{
        return $this->errors; 
    }

    public function CheckNewValue() : array
    {
        $this->UpdatedUserData = [];
        if ($this -> name != $this -> currentUserData['name'])
        {
            $this -> UpdatedUserData['name'] = $this -> name;
        }
        if ($this -> last_name != $this -> currentUserData['last_name'])
        {
            $this -> UpdatedUserData['last_name'] = $this -> last_name;
        }
        if ($this -> email != $this -> currentUserData['email'])
        {
            $this -> UpdatedUserData['email'] = $this -> email;
        }
        if ($this -> login != $this -> currentUserData['login'])
        {
            $this -> UpdatedUserData['login'] = $this -> login;
        }
        if ($this -> new_password && sha1($this -> new_password) != $this -> currentUserData['password'])
        {
            $this -> UpdatedUserData['password'] = sha1($this -> new_password);
        }
        return $this->UpdatedUserData;
    }

    public function saveUserUpdate($user_id){ 
        if($this->UpdatedUserData)
        {
            foreach ($this->UpdatedUserData as $data){
                $sql = Database::prepare('UPDATE `users` SET `' . array_search($data, $this->UpdatedUserData) . '` = :data, `updated_at` = NOW() WHERE `id` = :user_id;');
                $sql->execute([
                    'data'=>$data,
                    'user_id'=>$user_id,
                ]);
            }
        }
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

    public function loadCurrentUserData($user_id){
        $this ->currentUserData = [];
        $columns = Database::queryOne('SELECT * FROM `users` WHERE `id` = ' . $user_id . ';');
        foreach ($columns as $column){
            $this ->currentUserData [array_search($column, $columns)] = $column;
        }
    }

    public function checkUniqeLogin(){
        $sql = Database::queryOne("
        SELECT COUNT(`login`) as `qty` 
        FROM `users`
         WHERE `login` = '" . $this->login . "';");
        return $sql['qty'];
    }
}
