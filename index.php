<?php
$title = 'Главная';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';
$css[] ='main.css';
$css[] ='index.css';
$js[] = 'jquery-3.2.1.min.js';
$js[] = 'bootstrap.min.js';

$db = getConnect();

$q = mysqli_query($db, "
	SELECT *
	FROM gallery
	WHERE is_published = 1
    ORDER BY created_at DESC
    LIMIT 8
");

$gallery = mysqli_fetch_all($q, MYSQLI_ASSOC);




$pageResult = [
    'gallery' => $gallery,
    
];