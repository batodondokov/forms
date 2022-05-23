<?php

include 'init.php';

use App\Models\Form;

if (!empty($_SESSION['is_authorized']))
{
	$is_authorized = true;
	if ($_POST)
	{
		if (isset($_POST['selected']))
		{
			Form::deleteByIds($_POST['selected']);
			$_SESSION['last_action_time'] = time();
		}
		if (isset($_POST['logout']))
		{
			session_destroy();
			header('Location: admin.php');
			exit;
		}
	}
	$items = Form::loadAll();
	$stats = Form::loadStats();
	if (($_SESSION['last_action_time'] + 600) < time())
	{
		session_destroy();
		header('Location: admin.php');
		exit;
	}
}
else
{	
	if ($_POST)
	{
		$form = new Form($_POST);
		if ($form->validate_authorize()) 
		{			
			$_SESSION['is_authorized'] = 1;
			$_SESSION['last_action_time'] = time();
			header('Location: admin.php');
			exit;
		}
	}
	else
	{
		$form = new Form;
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/style_admin.css" type="text/css"/>
    <title>Администратор</title>
</head>
<body>
	<?php if (!empty($is_authorized)): ?>
		<div class="form">		
			<div class="form-header">
				<h1 class="form-title">СТРАНИЦА АДМИНИСТРАТОРА</h1>
			</div>
			<div class="form-stats">
				<table border="1">
					<thead>
						<tr>
							<th>Количество пользователей: </th>
							<th>Количество уникальных IP: </th>
							<th>Количество хитов: </th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<th><?=e($stats['session'])?></th>
							<th><?=e($stats['ip'])?></th>
							<th><?=e($stats['hits'])?></th>
						</tr>
					</tbody>
				</table>
			</div>
			<form method="POST">
				<table border="1">
					<thead>
						<tr>
							<th></th>
							<th>Id</th>
							<th>Дата</th>
							<th>IP</th>
							<th>Имя</th>
							<th>Фамилия</th>
							<th>Электронный адресс</th>
							<th>Номер телефона</th>
							<th>Тематика конференции</th>
							<th>Метод оплаты участия</th>
							<th>Рассылка</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items as $item): ?>
						<tr>
							<td><input type="checkbox" name="selected[]" value="<?= e($item->id) ?>"></td>
							<td><?= e($item->id) ?></td>
							<td><?= e($item->created_at) ?></td>
							<td><?= e($item->ip) ?></td>
							<td><?= e($item->name) ?></td>
							<td><?= e($item->lastname) ?></td>
							<td><?= e($item->email) ?></td>
							<td><?= e($item->phone) ?></td>
							<td><?= e($item->topic_id) ?></td>
							<td><?=	e($item->payment_id) ?></td>
							<td><?php 
								if($item->is_confirmed == 0){
									echo e('Нет');
								}
								else{
									echo e('Да');
								}						 
							?></td>
						</tr>
						<?php endforeach ?>
					</tbody>				
				</table>
				<div class="form-footer">
					<div class="buttons">
						<button class="button-delete" type="submit">УДАЛИТЬ</button>
						<button class="button-logout" type="submit" name="logout">ВЫЙТИ</button>    
					</div>
				</div>
			</form>
		</div>
	<?php else: ?>			
		<div class="authorize">
			<?php if ($form->hasErrorsAuthorize()): ?>
				<div class="errors">
					<p>Вы допустили ошибки:</p>
					<ul>
						<?php foreach ($form->getErrorsAuthorize() as $error): ?>
						<li><?= e($error) ?></li>
						<?php endforeach ?>
					</ul>
				</div>
			<?php endif ?>
			<div class="form-authorize">
				<div class="authorize-header">
					<h1 class="authorize-title">АВТОРИЗАЦИЯ</h1>
				</div>
				<form method="POST">
					<div class="authorize-field">
						<label>Логин</label>
						<input type="text" name ="admin_login" value="<?=e($form->admin_login) ?>">   
					</div>
					<div class="authorize-field">
						<label>Пароль</label>
						<input type="password" name ="admin_password" value="<?= e($form->admin_password)?>">
					</div>
					<div class="form-footer">
						<div class="button">
							<button class="button-login" type="submit">ВОЙТИ</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php endif?>				
</body>
</html>