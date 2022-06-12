<?php

include 'init.php';

use App\Models\Login;

if ($_POST)
{
    $login = new Login($_POST);
    if ($login->validate_authorize()) 
    {			
        $_SESSION['is_authorized'] = 1;
        $_SESSION['last_action_time'] = time();
        $_SESSION['user_id'] = $login->getUserId($_POST['login']);
        header('Location: index.php');
        exit;
    }
}
else
{
    $login = new Login;
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
    <link rel="stylesheet" href="css/style-login.css" type="text/css"/>
    <title>Вход</title>
</head>
<body>
    <header class="page-header">
        <h1 class="page-header-title">Мой календарь</h1>
    </header>
    <main class="main-content">
        <div class="login-block">
            <h2 class="login-title">Войти</h2>
            <a href="registration.php" class="login-go-registration">Зарегистрироваться</a>
            <div class="login-errors">
                <?php if ($login->hasErrorsAuthorize()): ?>
                    <p>Вы допустили ошибки:</p>
                    <ul>
                        <?php foreach ($login->getErrorsAuthorize() as $error): ?>
                        <li><?= e($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
            </div>
            <form action="" method="POST" class="login-form">
                    <div class="login-form-input-login">
                        <label class="login-form-label">Логин</label>
                        <input type="text" name ="login" value="<?=e($login->login)?>" class="login-form-input">
                    </div>
                    <div class="login-form-input-password">
                        <label class="login-form-label">Пароль</label>
                        <input type="password" name ="password" value="<?=e($login->password)?>" class="login-form-input">
                    </div>
                    <div class="login-form-button-block">
                        <button type="submit" class="login-form-button">ВОЙТИ</button>
                    </div>
            </form>
        </div>
    </main>
    <footer class="page-footer">
        <div class="copyright-note">
            Дондоков Бато, 2022
        </div>
    </footer>
</body>
</html>