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

if (isset($_POST['editGallery'])) {
    $db = getConnect();

    $galleryId = (int) $_POST['id'];

    $name = escape($_POST['name'], $db);
    $status = (int) $_POST['status'];

    $result = mysqli_query($db, "
        SELECT * FROM gallery WHERE id = {$galleryId}
    ");

    $galleryRow = mysqli_fetch_assoc($result);

    mysqli_query($db, "
        UPDATE gallery
        SET name = '{$name}', is_published = {$status}
        WHERE id = {$galleryId}
    ");

    if ($galleryRow['name'] != $name) {
        @rename(__DIR__.'/projects/'.$galleryRow['name'], __DIR__.'/projects/'.$name);

        $result = mysqli_query($db, "
            SELECT * FROM gallery_image WHERE gallery_id = {$galleryId}
        ");

        while ($galleryImageRow = mysqli_fetch_assoc($result)) {
            $newPath = 'projects/'.$name.'/'.basename($galleryImageRow['path']);

            mysqli_query($db, "
                UPDATE gallery_image
                SET path = '{$newPath}'
                WHERE id = {$galleryImageRow['id']}
            ");
        }
    }

    if (isset($_POST['gallery_image_delete'])) {
        $galleryImageIdList = array_map('intval', (array) $_POST['gallery_image_delete']);
        $galleryImageIdList = implode(', ', $galleryImageIdList);        

        $result = mysqli_query($db, "
            SELECT * FROM gallery_image
            WHERE id IN ({$galleryImageIdList})        
        ");

        while ($row = mysqli_fetch_assoc($result)) {
            $filename = __DIR__.'/'.$row['path'];
            @unlink($filename);
        }

        mysqli_query($db, "
            DELETE FROM gallery_image
            WHERE id IN ({$galleryImageIdList})
        ");
    }
    
    if (isset($_FILES['images'])) {
        $result = mysqli_query($db, "
            SELECT * FROM gallery
            WHERE id = {$galleryId}
        ");

        $galleryRow = mysqli_fetch_assoc($result);

        $galleryName = $galleryRow['name'];

        $allowedExt = ['png', 'jpg', 'jpeg', 'gif'];

        foreach($_FILES['images']['name'] as $i => $filename) {
            $ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

            if (in_array($ext, $allowedExt)) {
                $tmp_name = $_FILES['images']['tmp_name'][$i];
                $basename = basename($filename);
                $path = "projects/{$galleryName}/{$basename}";
                if (move_uploaded_file($tmp_name, __DIR__."/".$path)) {
                    mysqli_query($db, "
                        INSERT INTO gallery_image
                        SET gallery_id = '{$galleryId}', path = '{$path}'
                    ");
                }
            }
        }        
    }

    header('Location: galleryControl');
}

$id = (int)$_GET['id'];
$query = mysqli_query($db, "
    SELECT * FROM gallery
    WHERE id = {$id}
");

$galleryRow = mysqli_fetch_assoc($query);

$q = mysqli_query($db, "
    SELECT * FROM gallery_image
    WHERE gallery_id = {$id}
");
$rows = mysqli_fetch_all($q, MYSQLI_ASSOC);

$pageResult = [
    'rows' => $rows,
    'galleryRow' => $galleryRow
    
];
