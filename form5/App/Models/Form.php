<?php

namespace App\Models;

use App\Database\Database;
use PDO;

class Form{

    const SEPARATOR = "||";

    public ?int $id;
    public ?string $ip;
    public ?int $date;
    public ?string $name = '';
    public ?string $lastname = '';
    public ?string $email = '';
    public ?string $phone = '';
    public ?string $topic = '';
    public ?string $payment = '';
    public bool $confirm = false;

    public ?string $admin_login = '';
    public ?string $admin_password = '';

    protected $errors = [];
  
    public static $payments = [
        'WebMoney',
        'Яндекс.Деньги',
        'PayPal',
        'Кредитная карта',
    ];
  
    public static $topics = [
        'Бизнес',
        'Технологии',
        'Реклама и маркетинг',
    ];

    public function __construct(array $data=[]){
        
        $this->ip = getenv('REMOTE_ADDR');       
        $this->fill($data);
        $this->fillAuthorize($data);
    }

    public function fill(array $data = [])
    {
        if ($data)
        {
            if(isset($data['ip']))
            {
                $this->ip = $data['ip'];
            }           
            $this->name = $data['name'] ?? '';
            $this->lastname = $data['lastname'] ?? '';
            $this->email = $data['email'] ?? '';
            $this->phone = $data['phone'] ?? '';
            $this->topic = $data['topic'] ?? '';
            $this->payment = $data['payment'] ?? '';
            $this->confirm = (bool)($data['confirm'] ?? '');
        } 
    }

    public function fillAuthorize(array $data = [])
    {
        if ($data)
        {        
            $this->admin_login = $data['admin_login'] ?? '';
            $this->admin_password = $data['admin_password'] ?? '';
        } 
    }

    public function validate() : bool{
        
        $this -> errors = [];   
        if (!$this -> name)
        {
            $this -> errors[]='Поле имя не заполнено';
        }
        if (!$this -> lastname)
        {
            $this -> errors[]= 'Поле фамилия не заполнено';
        } 
        if (!$this -> email)
        {
            $this -> errors[]= 'Поле электронный адресс не заполнено';
        }
        if (!$this -> phone)
        {
        $this -> errors[]= 'Поле телефон не заполнено';
        } 
        return ! $this->hasErrors();
    }

    public function hasErrors() : bool{
        return ! empty($this->errors);
    }
    
    public function getErrors(): array{
        return $this->errors; 
    }

    public function save()
    {
 
        $topic_id = Database::getId('topics',$this->topic);
        $payment_id = Database::getId('payments',$this->payment);
        $sql = Database::prepare('INSERT INTO forms (ip, name, lastname, email, phone, topic_id, payment_id, is_confirmed, created_at) 
        VALUES (:ip, :name, :lastname, :email, :phone, :topic_id, :payment_id, :is_confirmed, NOW());');
        $sql->execute([
            'ip'=>$this->ip, 
            'name'=>$this->name, 
            'lastname'=>$this->lastname, 
            'email'=>$this->email, 
            'phone'=>$this->phone, 
            'topic_id'=>$topic_id, 
            'payment_id'=>$payment_id, 
            'is_confirmed'=>(int)$this->confirm,
        ]);
    }

    public static function deleteByIds(array $ids = [])
    {
        if (!$ids) {
            return;
        }
        $ids_placeholder = trim(str_repeat('?,', count($ids)),',');
        $sql = Database::prepare('UPDATE forms SET deleted_at = NOW() WHERE id IN ('. $ids_placeholder .') AND deleted_at is NULL;');
        $sql->execute($ids);
    }

    public static function loadAll() : array
    {
        return Database::query('SELECT * FROM forms WHERE deleted_at is NULL LIMIT 10;',static::class);
    }

    public static function loadStats(): array
    {
        $stats = [];
        $count_session = Database::query('SELECT COUNT(`session`) FROM stats;')->fetch(PDO::FETCH_ASSOC);
        $stats['session'] = $count_session['COUNT(`session`)'];

        $count_ip = Database::query('SELECT COUNT(DISTINCT `ip`) FROM stats;')->fetch(PDO::FETCH_ASSOC);
        $stats['ip'] = $count_ip['COUNT(DISTINCT `ip`)'];

        $count_hits = Database::query('SELECT SUM(`hits`) FROM stats;')->fetch(PDO::FETCH_ASSOC);
        $stats['hits'] = $count_hits['SUM(`hits`)'];

        return $stats;
    }

    public static function insertStats($session, $session_ip, $session_hits)
    {
        $sql = Database::prepare('
        INSERT INTO stats (ip, session, hits, created_at) 
        VALUES (:ip, :session, :hits, NOW())');
        $sql->execute([
            'ip'=> $session_ip, 
            'session'=> $session, 
            'hits'=> $session_hits, 
        ]);
    }

    public static function updateHits($session)
    {
        $sql = Database::prepare('
        UPDATE stats 
        SET updated_at = NOW(), hits = hits + 1 
        WHERE session = :session');
        $sql->execute([
            'session'=> $session, 
        ]);
    }

    public function validate_authorize() : bool{
        
        $this -> errors = [];   
        if (!$this -> admin_login)
        {
            $this -> errors[]='Поле логин не заполнено';
        }
        if (!$this -> admin_password)
        {
            $this -> errors[]= 'Поле пароль не заполнено';
        }
        
        if ($this -> admin_login && $this -> admin_password)
        {
            $check = $this -> CheckAdmin($this -> admin_login, $this -> admin_password);
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

    public function CheckAdmin($login,$password)
    {
        $result = [];
        $count_admins = Database::query("
        SELECT COUNT(*) 
        FROM admins 
        WHERE `login` = '" . $login . "' 
        AND `password` = '" . sha1($password) . "';")->fetch(PDO::FETCH_ASSOC);
        $result = $count_admins['COUNT(*)'];
        return $result;
    }

}