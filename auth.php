<?php
$title = 'Авторизация';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';
$css[] ='main.css';
$css[] ='admin.css';
$pageResult = [
    'text' => '',
];


$db = getConnect();

if (isset($_GET['logout'])) {
    unset($_SESSION['token']);
}


if (!empty($_SESSION) && isset($_SESSION['token'])) {
	header('Location: admin');
}

if(!empty($_POST)) {
	$login = escape($_POST['login'], $db);
	$pass = md5($_POST['pass']);

    $now = date('Y-m-d H:i:s', time());
    
    $sql = <<<SQL
SELECT * FROM user WHERE login = 'admin' AND password = '{$pass}'
SQL;

    $count = mysqli_query($db, $sql);

    if (mysqli_num_rows($count) == 0) {
		$pageResult['text'] = 'Такого пользователя не существует';
	}
	else if (mysqli_num_rows($count) == 1) {
		$row = mysqli_fetch_assoc($count);
        
		$session = $_COOKIE['PHPSESSID'];
		$token = getHash();
		$expire = date('Y-m-d H:i:s', time() + 3600);

		mysqli_query($db, "
			UPDATE user SET
                session = '{$session}',
                token = '{$token}',
                expire = '{$expire}'
            WHERE 
			    login = 'admin'
            ");
        
		$_SESSION['token'] = $token;
		header('Location: admin');
	}
}