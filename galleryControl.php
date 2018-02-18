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


$db = getConnect();

$q = mysqli_query($db, "
	SELECT *
	FROM gallery
    ORDER BY created_at DESC
	
");

$galleryList = mysqli_fetch_all($q, MYSQLI_ASSOC);


$pageResult = [
    'galleryList' => $galleryList,
    
];