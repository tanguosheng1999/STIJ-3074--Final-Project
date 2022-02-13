<?php
include_once ("dbconnect.php");
session_start();
if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['user_email'];
}else{
   echo "<script>alert('Please login or register')</script>";
   echo "<script> window.location.replace('login.php')</script>";
}
$sqlpayment = "SELECT * FROM tbl_payments WHERE payment_email = '$useremail' ORDER BY payment_date DESC";
$stmt = $conn->prepare($sqlpayment);
$stmt->execute();
$number_of_rows = $stmt->rowCount();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();


?>
<!DOCTYPE html>
<html>
   <title>Time Shop</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
   <link rel="stylesheet" type="text/css" href="../css/style.css">
   <script src="../js/script.js"></script>
   <body >
       
      <!-- Sidebar (hidden by default) -->
      <nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:20%;min-width:200px" id="mySidebar">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button">Close Menu</a>
        <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Login</a>
        <a href="register.php" onclick="w3_close()" class="w3-bar-item w3-button">Register</a>
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">watchs</a>
        <a href="mycart.php" onclick="w3_close()" class="w3-bar-item w3-button">Carts</a>
        <a href="mypayment.php" onclick="w3_close()" class="w3-bar-item w3-button">Payment</a>
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Logout</a>
      </nav>
      <!-- Top menu -->
      <div class="w3-top">
         <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
            <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
            <div class="w3-center w3-padding-16">Time Shop</div>
         </div>
      </div>
      <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
          <div class="w3-grid-template">
               <?php
               $totalpaid = 0.0;
                foreach ($rows as $payments){
                    $paymentreceipt = $payments['payment_receipt'];
                    $paymentpaid = $payments['payment_paid'];
                    $totalpaid = $paymentpaid + $totalpaid;
                    $paymentdate = date_format(date_create($payments['payment_date']),"d/m/Y h:i A");
                     echo "<div class='w3-left w3-padding-small'><div class = 'w3-card w3-round-large w3-padding'>
                    <div class='w3-container w3-center w3-padding-small'><b>Receipt ID: $paymentreceipt</b></div><br>
                    Paid: RM $paymentpaid<br>Date: $paymentdate<br>
                    </div></div>";
                }
            echo "</div><br><hr><div class='w3-container w3-left'><h4>Your Orders</h4><p>Email: $useremail<br>Total Paid: RM $totalpaid<p></div>";
               ?>
          </div>
      </div>
      <footer class="w3-row-padding w3-padding-32">
         <hr>
         </hr>
         <p class="w3-center">TimeShop&reg;</p>
      </footer>
</body>
</html>