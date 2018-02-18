<?php
$db = getConnect();
checkAdmin($db);

if (isset($_POST['id'])) {
    $priceCalc = (float) $_POST['price'];
    
    $id = (int) $_POST['id'];
    $query = mysqli_query($db, "
		SELECT price FROM calculator
		WHERE id = $id
	");
    
    $price = mysqli_fetch_assoc($query);

    if($priceCalc !== 0 AND $priceCalc != $price['price']) {
        $result = mysqli_query($db, "
            UPDATE calculator SET
            price = '$priceCalc'
            WHERE id = {$id}
        ");

        die($result);
	}
}

die('0');