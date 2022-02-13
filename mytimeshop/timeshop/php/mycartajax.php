<?php
include_once("dbconnect.php");
session_start();

if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['user_email'];
}else{
    $response = array('status' => 'failed', 'data' => null);
    sendJsonResponse($response);
    return;
}
if (isset($_GET['submit'])) {
    $watchid = $_GET['watchid'];
    $watchprice = $_GET['watchprice'];
    $sqlqty = "SELECT * FROM tbl_carts WHERE user_email = '$useremail' AND watch_id = '$watchid'";
    $stmtsqlqty = $conn->prepare($sqlqty);
    $stmtsqlqty->execute();
    $resultsqlqty = $stmtsqlqty->setFetchMode(PDO::FETCH_ASSOC);
    $rowsqlqty = $stmtsqlqty->fetchAll();
    $watchcurqty = 0;
    foreach ($rowsqlqty as $watchs) {
        $watchcurqty = $watchs['cart_qty'] + $watchcurqty;
    }
    if ($_GET['submit'] == "add"){
        $cartqty = $watchcurqty + 1 ;
        $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND watch_id = '$watchid'";
        $conn->exec($updatecart);
    }
    if ($_GET['submit'] == "remove"){
        if ($watchcurqty == 1){
            $updatecart = "DELETE FROM `tbl_carts` WHERE user_email = '$useremail' AND watch_id = '$watchid'";
            $conn->exec($updatecart);
        }else{
            $cartqty = $watchcurqty - 1 ;
            $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND watch_id = '$watchid'";
            $conn->exec($updatecart);    
        }
    }
}


$stmtqty = $conn->prepare("SELECT * FROM tbl_carts INNER JOIN tbl_watchs ON tbl_carts.watch_id = tbl_watchs.watch_id WHERE tbl_carts.user_email = '$useremail'");
$stmtqty->execute();
//$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
$totalpayable = 0;
foreach ($rowsqty as $carts) {
   $carttotal = $carts['cart_qty'] + $carttotal;
   $watchpr = $carts['watch_price'] * $carts['cart_qty'];
   $totalpayable = $totalpayable + $watchpr;
}

$mycart = array();
$mycart['carttotal'] =$carttotal;
$mycart['watchid'] =$watchid;
$mycart['qty'] =$cartqty;
$mycart['watchprice'] = bcdiv($cartqty * $watchprice,1,2);
$mycart['totalpayable'] = bcdiv($totalpayable,1,2);


$response = array('status' => 'success', 'data' => $mycart);
sendJsonResponse($response);


function sendJsonResponse($sentArray)
{
    header('Content-Type: application/json');
    echo json_encode($sentArray);
}
?>