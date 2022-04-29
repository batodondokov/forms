<?php

include 'classes/Form.php';
include 'classes/Database.php';
include 'func.php';


if (!empty($_GET['success']))
{
	$success = true;
}

if ($_POST)
{
	$form = new Form($_POST);
    if ($form->validate()) 
    {
        $form->save();
        header('Location: /form4/index.php?success=1');
        exit;
    }
}
else
{
    $form = new Form;
}
?>

<!DOCTYPE html>
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

	<?php if ($form->hasErrors()): ?>
		<div class="errors">
			<p>Вы допустили ошибки:</p>
			<ul>
				<?php foreach ($form->getErrors() as $error): ?>
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
                    <input type="text" name ="name" value="<?=e($form->name) ?>">   
                </div>
                <div class="form-field">
                    <label>Фамилия</label>
                    <input type="text" name ="lastname" value="<?= e($form->lastname)?>">
                </div>
                <div class="form-field">
                    <label>Электронный адрес</label>
                    <input type="text" name ="email" value="<?= e($form->email)?>">  
                </div>
                <div class="form-field">
                    <label>Номер телефона</label>
                    <input type="text" name ="phone" value="<?= e($form->phone)?>">
                </div>
                <div class="form-field">
                    <label>Тематика конференции</label>
                    <select name="topic">
						<?php foreach (Form::$topics as $topic): ?>
							<option<?= $form->topic === $topic ? 'selected' : '' ?>><?= e($topic) ?></option>
						<?php endforeach ?>
		            </select>
                </div>
                <div class="form-field">
                    <label>Метод оплаты участия</label>
                    <select name="payment">
						<?php foreach (Form::$payments as $payment): ?>
							<option<?= $form->payment === $payment ? 'selected' : '' ?>><?= e($payment) ?></option>
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