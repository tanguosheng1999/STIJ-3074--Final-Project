<?php
error_reporting(0);
session_start();
if (isset($_SESSION['sessionid']))
{
    $useremail = $_SESSION['user_email'];
}else{
    echo "<script>alert('Please login or register')</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

$email = $_GET['email']; //email
$amount = $_GET['amount']; 

$api_key = '94c5d023-0229-4ec8-8c46-4b6646972eec';
$collection_id = 'jadzmsf_';
$host = 'https://billplz-staging.herokuapp.com/api/v3/bills';

$data = array(
          'collection_id' => $collection_id,
          'email' => $useremail,
          'mobile' => '01110740317',
          'name' => 'Lim',
          'amount' => ($amount + 1) * 100, // RM20
		  'description' => 'Payment for order by '.$email,
          'callback_url' => "http://tgs1999.com/mytimeshop/timeshop/php/return_url",
          'redirect_url' => "http://tgs1999.com/mytimeshop/timeshop/php/payment_update.php?email=$email&amount=$amount" 
);

$process = curl_init($host );
curl_setopt($process, CURLOPT_HEADER, 0);
curl_setopt($process, CURLOPT_USERPWD, $api_key . ":");
curl_setopt($process, CURLOPT_TIMEOUT, 30);
curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($process, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($process, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data) ); 

$return = curl_exec($process);
curl_close($process);

$bill = json_decode($return, true);
header("Location: {$bill['url']}");
?>