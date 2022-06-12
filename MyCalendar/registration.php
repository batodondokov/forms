<?php

include 'init.php';

use App\Models\Registration;

if (!empty($_SESSION['registration_success']))
{
	$registration_success = true;
    $_SESSION['registration_success'] = null;
}

if ($_POST)
{
    $registration = new Registration($_POST);
    if ($registration->validate_registration()) 
    {	
        $registration->saveUser();
        $_SESSION['registration_success'] = 1;
        $_SESSION['last_action_time'] = time();
        header('Location: registration.php');
        exit;
    }
}
else
{
    $registration = new Registration();
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
    <link rel="stylesheet" href="css/style-registration.css" type="text/css"/>
    <title>Редактирование</title>
</head>
<body>
    <header class="page-header">
        <h1 class="page-header-title">Мой календарь</h1>
    </header>
    <main class="main-content">
        <div class="registration-block">
            <h2 class="registration-title">Регистрация</h2>
            <a href="login.php" class="registration-go-login">Войти</a>
            <?php if (!empty($registration_success)): ?>
                <div class="registration-succsess-block">
                    <p class="registration-succsess-p">Регистрация прошла успешно!</p>
                </div>
            <?php else: ?>
            <div class="registration-errors">
                <?php if ($registration->hasErrorsRegistration()): ?>
                    <p>Вы допустили ошибки:</p>
                    <ul>
                        <?php foreach ($registration->getErrorsRegistration() as $error): ?>
                        <li><?= e($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </div>
            <form action="" method="POST" class="registration-form">
                    <div class="registration-form-input-name">
                        <label class="registration-form-label">Имя</label>
                        <input type="text" name ="name" value="<?=e($registration->name)?>" class="registration-form-input">
                        <label class="registration-form-label-info">Русские буквы, первая заглавная</label>
                    </div>
                    <div class="registration-form-input-last_name">
                        <label class="registration-form-label">Фамилия</label>
                        <input type="text" name ="last_name" value="<?=e($registration->last_name)?>" class="registration-form-input">
                        <label class="registration-form-label-info">Русские буквы, первая заглавная</label>
                    </div>
                    <div class="registration-form-input-email">
                        <label class="registration-form-label">Электронный адрес</label>
                        <input type="text" name ="email" value="<?=e($registration->email)?>" class="registration-form-input">
                    </div>
                    <div class="registration-form-input-login">
                        <label class="registration-form-label">Логин</label>
                        <input type="text" name ="login" value="<?=e($registration->login)?>" class="registration-form-input">
                        <label class="registration-form-label-info">Английские буквы, цифры, первая символ - заглавная буква, 3-25 символов</label>
                    </div>
                    <div class="registration-form-input-password">
                        <label class="registration-form-label">Пароль</label>
                        <input type="password" name ="password" value="<?=e($registration->password)?>" class="registration-form-input">
                        <label class="registration-form-label-info">Английские буквы, цифры, минимальная длина - 8 символов</label>
                    </div>
                    <div class="registration-form-input-password_confirm">
                        <label class="registration-form-label">Повторите пароль</label>
                        <input type="password" name ="password_confirm" value="<?=e($registration->password_confirm)?>" class="registration-form-input">
                    </div>
                    <div class="registration-form-button-block">
                        <button type="submit" class="registration-form-button">СОХРАНИТЬ</button>
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