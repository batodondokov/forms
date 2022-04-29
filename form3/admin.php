<?php

include 'classes/Form.php';
include 'func.php';

if ($_POST)
{
	if (isset($_POST['selected']))
	{
		Form::deleteByIds($_POST['selected']);
	}
}

              
$items = Form::loadAll();

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
    <title>Администратор</title>
    <style type="text/css" media="all">
    @import url("style_admin.css");
    </style>
</head>
<body>
	<div class="form">
		<div class="form-header">
			<h1 class="form-title">СТРАНИЦА АДМИНИСТРАТОРА</h1>
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
						<td><?= e(date('d.m.Y H:i', $item->date)) ?></td>
						<td><?= e($item->ip) ?></td>
						<td><?= e($item->name) ?></td>
						<td><?= e($item->lastname) ?></td>
						<td><?= e($item->email) ?></td>
						<td><?= e($item->phone) ?></td>
						<td><?= e($item->topic) ?></td>
						<td><?=	e($item->payment) ?></td>
						<td><?php 
							if($item->confirm == false){
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
			<div class="button">
				<button type="submit">УДАЛИТЬ</button>
			</div>
   		 </div>
		</form>
	</div>
	

</body>
</html>