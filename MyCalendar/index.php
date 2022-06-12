<?php

include 'init.php';

use App\Models\Form;

if (!empty($_SESSION['is_authorized']))
{
    $_SESSION['session'] = session_id();
    $_SESSION['session_ip'] = getenv('REMOTE_ADDR');
    Form::updateTasksStatus($_SESSION['user_id']);
    
    if (!empty($_SESSION['session_hits']))
    {
        $_SESSION['session_hits']++;
        Form::updateHits($_SESSION['session']);
    }
    else
    {
        $_SESSION['session_hits'] = 1;
        Form::insertSession($_SESSION['session'],$_SESSION['session_ip'], $_SESSION['user_id']);
    }
    
    if (!empty($_SESSION['success']))
    {
        $success = true;
        $_SESSION['success'] = null;
    }
    
    if ($_POST)
    {
        if (!empty($_POST['selected']))
		{
            $_SESSION['task_id'] = $_POST['selected'][0];
			$_SESSION['last_action_time'] = time();
            header('Location: taskUpdate.php');
		}
        if (isset($_POST['logout']))
		{
			session_destroy();
			header('Location: login.php');
			exit;
		}
        $form = new Form($_POST);
        $form->user_id = $_SESSION['user_id'];
        if ($form->validate()) 
        {
            $form->save();
            $_SESSION['success'] = 1;
            $_SESSION['last_action_time'] = time();
            header('Location: index.php');
            exit;
        }
    }
    else
    {
        $form = new Form;
    }
    if (($_SESSION['last_action_time'] + 600) < time())
    {
        session_destroy();
        header('Location: login.php');
        exit;
    }
    if($_GET)
    {
        if(!empty($_GET['sorting_date']))
        {
            $items = Form::loadAll($_SESSION['user_id'], $_GET['sorting_list'], $_GET['sorting_date']);
        }
        else
        {
            $items = Form::loadAll($_SESSION['user_id'], $_GET['sorting_list']);
            echo($form->sort);
        }
    }
    else
    {
        $items = Form::loadAll($_SESSION['user_id']);
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
    <link rel="stylesheet" href="css/style.css" type="text/css"/>
    <title>Мой календарь</title>
</head>
<body>
    <header class="page-header">
        <h1 class="page-header-title">Мой календарь</h1>
    </header>
    <main class="main-content">
        <div class="new-task-and-profile-block">
            <div class="new-task-block">
                <?php if (!empty($success)): ?>
                    <div class="new-task-succsess-block">
                        <p class="new-task-succsess-p">Задача добавлена!</p>
                        <a href="index.php" class="new-task-succsess-make-another-task">Добавить еще</a>
                    </div>
	            <?php else: ?>
                <h2 class="new-task-title">Добавить задачу</h2>
                <div class="new-task-errors">
                    <?php if ($form->hasErrors()): ?>
                        <p>Вы допустили ошибки:</p>
                        <ul>
                            <?php foreach ($form->getErrors() as $error): ?>
                            <li><?= e($error)?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif ?>
                </div>
                <form action="" method="POST" class="new-task-form">
                    <div class="new-task-form-input-topic-block">
                        <label class="new-task-form-label">Тема</label>
                        <input type="text" name ="topic" value="<?=e($form->topic) ?>" class="new-task-form-input">
                    </div>
                    <div class="new-task-form-select-type-block">
                        <label class="new-task-form-label">Тип</label>
                        <select name="type" class="new-task-form-select">
                            <?php foreach (Form::$types as $type): ?>
                                <option <?= $form->type === $type ? 'selected' : '' ?>><?= e($type) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="new-task-form-input-place-block">
                        <label class="new-task-form-label">Место</label>
                        <input type="text" name ="place" value="<?=e($form->place) ?>" class="new-task-form-input">
                    </div>
                    <div class="new-task-form-datetime-block">
                        <label class="new-task-form-label">Дата и время</label>
                        <div class="new-task-form-datetime-inputs">
                            <input type="date" name ="date" value="<?=e($form->date) ?>" class="new-task-form-input-date">
                            <input type="time" name ="time" value="<?=e($form->time) ?>" class="new-task-form-input-time">
                        </div>
                    </div>
                    <div class="new-task-form-select-duration-block">
                        <label class="new-task-form-label">Длительность</label>
                        <select name="duration" class="new-task-form-select">
                            <?php foreach (Form::$durations as $duration): ?>
                                <option <?= $form->duration === $duration ? 'selected' : '' ?>><?= e($duration) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="new-task-form-textarea-block">
                        <label class="new-task-form-label">Комментарий</label>
                        <textarea class="new-task-form-textarea" name="comment" cols="30" rows="5" maxlength="250" placeholder="Максимальная длина 250 символов" value="<?=e($form->comment) ?>"><?=e($form->comment) ?></textarea>
                    </div>
                    <div class="new-task-form-button-block">
                        <button type="submit" class="new-task-form-button">ДОБАВИТЬ</button>
                    </div>
                </form>
                <?php endif?>
            </div>
            <div class="profile-block">
                <h2 class="profile-title">Пользователь</h2>
                <p class="profile-p-user-name"><?=e(Form::getUserName($_SESSION['user_id'])) ?></p>
                <div class="profile-statistics-block">
                    <h2 class="profile-statistics-title">Статистика:</h2>
                    <ul class="profile-statistics-ul">
                        <li class="profile-statistics-li">Количество задач: <b><?=e(Form::getStat($_SESSION['user_id'])) ?></b></li>
                        <li class="profile-statistics-li">Текущих задач: <b><?=e(Form::getStat($_SESSION['user_id'],'Текущая')) ?></b></li>
                        <li class="profile-statistics-li">Выполненных задач: <b><?=e(Form::getStat($_SESSION['user_id'], 'Выполненная')) ?></b></li>
                        <li class="profile-statistics-li">Просроченных задач: <b><?=e(Form::getStat($_SESSION['user_id'], 'Просроченная')) ?></b></li>
                    </ul>
                </div>
                <div class="profile-info-block">
                    <h2 class="profile-info-title">Информация о пользователе: </h2>
                    <ul class="profile-info-ul">
                        <li class="profile-info-li">Логин: <b><?=e(Form::getUserInfo($_SESSION['user_id'], 'login')) ?></b></li>
                        <li class="profile-info-li">Электронный адрес: <b><?=e(Form::getUserInfo($_SESSION['user_id'], 'email')) ?></b></li>
                        <li class="profile-info-li">Дата регистрации: <b><?=e(Form::getUserInfo($_SESSION['user_id'], 'registered_at')) ?></b></li>
                    </ul>
                </div>
                <div class="profile-buttons">
                    <a href="userUpdate.php"><button type="button" class="profile-edit-button">РЕДАКТИРОВАТЬ</button></a>
                    <form action="" method="POST" class="profile-button-form-logout">
                        <button type="submit" class="profile-exit-button" name="logout">ВЫЙТИ</button>
                    </form>               
                </div>
            </div>
        </div>
        <div class="tasks-list-block">
            <h2 class="tasks-list-title">Список задач</h2>
            <form action="" method="GET" class="tasks-list-sorting-block">
                <div class="tasks-list-sorting-left">
                    <select name="sorting_list" class="tasks-list-sorting-select">
                            <?php foreach (Form::$sorting as $sort): ?>
                                <option <?php
                                    if(!empty($_GET['sorting_list']))
                                    {
                                        echo($_GET['sorting_list'] === $sort ? 'selected' : '');
                                    }
                                    else
                                    {
                                        echo($form->sort === $sort ? 'selected' : '');
                                    }
                                    ?>><?= e($sort) ?></option>
                            <?php endforeach ?>
                        </select>
                    <input type="date" name ="sorting_date" value="<?php
                        if(!empty($_GET['sorting_date']))
                        {
                            echo($_GET['sorting_date']); 
                        }
                        else
                        {
                            e($form->sorting_date);
                        }
                        ?>" class="tasks-list-sorting-date">
                </div>
                <div class="tasks-list-sorting-button-block">
                    <button type="submit" class="tasks-list-sorting-button">СОРТИРОВАТЬ</button>
                </div>
            </form>
            <div class="tasks-list-table-block">
                <form action="" method="POST" class="tasks-list-table-form">
                    <table class="tasks-list-table" border="2">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Тип</th>
                                <th>Задача</th>
                                <th>Место</th>
                                <th>Дата и время</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <th><input type="radio" name="selected[]" value="<?= e($item['id']) ?>" class="tasks-list-table-selected-checkbox"></th>
                                <td><?= e($item['type']) ?></td>
                                <td><?= e($item['topic']) ?></td>
                                <td><?= e($item['place']) ?></td>
                                <td><?= e($item['date']) ?> <?= e($item['time']) ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                    <div class="tasks-list-table-buttons-block">
                        <button type="submit" class="tasks-list-table-button">РЕДАКТИРОВАТЬ</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <footer class="page-footer">
        <div class="copyright-note">
            Дондоков Бато, 2022
        </div>
    </footer>
</body>
</html>