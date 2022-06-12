<?php

namespace App\Models;

use App\Database\Database;

date_default_timezone_set('Asia/Irkutsk');

class Form{

    public ?int $user_id;
    public ?string $topic = '';
    public ?string $type = '';
    public ?string $place = '';
    public ?string $date = '';
    public ?string $time = '';
    public ?string $duration = '';
    public ?string $comment = '';
    public ?string $sort = '';
    public ?string $sorting_date = '';

    protected $errors = [];

    public static $types = [
        1 => 'Встреча',
        2 => 'Звонок',
        3 => 'Совещание',
        4 => 'Дело',
    ];
  
    public static $durations = [
        1 => '1 час', 
        2 => '2 часа', 
        3 => '3 часа', 
        4 => '4 часа', 
        5 => '5 часов', 
        6 => '6 часов', 
        7 => '7 часов', 
        8 => '8 часов', 
        9 => '9 часов', 
        10 => '10 часов',        
        11 => '11 часов', 
        12 => '12 часов', 
        13 => '13 часов', 
        14 => '14 часов', 
        15 => '15 часов', 
        16 => '16 часов', 
        17 => '17 часов', 
        18 => '18 часов', 
        19 => '19 часов', 
        20 => '20 часов',       
        21 => '21 час', 
        22 => '22 часа', 
        23 => '23 часа', 
        24 => '24 часа',
    ];

    public static $sorting = [
        1 => 'Текущие задачи',
        2 => 'Просроченные задачи',
        3 => 'Выполненные задачи',
        4 => 'Поиск по дате',
    ];

    public function __construct(array $data=[]){
        $this->fill($data);
    }

    public static function updateTasksStatus($user_id){
        $sql = Database::prepare('
        UPDATE `tasks`
        SET `status` = \'Просроченная\'
        WHERE `date` < CURRENT_DATE() AND `user_id` = :user_id AND `status` != \'Выполненная\';');
        $sql->execute([
            'user_id'=> $user_id, 
        ]);
    }

    public function fill(array $data = [])
    {
        if ($data)
        {          
            $this->topic = $data['topic'] ?? '';
            $this->type = $data['type'] ?? '';
            $this->place = $data['place'] ?? '';
            $this->date = $data['date'] ?? '';
            $this->time = $data['time'] ?? '';
            $this->duration = $data['duration'] ?? '';
            $this->comment = $data['comment'] ?? '';
        } 
    }

    public function validate() : bool{
        
        $this -> errors = [];
        //проверка на заполненность полей
        if (!$this -> topic)
        {
            $this -> errors[]='Поле тема не заполнено';
        }
        if (!$this -> type)
        {
            $this -> errors[]= 'Поле тип не заполнено';
        } 
        if (!$this -> place)
        {
            $this -> errors[]= 'Поле место не заполнено';
        }
        if (!$this -> date || !$this -> time)
        {
            $this -> errors[]= 'Поле дата и время не заполнено';
        }
        if (!$this -> duration)
        {
            $this -> errors[]= 'Поле длительность не заполнено';
        }
        //проверка на дату (нельзя добавить задачу если дата задачи уже прошла)
        $datetime = date("Y-m-d H:i", strtotime($this->date . ' ' . $this->time)); 
        if ($datetime < date('Y-m-d H:i'))
        {
            $this -> errors[]= 'Нельзя создать задачу с прошедшей датой';
        }

        return ! $this->hasErrors();
    }

    public function hasErrors() : bool{
        return ! empty($this->errors);
    }
    
    public function getErrors(): array{
        return $this->errors; 
    }

