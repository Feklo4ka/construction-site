<?php

$db = getConnect();
checkAdmin($db);

$title = 'Панель управления';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';
$css[] ='main.css';
$css[] ='admin.css';
$js[] = 'jquery-3.2.1.min.js';
$js[] = 'bootstrap.min.js';

$pageResult = [
    'feedbackList' => [],
];

$step = 4;
$page = (isset($_GET['page']) AND (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;

$start = ($page * $step) - $step;

$q = mysqli_query($db, "
	SELECT COUNT(*) AS cnt
	FROM feedback
	
");

$count = mysqli_fetch_assoc($q);

$res = mysqli_query($db, "
	SELECT * FROM feedback
    ORDER BY created_at DESC
	LIMIT $start, $step
");

$rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
$pageResult = [
    'feedbackList' => $rows,
    'pages' => ceil($count['cnt'] / $step),
    'currentPage' => $page,
];