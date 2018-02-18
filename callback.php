<?php
$db = getConnect();




if(!empty($_POST['name']) && !empty($_POST['phone'])){
    $name = escape($_POST['name'], $db);
    $phone = preg_replace('/[^+\d]+/', '', $_POST['phone']);
    if (preg_match('/^[\s\da-zа-яёЁ]+$/iu',$name)) {
        mysqli_query($db, "
        INSERT INTO callback SET name = '$name', phone = '$phone';
    ");
    }
}
header('Location: index');