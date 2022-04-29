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
    <form method="POST" action="">
    <?php
    if($_POST)
    {
        if(isset($_POST['selected']))
        {
            foreach($_POST['selected'] as $selected)
            {
                unlink($selected);
            }
        }
    }
    $files = glob('data/*.txt');
    foreach ($files as $file)
    {
        echo '<div class="form-field">';
        echo '<div class="delite_checkbox"><input type ="checkbox" name = "selected[]" value = "' . $file . '"> ' . $file . '</div>';
        $contents = file_get_contents($file);
        echo nl2br ($contents);
        echo '</div>';
    }
    ?>
    <div class="form-footer">
        <div class="button">
            <button type="submit">УДАЛИТЬ</button>
        </div>
    </div>
</form>
</div>
</body>
</html>