<?php
$title = 'Контакты';
$meta = ['charset' => 'utf-8'];

$css[] ='bootstrap.min.css';
$css[] ='font-awesome.min.css';
$css[] ='main.css';
$css[] ='common.css';

$db = getConnect();

if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message'])){
    $name = escape($_POST['name'], $db);
    $email = escape($_POST['email'], $db);
    $text = escape($_POST['message'], $db);
    if (preg_match('/^[\s\da-zа-яёЁ]+$/iu',$name)) {
        if (preg_match('/^.{0,1000}$/',$text)) {
            if (preg_match('/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/',$email)) {

                mysqli_query($db, "
        INSERT INTO feedback SET name = '$name', email = '$email', text = '$text';
    ");
            }
        }
    }
}
