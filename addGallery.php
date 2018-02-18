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

if(!empty($_POST["name"]) && 
   ($_FILES['firstFoto']['size']<=10*1024*1024)) {
    $status = (int)$_POST['status'];
    $name = htmlspecialchars($_POST['name']);
    
    @mkdir("projects/$name", 0777, true);

    $query = mysqli_query($db, "
            SELECT * FROM gallery 
            WHERE name = '$name';
        ");
    
    $firstfoto = $_FILES['firstFoto']['name'];
    $tmp_name = $_FILES['firstFoto']['tmp_name'];
    move_uploaded_file($tmp_name, __DIR__."/projects/$name/$firstfoto");
    
    if(!mysqli_num_rows($query)) {
        $query = mysqli_query($db, "
                INSERT INTO gallery SET 
                name = '$name',
                is_published = '$status',
                cover_img = 'projects/$name/$firstfoto';
                
            ");
       
        $galleryId = mysqli_insert_id($db);
    } else {
        $row = mysqli_fetch_assoc($query);
        $galleryId = $row['id'];
    }
    

    
    $allowedExt=['png', 'jpg', 'jpeg', 'gif'];
    foreach($_FILES['foto']['name'] as $i => $filename) {
        $ext = substr($filename, strrpos($filename, '.') + 1);
        if (in_array($ext, $allowedExt)){
            $tmp_name = $_FILES['foto']['tmp_name'][$i];
            $fotoname = basename($filename);
            move_uploaded_file($tmp_name, "projects/$name/$fotoname");
            mysqli_query($db, "
                INSERT INTO gallery_image SET 
                gallery_id = '$galleryId',
                path = 'projects/$name/$fotoname';
            ");
            
        }
        
    }
    header('Location: galleryControl');
}


