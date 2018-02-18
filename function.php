<?php

require_once 'global.php';

session_start();

function getConnect() {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	mysqli_set_charset($db, 'utf8');
	return $db;
}

function view($page, $data=[]){
    extract($data);
    $page.='.html';
    if(file_exists($page)){
        include $page;
    }
}

function escape($str, $db) {
	return mysqli_real_escape_string($db, $str);
}
function getHash($size = 32) {
	$str = "abcdefghijklmnopqrstuvwxyz0123456789";
	$hash = "";
	for($i=0; $i<$size; $i++) {
		$hash.= $str[rand(0, 35)];
	}
	return $hash;
}

function checkAdmin($db) {
    $token = isset($_SESSION['token']) ? $_SESSION['token'] : '';

    $info = mysqli_query($db, "
        SELECT * FROM user			
        WHERE token = '$token'
    ");

    if(mysqli_num_rows($info) == 1) {
        $user = mysqli_fetch_assoc($info);

        $userExpire = strtotime($user['expire']);

        if ($user['token'] != $token) {
            unset($_SESSION['token']);
            $user = false;
        }
        else if($userExpire < time()) {
            $token = getHash();
            $_SESSION['token'] = $token;

            $expire = date('Y-m-d H:i:s', time() + 3600);

            mysqli_query($db, "
                UPDATE user
                SET	token = '$token', expire = '{$expire}'
            ");
        }
    } 
    else {
        unset($_SESSION['token']);
        $user = false;
    }
    
    if ($user === false) {
        header('Location: auth');
    }
}

function priceReduce($id) {
    $id = (array) $id;
    
    return function ($carry, $row) use ($id) {
        if (in_array($row['id'], $id)) {
            return $carry + $row['price'];
        }
        return $carry;
    };
}

// функции для обновления галереи
// 1. обновление галереи
function updateGallery($galleryId, $update) {
    $db = getConnect();

    $galleryId = (int) $galleryId;

    $name = escape($update['name'], $db);
    $status = (int) $update['status'];

    $result = mysqli_query($db, "
        SELECT * FROM gallery WHERE id = {$galleryId}
    ");

    $galleryRow = mysqli_fetch_assoc($result);

    mysqli_query($db, "
        UPDATE gallery
        SET name = '{$name}', is_published = {$status}
        WHERE id = {$galleryId}
    ");

    if ($galleryRow['name'] == $name) {
        return;
    }

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

// 2. удаление картинок
function deleteGalleryImages($galleryImageIdList) {
    $galleryImageIdList = array_map('intval', (array) $galleryImageIdList);
    $galleryImageIdList = implode(', ', $galleryImageIdList);

    if (empty($galleryImageIdList)) {
        return;
    }

    $db = getConnect();

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

// 3. добавление картинок в галлерею
function addGalleryImages($galleryId, $images) {
    $db = getConnect();

    $galleryId = (int) $galleryId;

    $result = mysqli_query($db, "
        SELECT * FROM gallery
        WHERE id = {$galleryId}
    ");

    $galleryRow = mysqli_fetch_assoc($result);

    if (!$galleryRow) {
        return;
    }

    $galleryName = $galleryRow['name'];

    $allowedExt = ['png', 'jpg', 'jpeg', 'gif'];

    foreach($images['name'] as $i => $filename) {
        $ext = substr($filename, strrpos($filename, '.') + 1);
        if (in_array($ext, $allowedExt)) {
            $tmp_name = $images['tmp_name'][$i];
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