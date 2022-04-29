<?php

include 'config.php';
include 'func.php';

if ($_POST)
{
	if (isset($_POST['selected']))
	{
		$contents = file_get_contents($datafile);
		$lines = explode("\n", trim($contents));

		foreach ($_POST['selected'] as $selected)
		{
			foreach ($lines as $index => $line)
			{
				$cols = explode($separator, trim($line));
				$id = $cols[0];
				if ($id === $selected)
				{
					array_splice($lines, $index, 1, [str_replace('exist','deleted',$line)]);
					break;
				}
				
			}

		}
		file_put_contents($datafile, implode("\n", $lines) . "\n");
	}
}

$items = [];

if (file_exists($datafile))
{
	$contents = file_get_contents($datafile);

	$lines = explode("\n", trim($contents));

	foreach ($lines as $line)
	{
		$cols = explode($separator, trim($line));
		$item = [
			'id' => $cols[0],
			'date' => $cols[1],
			'ip' => $cols[2],
			'name' => $cols[3],
			'lastname' => $cols[4],
			'email' => $cols[5],
			'phone' => $cols[6],
			'topic' => $cols[7],
			'payment' => $cols[8],
			'confirm' => $cols[9],
			'status' => $cols[10]
		];
		
		if($cols[10] != 'deleted'){
			$items[] = $item;
		}
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
						<td><input type="checkbox" name="selected[]" value="<?= e($item['id']) ?>"></td>
						<td><?= e($item['id']) ?></td>
						<td><?= e(date('d.m.Y H:i', $item['date'])) ?></td>
						<td><?= e($item['ip']) ?></td>
						<td><?= e($item['name']) ?></td>
						<td><?= e($item['lastname']) ?></td>
						<td><?= e($item['email']) ?></td>
						<td><?= e($item['phone']) ?></td>
						<td><?= e($item['topic']) ?></td>
						<td><?= e($item['payment']) ?></td>
						<td><?php
						if ($item['confirm'] == '1') {
							echo e('Да');
						}
						else {
							echo e('Нет');
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