<?php
session_start(); // Токен храним в сессии
 
// Параметры приложения
$clientId     = '51664271'; // ID приложения
$clientSecret = 'VEP4nXRDB1vDMopw0rmQ'; // Защищённый ключ
$redirectUri  = 'http://localhost'.$_SERVER['PHP_SELF']; // Адрес, на который будет переадресован пользователь после прохождения авторизации
 
// Формируем ссылку для авторизации
$params = array(
	'client_id'     => $clientId,
	'redirect_uri'  => $redirectUri,
	'response_type' => 'code',
	'v'             => '5.126', // (обязательный параметр) версиb API https://vk.com/dev/versions
 
	// Права доступа приложения https://vk.com/dev/permissions
	// Если указать "offline", полученный access_token будет "вечным" (токен умрёт, если пользователь сменит свой пароль или удалит приложение).
	// Если не указать "offline", то полученный токен будет жить 12 часов.
	'scope'         => 'email,offline',
);
 


if (empty($_SESSION['token']) && !empty($_GET['code']) && ($_GET['code']!==$_SESSION['code'])){
	$_SESSION['code']=$_GET['code'];
$params = array(
    'client_id'     => $clientId,
    'client_secret' => $clientSecret,
    'code'          => $_GET['code'],
    'redirect_uri'  => $redirectUri
);

if (!$content = @file_get_contents('https://oauth.vk.com/access_token?' . http_build_query($params))) {
    $error = error_get_last();
    throw new Exception('HTTP request failed. Error: ' . $error['message']);
}

$response = json_decode($content);

// Если при получении токена произошла ошибка
if (isset($response->error)) {
    throw new Exception('При получении токена произошла ошибка. Error: ' . $response->error . '. Error description: ' . $response->error_description);
}
//А вот здесь выполняем код, если все прошло хорошо
$tokenvk = $response->access_token; // Токен
$expiresIn = $response->expires_in; // Время жизни токена
$userId = $response->user_id; // ID авторизовавшегося пользователя
$userEmail = $response->email; // Email пользователя

// Сохраняем токен в сессии
//$_SESSION['token'] = $tokenvk;
//$_SESSION['login'] = $userEmail;

}