<?php

$db = getConnect();
checkAdmin($db);

$title = 'Управление ценами';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';
$css[] ='main.css';
$css[] ='admin.css';
$js[] = 'jquery-3.2.1.min.js';
$js[] = 'bootstrap.min.js';
$js[] = 'calc-edit.js';

$pageResult = [
    'calcList' => [],
];



$res = mysqli_query($db, "
	SELECT * FROM calculator
    
	
");

$rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
$pageResult = [
    'calcList' => $rows,
    
];