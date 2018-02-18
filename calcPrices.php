<?php


$db = getConnect();

$result = 0;

$query = mysqli_query($db, "
		SELECT * FROM calculator
		
	");
$rows = mysqli_fetch_all($query, MYSQLI_ASSOC);

if(!empty($_POST['square']) && !empty($_POST['floor'])){
    $sq =  escape($_POST['square'], $db);
    $floor = (int) $_POST['floor'];
    
    $materialPrice = array_reduce($rows, priceReduce($_POST['material']), 0);

    $basementPrice = array_reduce($rows, priceReduce($_POST['basement']), 0);
    
    $outDecorPrice = array_reduce($rows, priceReduce($_POST['out-decor']), 0);
    
    $roofPrice = array_reduce($rows, priceReduce($_POST['roof']), 0);
    
    

    $inDecorPrice = array_reduce($rows, priceReduce($_POST['in-decor']), 0);
    if (isset($_POST['add'])){
        $addPrice = array_reduce($rows, priceReduce($_POST['add']), 0);
    } else {
        $addPrice = 0;
    }
    $result = $sq*($materialPrice + $outDecorPrice + $inDecorPrice) + ($sq/$floor*$basementPrice) + ($sq/$floor*$roofPrice) +($sq*$addPrice);
}

echo number_format($result, 2, ".", " ");

die;
