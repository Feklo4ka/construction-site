<?php
$db = getConnect();

if (!empty($_SESSION) AND isset($_SESSION['token'])) {
	unset($_SESSION['token']);
}
header('Location: auth');