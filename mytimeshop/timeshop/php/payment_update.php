<?php
error_reporting(0);
include_once("dbconnect.php");
$useremail = $_GET['email'];
// $mobile = $_GET['mobile'];
$amount = $_GET['amount'];

$data = array(
    'id' =>  $_GET['billplz']['id'],
    'paid_at' => $_GET['billplz']['paid_at'] ,
    'paid' => $_GET['billplz']['paid'],
    'x_signature' => $_GET['billplz']['x_signature']
);

$paidstatus = $_GET['billplz']['paid'];
if ($paidstatus=="true"){
    $paidstatus = "Success";
}else{
    $paidstatus = "Failed";
}
$receiptid = $_GET['billplz']['id'];
$signing = '';
foreach ($data as $key => $value) {
    $signing.= 'billplz'.$key . $value;
    if ($key === 'paid') {
        break;
    } else {
        $signing .= '|';
    }
}
 
 
$signed= hash_hmac('sha256', $signing, 'S-iAIHDiLiH9IcDXahkaTZbg');
if ($signed === $data['x_signature']) {
    if ($paidstatus == "Success"){ //payment success
        $sqlinsertpayment = "INSERT INTO `tbl_payments`(`payment_receipt`, `payment_email`, `payment_paid`) VALUES ('$receiptid','$useremail','$amount')";
        $sqldeletecart = "DELETE FROM `tbl_carts` WHERE user_email = '$useremail'";
         try {
        $conn->exec($sqlinsertpayment);
        $stmt = $conn->prepare($sqldeletecart);
        $stmt->execute();
            echo "<script>alert('Payment successful')</script>";
              echo "<script>window.location.replace('https://tgs1999.com/mytimeshop/timeshop/php/index.php')</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Payment failed')</script>";
             echo "<script>window.location.replace('https://tgs1999.com/mytimeshop/timeshop/php/index.php')</script>";
        }
    }
    else 
    {
        echo 'Payment Failed!';
    }
}
?>