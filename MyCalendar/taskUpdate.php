<?php

include 'init.php';

use App\Models\TaskUpdate;

if (!empty($_SESSION['is_authorized']))
{
    if (!empty($_SESSION['update_success']))
    {
        $update_success = true;
        $_SESSION['update_success'] = null;
    }
    
    if ($_POST)
    {
        $taskUpdate = new TaskUpdate($_POST, $_SESSION['user_id'], $_SESSION['task_id']);
        if ($taskUpdate->validateTaskUpdate()) 
        {	
            $taskUpdate->saveTaskUpdate($_SESSION['user_id'], $_SESSION['task_id']);
            $_SESSION['update_success'] = 1;
            $_SESSION['last_action_time'] = time();
            header('Location: taskUpdate.php');
            exit;
        }
    }
    else
    {
        $taskUpdate = new TaskUpdate(NULL, $_SESSION['user_id'], $_SESSION['task_id']);
    }
}
else
{
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style-update-task.css" type="text/css"/>
    <title>Редактирование профиля</title>
</head>
<body>
    <header class="page-header">
        <h1 class="page-header-title">Мой календарь</h1>
    </header>
    <main class="main-content">
        <div class="taskUpdate-block">
            <h2 class="taskUpdate-title">Редактирвание задачи</h2>
            <a href="index.php" class="taskUpdate-go-back">Вернуться на главную</a>
            <?php if (!empty($update_success)): ?>
                <div class="taskUpdate-succsess-block">
                    <p class="taskUpdate-succsess-p">Задача обновлена!</p>
                </div>
            <?php else: ?>
            <div class="taskUpdate-errors">
                <?php if ($taskUpdate->hasErrorsTaskUpdate()): ?>
                    <p>Вы допустили ошибки:</p>
                    <ul>
                        <?php foreach ($taskUpdate->getErrorsTaskUpdate() as $error): ?>
                        <li><?= e($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </div>
            <form action="" method="POST" class="taskUpdate-form">
                <div class="taskUpdate-form-input-topic-block">
                    <label class="taskUpdate-form-label">Тема</label>
                    <input type="text" name ="topic" value="<?=e($taskUpdate->topic) ?>" class="taskUpdate-form-input">
                </div>
                <div class="taskUpdate-form-select-type-block">
                    <label class="taskUpdate-form-label">Тип</label>
                    <select name="type" class="taskUpdate-form-select">
                        <?php foreach (TaskUpdate::$types as $type): ?>
                            <option <?=$taskUpdate->type === $type ? 'selected' : '' ?> ><?= e($type) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="taskUpdate-form-input-place-block">
                    <label class="taskUpdate-form-label">Место</label>
                    <input type="text" name ="place" value="<?=e($taskUpdate->place) ?>" class="taskUpdate-form-input">
                </div>
                <div class="taskUpdate-form-datetime-block">
                    <label class="taskUpdate-form-label">Дата и время</label>
                    <div class="taskUpdate-form-datetime-inputs">
                        <input type="date" name ="date" value="<?=e($taskUpdate->date) ?>" class="taskUpdate-form-input-date">
                        <input type="time" name ="time" value="<?=e($taskUpdate->time) ?>" class="taskUpdate-form-input-time">
                    </div>
                </div>
                <div class="taskUpdate-form-select-duration-block">
                    <label class="taskUpdate-form-label">Длительность</label>
                    <select name="duration" class="taskUpdate-form-select">
                        <?php foreach (TaskUpdate::$durations as $duration): ?>
                            <option <?=$taskUpdate->duration === $duration ? 'selected' : '' ?> ><?= e($duration) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="taskUpdate-form-textarea-block">
                    <label class="taskUpdate-form-label">Комментарий</label>
                    <textarea class="taskUpdate-form-textarea" name="comment" cols="30" rows="5" maxlength="250" placeholder="Максимальная длина 250 символов" value="<?=e($taskUpdate->comment) ?>"><?=e($taskUpdate->comment) ?></textarea>
                </div>
                <div class="taskUpdate-form-select-status-block">
                    <label class="taskUpdate-form-label">Статус</label>
                    <select name="status" class="taskUpdate-form-select">
                        <?php foreach (TaskUpdate::$statuses as $status): ?>
                            <option <?=$taskUpdate->status === $status ? 'selected' : '' ?> ><?= e($status) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="taskUpdate-form-button-block">
                    <button type="submit" class="taskUpdate-form-button">СОХРАНИТЬ</button>
                </div>
            </form>
            <?php endif?>
        </div>
    </main>
    <footer class="page-footer">
        <div class="copyright-note">
            Дондоков Бато, 2022
        </div>
    </footer>
</body>
</html>