<?php

namespace App\Models;

use App\Database\Database;

date_default_timezone_set('Asia/Irkutsk');

class TaskUpdate{
    public ?int $user_id;
    public ?string $topic = '';
    public ?string $type = '';
    public ?string $place = '';
    public ?string $date = '';
    public ?string $time = '';
    public ?string $duration = '';
    public ?string $comment = '';
    public ?string $status = '';

    public $currentTaskData = [];
    protected $UpdatedTaskData = [];
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

    public static $statuses = [
        1 => 'Текущая',
        2 => 'Выполненная',
        3 => 'Просроченная',
    ];

    public function __construct(array $data = null, $user_id = null, $task_id = null){
        if(!$data)
        {
            $this->loadCurrentTaskData($user_id, $task_id);
            $this->fillCurrentTaskData();
        }
        else
        {
            $this->loadCurrentTaskData($user_id, $task_id);
            $this->fillCurrentTaskData();
            $this->fillTaskUpdate($data);
            $this->CheckNewValue();
        }
    }

    public function loadCurrentTaskData($user_id, $task_id){
        $this ->currentTaskData = [];
        $columns = Database::queryOne(
            'SELECT t.`topic`,
            tp.`name` AS `type`,
            t.`place`,
            t.`date`,
            t.`time`,
            d.`name` AS `duration`, 
            t.`comment`,
            t.`status`
            FROM `tasks` AS t, `types` AS tp, `durations` AS d
            WHERE t.`id` = \'' . $task_id . '\' AND t.`user_id` = \'' . $user_id . '\' AND t.`type_id` = tp.`id` AND t.`duration_id` = d.`id`;');
        foreach ($columns as $column){
            $this ->currentTaskData [array_search($column, $columns)] = $column;
        }
    }

    public function fillCurrentTaskData()
    {
        if ($this->currentTaskData)
        {
            $this->topic = $this->currentTaskData['topic'] ?? '';
            $this->type = $this->currentTaskData['type'] ?? '';
            $this->place = $this->currentTaskData['place'] ?? '';
            $this->date = $this->currentTaskData['date'] ?? '';
            $this->time = $this->currentTaskData['time'] ?? '';
            $this->duration = $this->currentTaskData['duration'] ?? '';
            $this->comment = $this->currentTaskData['comment'] ?? '';
            $this->status = $this->currentTaskData['status'] ?? '';
        }
    }

    public function fillTaskUpdate(array $data = [])
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
            $this->status = $data['status'] ?? '';
        }
    }

    public function validateTaskUpdate() : bool{
        
        $this -> errors = [];

        // Проверка заполнености полей  
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

        //проверка на дату (нельзя обновить задачу если дата задачи уже прошла)
        $datetime = date("Y-m-d H:i", strtotime($this->date . ' ' . $this->time)); 
        if ($datetime < date('Y-m-d H:i') && $this->status != 'Выполненная')
        {
            $this -> errors[]= 'Нельзя обновить задачу с прошедшей датой';
        }

        //Проверка если не ввели никаких изменений
        if ($this -> topic === $this->currentTaskData['topic'] 
        && $this -> type === $this->currentTaskData['type']
        && $this -> place === $this->currentTaskData['place']
        && $this -> date === $this->currentTaskData['date']
        && $this -> time === $this->currentTaskData['time']
        && $this -> duration === $this->currentTaskData['duration']
        && $this -> comment === $this->currentTaskData['comment']
        && $this -> status === $this->currentTaskData['status'])
        {
            $this -> errors[]= 'Вы не внесли никаких изменений';
        }
        return ! $this->hasErrorsTaskUpdate();
    }

    public function hasErrorsTaskUpdate() : bool{
        return ! empty($this->errors);
    }

    public function getErrorsTaskUpdate(): array{
        return $this->errors; 
    }

    public function CheckNewValue() : array
    {
        $this->UpdatedTaskData = [];
        if ($this -> topic != $this -> currentTaskData['topic'])
        {
            $this -> UpdatedTaskData['topic'] = $this -> topic;
        }
        if ($this -> type != $this -> currentTaskData['type'])
        {
            $this -> UpdatedTaskData['type_id'] = array_search ($this -> type, Form::$types);
        }
        if ($this -> place != $this -> currentTaskData['place'])
        {
            $this -> UpdatedTaskData['place'] = $this -> place;
        }
        if ($this -> date != $this -> currentTaskData['date'])
        {
            $this -> UpdatedTaskData['date'] = $this -> date;
        }
        if ($this -> duration != $this -> currentTaskData['duration'])
        {
            $this -> UpdatedTaskData['duration_id'] = array_search ($this -> duration, Form::$durations);
        }
        if ($this -> comment != $this -> currentTaskData['comment'])
        {
            $this -> UpdatedTaskData['comment'] = $this -> comment;
        }
        if ($this -> status != $this -> currentTaskData['status'])
        {
            $this -> UpdatedTaskData['status'] = $this -> status;
        }
        return $this->UpdatedTaskData;
    }

    public function saveTaskUpdate($user_id, $task_id){ 
        if($this->UpdatedTaskData)
        {
            foreach ($this->UpdatedTaskData as $data){
                $sql = Database::prepare('UPDATE `tasks` 
                SET `' . array_search($data, $this->UpdatedTaskData) . '` = :data, 
                `updated_at` = NOW() 
                WHERE `id` = :task_id AND `user_id` = :user_id;');
                $sql->execute([
                    'data'=>$data,
                    'task_id'=>$task_id,
                    'user_id'=>$user_id,
                ]);
            }
        }
    }
}