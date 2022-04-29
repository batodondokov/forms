<?php

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
        $sql = Database::prepare('
        UPDATE forms SET deleted_at = NOW() WHERE id IN ('. $ids_placeholder .') AND deleted_at is NULL;');
        $sql->execute($ids);
    }

    public static function loadAll() : array
    {
        return Database::query('SELECT * FROM forms WHERE deleted_at is NULL LIMIT 10;',static::class);
    }
}