<?php
$title = 'Галлерея';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';

$css[] ='main.css';
$css[] ='common.css';
$css[] ='jquery.fancybox.css';
$js[] = 'jquery-3.2.1.min.js';
$js[] = 'bootstrap.min.js';
$js[] = 'jquery.fancybox.pack.js';
$js[] = 'galleryImg.js';


$db = getConnect();
$id = (int)$_GET['id'];

$query = mysqli_query($db, "
    SELECT * FROM gallery
    WHERE id = $id
");

$galleryRow = mysqli_fetch_assoc($query);

$q = mysqli_query($db, "
    SELECT * FROM gallery_image
    WHERE gallery_id = $id
");
$rowsImg = mysqli_fetch_all($q, MYSQLI_ASSOC);

$pageResult = [
    'rowsImg' => $rowsImg,
    'galleryRow' => $galleryRow
    
];
