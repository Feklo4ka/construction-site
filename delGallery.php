<?php

$db = getConnect();
checkAdmin($db);

$id=(int)$_GET['id'];
$q = mysqli_query ($db, "
        SELECT name FROM gallery
        WHERE id = $id
");

$row = mysqli_fetch_assoc ($q);
$dir = __DIR__.'/projects/'.$row['name'];

if (file_exists($dir)) {
$files = array_diff(scandir($dir), array('.','..'));
   foreach ($files as $file) {
      unlink("$dir/$file");
   }
   rmdir($dir);
}
$query = mysqli_query($db, "
		DELETE FROM gallery
        WHERE id = $id
		
	");

header("Location: galleryControl");