    public function save(){   
        $type_id = array_search ($this->type, Form::$types);
        $duration_id = array_search ($this->duration, Form::$durations);
        $sql = Database::prepare('INSERT INTO `tasks` (`user_id`, `topic`, `type_id`, `place`, `date`, `time`, `duration_id`, `comment`, `created_at`) 
        VALUES (:user_id, :topic, :type_id, :place, :date, :time, :duration_id, :comment, NOW());');
        $sql->execute([
            'user_id'=>$this->user_id, 
            'topic'=>$this->topic, 
            'type_id'=>$type_id, 
            'place'=>$this->place, 
            'date'=>$this->date, 
            'time'=>$this->time, 
            'duration_id'=>$duration_id, 
            'comment'=>$this->comment,
        ]);
    }

    public static function insertSession($session, $session_ip, $user_id)
    {
        $sql = Database::prepare('
        INSERT INTO `sessions` (`session`, `user_id`, `ip`, `created_at`) 
        VALUES (:session, :user_id, :ip, NOW())');
        $sql->execute([
            'session'=> $session,
            'user_id'=> $user_id,
            'ip'=> $session_ip,
        ]);
    }

    public static function updateHits($session)
    {
        $sql = Database::prepare('
        UPDATE `sessions` 
        SET `updated_at` = NOW(), `hits` = `hits` + 1 
        WHERE `session` = :session');
        $sql->execute([
            'session'=> $session, 
        ]);
    }

    public static function getUserName($user_id){
        $result = Database::queryOne("
        SELECT CONCAT_WS(' ', `last_name`, `name`) AS `user_name`
        FROM `users` 
        WHERE `id` = " . $user_id . ";");
        return $result['user_name'];
    }

    public static function getStat($user_id, $status = null){
        if (!$status)
        {
            $result = Database::queryOne("
            SELECT COUNT(*)
            FROM `tasks`
            WHERE `user_id` = " . $user_id . " ;");
            return $result['COUNT(*)'];
        }
        else
        {
            $result = Database::queryOne("
            SELECT COUNT(*)
            FROM `tasks` 
            WHERE `user_id` = " . $user_id . " AND `status` = '" . $status . "';");
            return $result['COUNT(*)'];
        }
    }

    public static function getUserInfo($user_id, $column){
        $result = Database::queryOne("
        SELECT " . $column . "
        FROM `users` 
        WHERE `id` = " . $user_id . ";");
        return $result[$column];
    }

    public static function loadAll($user_id, $sort = null, $date = null) : array
    {
        if($sort === 'Текущие задачи')
        {
            $status = 'Текущая';
            return Database::query(
            'SELECT t.`id`, 
            tp.`name` AS `type`, 
            t.`topic`, 
            t.`place`, 
            t.`date`,
            t.`time` 
            FROM `tasks` AS t, `types` AS tp 
            WHERE t.`type_id` = tp.`id` AND `user_id` = ' . $user_id . ' AND `status` = \'' . $status . '\'
            ORDER BY `id`;');
        }
        if($sort === 'Выполненные задачи')
        {
            $status = 'Выполненная';
            return Database::query(
            'SELECT t.`id`, 
            tp.`name` AS `type`, 
            t.`topic`, 
            t.`place`, 
            t.`date`,
            t.`time` 
            FROM `tasks` AS t, `types` AS tp 
            WHERE t.`type_id` = tp.`id` AND `user_id` = ' . $user_id . ' AND `status` = \'' . $status . '\'
            ORDER BY `id`;');
        }
        elseif($sort === 'Просроченные задачи')
        {
            $status = 'Просроченная';
            return Database::query(
            'SELECT t.`id`, 
            tp.`name` AS `type`, 
            t.`topic`, 
            t.`place`, 
            t.`date`,
            t.`time` 
            FROM `tasks` AS t, `types` AS tp 
            WHERE t.`type_id` = tp.`id` AND `user_id` = ' . $user_id . ' AND `status` = \'' . $status . '\'
            ORDER BY `id`;');
        }
        elseif($sort === 'Поиск по дате' && $date)
        {
            return Database::query(
            'SELECT t.`id`, 
            tp.`name` AS `type`, 
            t.`topic`, 
            t.`place`, 
            t.`date`,
            t.`time` 
            FROM `tasks` AS t, `types` AS tp 
            WHERE t.`type_id` = tp.`id` AND `user_id` = ' . $user_id . ' AND `date` = \'' . $date . '\'
            ORDER BY `id`;');
        }
        else
        {
            return Database::query(
            'SELECT t.`id`, 
            tp.`name` AS `type`, 
            t.`topic`, 
            t.`place`, 
            t.`date`,
            t.`time` 
            FROM `tasks` AS t, `types` AS tp 
            WHERE t.`type_id` = tp.`id` AND `user_id` = ' . $user_id . ' 
            ORDER BY `id`;');
        }
    }
}