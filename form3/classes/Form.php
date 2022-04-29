<?php

class Form{

    const SEPARATOR = "||";
    protected static $datafile = 'data/data.txt';

    public string $id = ' ';
    public string $ip = ' ';
    public int $date = 0;
    public string $name = ' ';
    public string $lastname =' ';
    public string $email = ' ';
    public string $phone = ' ';
    public string $topic = ' ';
    public string $payment = ' ';
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
        'Реклама и маркейтинг',
    ];

    public function __construct(array $data=[]){
        
        $this->id = uniqid();
        $this->ip = getenv('REMOTE_ADDR');
        $this->date = time();        
        $this->fill($data);
        $this->status = 'exist';
    }

    public function fill(array $data = [])
    {
        if ($data)
        {
            if(isset($data['id']))
            {
                $this->id = $data['id'];
            }
            if(isset($data['date']))
            {
                $this->date = $data['date'];
            }
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
        $this->ensureDataDir();       
        file_put_contents(static::$datafile, $this->toString() . "\n" , FILE_APPEND);
    }

    public static function deleteByIds(array $ids = [])
    {
        $items = static::loadAll();
                
        foreach($ids as $id)
        {
            foreach($items as $index => $item)
            {
                if ($id === $item->id)
                {                   
                    array_splice($items, $index, 1); 
                    break;
                }
            }
        }          
        static::saveAll($items);
    }

    public static function saveAll(array $items = [])
    {
        $lines = [];
        foreach ($items as $item)
        {
            $lines [] = $item->toString();
        }
        file_put_contents(static::$datafile, implode("\n", $lines) . "\n");   
    }

    public static function loadAll() : array
    {
        $items = [];   
        if (file_exists(static::$datafile))
        {            
            $contents = file_get_contents(static::$datafile);
            $lines = explode ("\n", trim($contents));

            foreach($lines as $line)
            {
                $cols = explode(static::SEPARATOR, trim($line));
                $item = new static;
                $item->fill([
                    'id' => $cols[0],
                    'data' => $cols[1],
                    'ip' => $cols[2],
                    'name' => $cols[3],
                    'lastname' => $cols[4],
                    'email' => $cols[5],
                    'phone' => $cols[6],
                    'topic' => $cols[7],
                    'payment' => $cols[8],
                    'confirm' => $cols[9],
                    'status' => $cols[10]
                ]);
                if($cols[10] != 'deleted'){
                    $items[] = $item;
                }
            }
        }      
        return $items;
    }
    protected function ensureDataDir(){    
        $dir = dirname(static::$datafile);        
        if (!file_exists($dir))
        {
            mkdir($dir, 0777);
        }
    }

    protected function toString()
    {      
        return implode(static::SEPARATOR, [
            $this->id,
            $this->date,
            $this->ip,
            $this->name,
            $this->lastname,
            $this->email,
            $this->phone,
            $this->topic,
            $this->payment,
            (int)$this->confirm,
            $this->status,
        ]);
    }
}
