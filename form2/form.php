<?php

include 'config.php';
include 'func.php';

if (!empty($_GET['success']))
{
	$success = true;
}

if ($_POST)
{
	$name = $_POST['name'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $topic = $_POST['topic'] ?? '';
    $payment = $_POST['payment'] ?? '';
    $confirm = (bool)($_POST['confirm'] ?? '');

	$errors = [];

	if(!$name)
    {
        $errors [] = 'Поле имя не заполнено';
    }
    
    if(!$lastname)
    {
        $errors [] =  'Поле фамилия не заполнено';
    }

    if(!$email)
    {
        $errors [] = 'Поле электронный адресс не заполнено';
    }
    
    if(!$phone)
    {
        $errors [] =  'Поле телефон не заполнено';
    }

	if (!$errors)
	{

		$status = 'exist';

		$item = [
			uniqid(),
			time(),
			getenv('REMOTE_ADDR'),
			$name,
			$lastname,
			$email,
			$phone,
			$topic,
			$payment,
			(int)$confirm,
			$status
		];

        if (!file_exists($data))
        {
            mkdir($data, 0777);
        }

		$contents = implode($separator, $item);

		file_put_contents($datafile, $contents . "\n", FILE_APPEND);

		header('Location: /form2/form.php?success=1');
		exit;
	}
}

?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style type="text/css" media="all">
    @import url("style.css");
    </style>
</head>
<body>

	<?php if (!empty($errors)): ?>
		<div class="errors">
			<p>Вы допустили ошибки:</p>
			<ul>
				<?php foreach ($errors as $error): ?>
				<li><?= e($error) ?></li>
				<?php endforeach ?>
			</ul>
		</div>
	<?php endif ?>

	<?php if (!empty($success)): ?>
		<div class="succsess">
			<p>Форма отправлена успешно!</p>
		</div>
	<?php else: ?>
		<div class="form">
			<div class="form-header">
				<h1 class="form-title">РЕГИСТРАЦИЯ НА КОНФЕРЕНЦИЮ</h1>
			</div>
			<form method="POST" action="">
                <div class="form-field">
                    <label>Имя</label>
                    <input type="text" name ="name" value="<?=e($_POST['name'] ?? '') ?>">   
                </div>
                <div class="form-field">
                    <label>Фамилия</label>
                    <input type="text" name ="lastname" value="<?= e($_POST['lastname'] ?? '')?>">
                </div>
                <div class="form-field">
                    <label>Электронный адрес</label>
                    <input type="text" name ="email" value="<?= e($_POST['email'] ?? '')?>">  
                </div>
                <div class="form-field">
                    <label>Номер телефона</label>
                    <input type="text" name ="phone" value="<?= e($_POST['phone'] ?? '')?>">
                </div>
                <div class="form-field">
                    <label>Тематика конференции</label>
                    <select name="topic">
						<?php foreach ($topics as $topic): ?>
							<option<?= isset($_POST['topic']) ? $_POST['topic'] === $topic ? ' selected' : '' : '' ?>><?= e($topic) ?></option>
						<?php endforeach ?>
		            </select>
                </div>
                <div class="form-field">
                    <label>Метод оплаты участия</label>
                    <select name="payment">
						<?php foreach ($payments as $payment): ?>
							<option<?= isset($_POST['payment']) ? $_POST['payment'] === $payment ? ' selected' : '' : '' ?>><?= e($payment) ?></option>
						<?php endforeach ?>
                    </select>
                </div>
                <div class="form-field-checkbox">
                    <label>
			            <input type="checkbox" name ="confirm">
			            Желаете получать рассылку о конференции
		            </label>
                </div>
                <div class="form-footer">
                    <div class="button">
                        <button type="submit">ОТПРАВИТЬ</button>
                    </div>
                </div>
            </form>
            <?php endif?>
		</div>
</body>
</html>