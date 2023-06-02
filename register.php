<?php
	session_start();
	include 'db.php';
	require 'config.php';
	require 'vk.php';
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	require_once('log_monolog.php');
?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
			integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

		<title>Регистрация</title>
	</head>
	<body>
		<div class="container pt-4">
		<h1 class="mb-4"><a href="<? echo URL ?>">Просто сайт</a></h1>

		<?php
		if(($_POST["token"] == $_SESSION["CSRF"]) || (!empty($_GET['code']))){
			if(((!empty($_POST["login"])) && (!empty($_POST["pass"])) && (preg_match("/^[-.@_\w]+$/i", $_POST['login'])) && (preg_match("/^[-._\w]+$/i", $_POST['pass'])) ) || (!empty($tokenvk)) ){
				$login = ($tokenvk) ? $userEmail : $_POST['login'];
				$password = ($tokenvk) ? $_SESSION['token'] : $_POST['pass'];
				$role= ($tokenvk) ? "uservk" : "user";
				$sql = "SELECT * FROM users WHERE login = '$login'";
				$result = mysqli_query($link, $sql);
				$row = mysqli_fetch_assoc($result);
				if (empty($row)) {
					mysqli_query($link, 'INSERT INTO `users`(`login`, `password`, `role`) VALUES(\''.$login.'\', \''.password_hash($password, PASSWORD_DEFAULT).'\', \''.$role.'\');');
					$_SESSION['login'] = $login;
					$_SESSION['role'] = $role;
					$_SESSION['token'] = $tokenvk;
					$log->alert($login.' - зарегистрированный новый пользователь');
				}else{
					echo '<div class="alert alert-danger" style="text-align:center;">Пользователь уже есть в базе данных, авторизуйтесь или зарегистрируйтесь с другим логином</div>';
					$log->info($login.' пытается повторно зарегистрироваться');
				}
			}else{
				echo '<div style="text-align:center;"><div class="alert alert-danger" style="text-align:center;">Есть незаполненные поля или в них содержатся недопустимые символы!</div></div></div>';
				$log->warning('Пользователь при регистрации использует недопустимые символы');
			}
		}else{
			if ($_POST["token"]){
				echo '<div style="text-align:center;"><div class="alert alert-danger" style="text-align:center;">Недействительный токен</div></div></div>';
				$log->error('Кто-то пытается обойти CSRF-токен и подобрать пароль!');
			}
		}
		$token = hash('gost-crypto', random_int(0,999999));
		$_SESSION["CSRF"] = $token;
	
		

		if ($_SESSION['login']) {
			echo '<div style="text-align:center;">
				<h3> Добро пожаловать на простой сайт, '.$_SESSION['login'].'!</h3>
				<div class="alert alert-success">Регистрация и авторизация прошли успешно! Вы будете перенаправлены на основную страницу через <span id="counter">5</span></div>
					<script>
						setInterval(function() {
							var div = document.querySelector("#counter");
							var count = div.textContent * 1 - 1;
							div.textContent = count;
							if (count <= 0) {
								window.location.replace("./index.php");
							}
						}, 1000);
					</script>';

		} else {
			echo '<div class="blueBlock row">
				<div class="col-12 col-sm-8 offset-sm-2" style="text-align: center;">
					<h2>Придумайте логин и пароль</h2>
					<br>
						<form method="post" class="form-group">
							<input type="text" name="login" placeholder="Логин">
							<input type="text" name="pass" placeholder="Пароль">
							<input type="hidden" name="token" value="'.$token.'"> <br/>
							<input type="submit" class="btn btn-primary">
						</form>
						<a href="http://oauth.vk.com/authorize?'.http_build_query($params).'">Регистрация через ВКонтакте</a><br>
						<a href="./auth.php">Авторизоваться</a><br>
				</div>
			</div>';
		}?>
			<style>
			.blueBlock {
				text-align: center;
				height: 300px;
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