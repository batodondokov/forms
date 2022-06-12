<?php

include 'init.php';

use App\Models\UserUpdate;

if (!empty($_SESSION['is_authorized']))
{
    if (!empty($_SESSION['update_success']))
    {
        $update_success = true;
        $_SESSION['update_success'] = null;
    }

    if ($_POST)
    {
        $userUpdate = new UserUpdate($_POST, $_SESSION['user_id']);
        if ($userUpdate->validate_UserUpdate()) 
        {	
            $userUpdate->saveUserUpdate($_SESSION['user_id']);
            $_SESSION['update_success'] = 1;
            $_SESSION['last_action_time'] = time();
            header('Location: userUpdate.php');
            exit;
        }
    }
    else
    {
        $userUpdate = new UserUpdate(NULL, $_SESSION['user_id']);
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
    <link rel="stylesheet" href="css/style-update-user.css" type="text/css"/>
    <title>Редактирование профиля</title>
</head>
<body>
    <header class="page-header">
        <h1 class="page-header-title">Мой календарь</h1>
    </header>
    <main class="main-content">
        <div class="userUpdate-block">
            <h2 class="userUpdate-title">Редактирвание профиля</h2>
            <a href="index.php" class="userUpdate-go-back">Вернуться на главную</a>
            <?php if (!empty($update_success)): ?>
                <div class="userUpdate-succsess-block">
                    <p class="userUpdate-succsess-p">Профиль обновлен!</p>
                </div>
            <?php else: ?>
            <div class="userUpdate-errors">
                <?php if ($userUpdate->hasErrorsUserUpdate()): ?>
                    <p>Вы допустили ошибки:</p>
                    <ul>
                        <?php foreach ($userUpdate->getErrorsUserUpdate() as $error): ?>
                        <li><?= e($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </div>
            <form action="" method="POST" class="userUpdate-form">
                    <div class="userUpdate-form-input-name">
                        <label class="userUpdate-form-label">Имя</label>
                        <input type="text" name ="name" value="<?=e($userUpdate->name)?>" class="userUpdate-form-input">
                        <label class="userUpdate-form-label-info">Русские буквы, первая заглавная</label>
                    </div>
                    <div class="userUpdate-form-input-last_name">
                        <label class="userUpdate-form-label">Фамилия</label>
                        <input type="text" name ="last_name" value="<?=e($userUpdate->last_name)?>" class="userUpdate-form-input">
                        <label class="userUpdate-form-label-info">Русские буквы, первая заглавная</label>
                    </div>
                    <div class="userUpdate-form-input-email">
                        <label class="userUpdate-form-label">Электронный адрес</label>
                        <input type="text" name ="email" value="<?=e($userUpdate->email)?>" class="userUpdate-form-input">
                    </div>
                    <div class="userUpdate-form-input-login">
                        <label class="userUpdate-form-label">Логин</label>
                        <input type="text" name ="login" value="<?=e($userUpdate->login)?>" class="userUpdate-form-input">
                        <label class="userUpdate-form-label-info">Английские буквы, цифры, первая символ - заглавная буква, 3-25 символов</label>
                    </div>
                    <div class="userUpdate-form-input-current_password">
                        <label class="userUpdate-form-label">Текущий пароль</label>
                        <input type="password" name ="current_password" value="<?=e($userUpdate->current_password)?>" class="userUpdate-form-input">
                    </div>
                    <div class="userUpdate-form-input-new_password">
                        <label class="userUpdate-form-label">Новый пароль</label>
                        <input type="password" name ="new_password" value="<?=e($userUpdate->new_password)?>" class="userUpdate-form-input">
                        <label class="userUpdate-form-label-info">Английские буквы, цифры, минимальная длина - 8 символов</label>
                    </div>
                    <div class="userUpdate-form-input-new_password_confirm">
                        <label class="userUpdate-form-label">Повторите пароль</label>
                        <input type="password" name ="new_password_confirm" value="<?=e($userUpdate->new_password_confirm)?>" class="userUpdate-form-input">
                    </div>
                    <div class="userUpdate-form-button-block">
                        <button type="submit" class="userUpdate-form-button">СОХРАНИТЬ</button>
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