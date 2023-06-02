<?
    session_start();
    require_once("db.php");
    require_once("vk.php");
	require_once("config.php");
    require_once('log_monolog.php');
	
	if ($_GET['exit']){
		session_destroy();
		header('Location: '.URL.'/auth.php ');
	}
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
                if(($_POST["token"] == $_SESSION["CSRF"]) || (!empty($_GET['code']))){
                    // сразу запрещаю в post все лишнее
                    if(((isset($_POST["login"]))&& (isset($_POST["pass"])) && (preg_match("/^[-.@_\w]+$/i", $_POST['login'])) && (preg_match("/^[-.@_\w]+$/i", $_POST['pass'])) )  || (!empty($tokenvk))  ){
                        $login = ($tokenvk) ? $userEmail : $_POST['login'];
                        $password = $_POST['pass'];
                        $result = mysqli_query($link, "SELECT * FROM users WHERE login='". $login. "'");
                        if(mysqli_num_rows($result) >0){
							$row = mysqli_fetch_assoc($result);
                            $role=$row['role'];
							if ((password_verify ($password, $row['password'])) || (!empty($tokenvk))) {
                            // логин и пароль нашли
                            $_SESSION['login'] = $login;
                            $_SESSION['token'] = $tokenvk;
                            $_SESSION['role'] = $role;
							}else{
                            //Отображаем сообщение, что логин и пароль не найдены
                            echo '<div style="text-align:center;"><div style="text-align:center;" class="alert alert-danger">Неверно введен логин или пароль</div></div>';
                            $log->warning($login.' неверно ввел логин или пароль');
							}
                        }else{
                            //Отображаем сообщение, что логин и пароль не найдены
                            echo '<div style="text-align:center;"><div style="text-align:center;" class="alert alert-danger">Пользователь не найден, попробуйте зарегистрироваться повторно</div></div>';
                            $log->warning('Пользователь '.$login.' не найден');
                        }

                    }
                }else{
					if ($_POST["token"]){
						echo '<div style="text-align:center;"><div style="text-align:center;" class="alert alert-danger">Авторизация не выполнена. Токен устарел.</div></div>';
                        $log->error('Кто-то пытается обойти CSRF-токен и подобрать пароль!');
					}
                }
				
				$token = hash('gost-crypto', random_int(0,999999));
                $_SESSION["CSRF"] = $token;

                if(!$_SESSION['login']){
            ?>
            <div class="blueBlock row">
                <div class="col-12 col-sm-8 offset-sm-2" style="text-align: center;">
                <h2>Введите свой логин и пароль</h2>
                <br>
                <form method="post" action="">
                    <input type="text" name="login" placeholder="Логин"><br/>
                    <input type="password" name="pass"> <br/>
                    <input type="hidden" name="token" value="<?=$token?>"> <br/>
                    <input type="submit" value="Войти">
                </form>
                    <?php // Выводим на экран ссылку для открытия окна диалога авторизации
                        echo '<a href="http://oauth.vk.com/authorize?' . http_build_query( $params ) . '">Авторизация через ВКонтакте</a><br>';
                    ?>
                <a href="./register.php">Зарегистрироваться</a>
                </div>
            </div>
            <?php
                }else{
                    ?>
                    <div class="alert alert-success" style="text-align: center;"><p>Привет, <?echo $_SESSION['login'];?> Вы успешно авторизованы и будете перенаправлены на основную страницу через <span id="counter">5</span></p></div>
                    <script>
                        setInterval(function() {
                            var div = document.querySelector("#counter");
                            var count = div.textContent * 1 - 1;
                            div.textContent = count;
                            if (count <= 0) {
                                window.location.replace("./index.php");
                            }
                        }, 1000);
                    </script>
                    <?php
                }
            ?>
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