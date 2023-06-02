<?php
session_start();
include 'config.php';
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
            integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Авторизация</title>
    </head>
    <body>
        <div class="container pt-4">
            <h1 class="mb-4"><a href="<? echo URL ?>">Просто сайт</a></h1>
			<?php
				if (isset($_SESSION['login'])){
				echo '<div style="text-align:right">'.$_SESSION['login'].' <a href="./auth.php?exit=1">Выйти</a></div>';
				}
			?>
			<div class="blueBlock row">
			<div class="col-12 col-sm-8 offset-sm-2" style="text-align: center;">

<?php
if (isset($_SESSION['login'])){
	echo '<p>Аутентификация пользователя — это когда мы убеждаемся, что наш посетитель сайта —  тот, за кого себя выдает. То есть, когда мы запрашиваем у него логин и пароль.</p>';
	if ($_SESSION['role'] == 'uservk'){
		echo '<p><img src="./au.jpg" style="width:350px;height:auto;"></p>';
	}else{
		echo '<h3>Внимание!</h3><p style="color:red;">Текст с изображениями могут видеть только пользователи, зарегистрированные с помощью сети Вконтакте</p>';
	}
}else{
	//header('Location: '.URL.'auth.php ');
	echo 'Для просмотра скрытого текста на этой странице - авторизуйтесь<br>';
	echo '<a href="'.URL.'auth.php">Перейти для авторизации</a>';
}


?>
</div>
</div>
<style>
			.blueBlock {
				text-align: center;
				height: 500px;
				display: flex;
				flex-direction: column;
				background: aliceblue;
				border-radius: 10px;
				justify-content: center;
			}
			</style>
		</div>
	</body>
</html>

