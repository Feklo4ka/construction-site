<?php
$title = 'Галлерея';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';

$css[] ='main.css';
$css[] ='common.css';

$db = getConnect();

$q = mysqli_query($db, "
	SELECT *
	FROM gallery
	WHERE is_published = 1
    ORDER BY created_at DESC
");

$gallery = mysqli_fetch_all($q, MYSQLI_ASSOC);




$pageResult = [
    'gallery' => $gallery,
    
];