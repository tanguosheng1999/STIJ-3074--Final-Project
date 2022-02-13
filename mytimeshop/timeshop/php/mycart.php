<?php
include_once("dbconnect.php");
session_start();

if (isset($_SESSION['sessionid'])) {
    $useremail = $_SESSION['user_email'];
}else{
   echo "<script>alert('Please login or register')</script>";
   echo "<script> window.location.replace('login.php')</script>";
}
$sqlcart = "SELECT * FROM tbl_carts WHERE user_email = '$useremail'";
$stmt = $conn->prepare($sqlcart);
$stmt->execute();
$number_of_rows = $stmt->rowCount();
if ($number_of_rows>0){
   if (isset($_GET['submit'])) {
    if ($_GET['submit'] == "add"){
        $watchid = $_GET['watchid'];
        $qty = $_GET['qty'];
        $cartqty = $qty + 1 ;
        $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND watch_id = '$watchid'";
        $conn->exec($updatecart);
        echo "<script>alert('Cart updated')</script>";
    }
    if ($_GET['submit'] == "remove"){
        $watchid = $_GET['watchid'];
        $qty = $_GET['qty'];
        if ($qty == 1){
            $updatecart = "DELETE FROM `tbl_carts` WHERE user_email = '$useremail' AND watch_id = '$watchid'";
            $conn->exec($updatecart);
            echo "<script>alert('watch removed')</script>";
        }else{
            $cartqty = $qty - 1 ;
            $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND watch_id = '$watchid'";
            $conn->exec($updatecart);    
            echo "<script>alert('Removed')</script>";
        }
        
    }
} 
}else{
    echo "<script>alert('No item in your cart')</script>";
    echo "<script> window.location.replace('index.php')</script>";
}



$stmtqty = $conn->prepare("SELECT * FROM tbl_carts INNER JOIN tbl_watchs ON tbl_carts.watch_id = tbl_watchs.watch_id WHERE tbl_carts.user_email = '$useremail'");
$stmtqty->execute();
$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
foreach ($rowsqty as $carts) {
   $carttotal = $carts['cart_qty'] + $carttotal;
}

function subString($str)
{
    if (strlen($str) > 15)
    {
        return $substr = substr($str, 0, 15) . '...';
    }
    else
    {
        return $str;
    }
}

?>


<!DOCTYPE html>
<html>
  <title>Time Shop</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <script src="../js/script.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <body>
    <!-- Sidebar (hidden by default) -->
    <nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:40%;min-width:300px" id="mySidebar">
      <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button">Close Menu</a>
        <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Login</a>
        <a href="register.php" onclick="w3_close()" class="w3-bar-item w3-button">Register</a>
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Watchs</a>
        <a href="mycart.php" onclick="w3_close()" class="w3-bar-item w3-button" id = "carttotalida">Carts (<?php echo $carttotal?>)</a>
        <a href="mypayment.php" onclick="w3_close()" class="w3-bar-item w3-button">Payment</a>
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Logout</a>
    </nav>
    
    <!-- Top menu -->
    <div class="w3-top">
      <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
        <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
        <div class="w3-right w3-padding-16" id = "carttotalidb">Cart (<?php echo $carttotal?>)</div>
        <div class="w3-center w3-padding-16">Time Shop</div>
      </div>
    </div>
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
         <div class="w3-grid-template">
             <?php
             
             $total_payable = 0.00;
                foreach ($rowsqty as $watchs){
                    $watchid = $watchs['watch_id'];
                    $watch_name = subString($watchs['watch_name']);
                    $watch_number = $watchs['watch_number'];
                    $watch_price = $watchs['watch_price'];
                    $watch_qty = $watchs['cart_qty'];
                    $watch_total = $watch_qty * $watch_price;
                    $total_payable = $watch_total + $total_payable;
                    echo "<div class='w3-center w3-padding-small' id='watchcard_$watchid'><div class = 'w3-card w3-round-large'>
                    <div class='w3-padding-small'><a href='watch_details.php?watchid=$watchid'><img class='w3-container w3-image' 
                    src=../images/watchs/$watch_number.png onerror=this.onerror=null;this.src='../images/watchs/default.png'></a></div>
                    <b>$watch_name</b><br>RM $watch_price/unit<br>
                    <input type='button' class='w3-button w3-red' id='button_id' value='-' onClick='removeCart($watchid,$watch_price);'>
                    <label id='qtyid_$watchid'>$watch_qty</label>
                    <input type='button' class='w3-button w3-green' id='button_id' value='+' onClick='addCart($watchid,$watch_price);'>
                    <br>
                    <b><label id='watchprid_$watchid'> Price: RM $watch_total</label></b><br></div></div>";
                }
             ?>
        </div>
        <?php 
        echo "<div class='w3-container w3-padding w3-block w3-center'><p><b><label id='totalpaymentid'> Total Amount Payable: RM $total_payable</label>
        </b></p><a href='payment.php?email=$useremail&amount=$total_payable' class='w3-button w3-round w3-blue'> Pay Now </a> </div>";
        ?>
        
    <footer class="w3-row-padding w3-padding-32">
      <hr></hr>
      <p class="w3-center">TimeShop&reg;</p>
      
    </footer>
    <script>
    function addCart(watchid, watch_price) {
        jQuery.ajax({
                type: "GET",
                url: "mycartajax.php",
                data: {
                        watchid: watchid,
                        submit: 'add',
                        watchprice: watch_price
                },
		        cache: false,
		        dataType: "json",
	    	    success: function(response) {
			            var res = JSON.parse(JSON.stringify(response));
			            console.log(res.data.carttotal);
			            if (res.status = "success") {
				            var watchid = res.data.watchid;
			                document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
				            document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
				            document.getElementById("qtyid_" + watchid).innerHTML = res.data.qty;
				            document.getElementById("watchprid_" + watchid).innerHTML = "Price: RM " + res.data.watchprice;
				            document.getElementById("totalpaymentid").innerHTML = "Total Amount Payable: RM " + res.data.totalpayable;
			            } else {
				                alert("Failed");
			            }

		        }
	    });
    }
    
    function removeCart(watchid, watch_price) {
	        jQuery.ajax({
		            type: "GET",
		            url: "mycartajax.php",
		            data: {
			                watchid: watchid,
			                submit: 'remove',
			                watchprice: watch_price
		            },
		            cache: false,
		            dataType: "json",
		            success: function(response) {
			                var res = JSON.parse(JSON.stringify(response));
			                if (res.status = "success") {
				                    console.log(res.data.carttotal);
				                    if (res.data.carttotal == null || res.data.carttotal == 0){
				                        alert("Cart empty");
				                        window.location.replace("index.php");
				            }else{
				            var watchid = res.data.watchid;
				            document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
				            document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
				            document.getElementById("qtyid_" + watchid).innerHTML = res.data.qty;
				            document.getElementById("watchprid_" + watchid).innerHTML = "Price: RM " + res.data.watchprice;
				            document.getElementById("totalpaymentid").innerHTML = "Total Amount Payable: RM " + res.data.totalpayable;
				            console.log(res.data.qty);
				            if (res.data.qty==null){
				                var element = document.getElementById("watchcard_"+watchid);
				                element.parentNode.removeChild(element);
				            }    
				            }
				
			        } else {
				            alert("Failed");
			        }

		    }
	});
}
</script>
    
  </body>
</html>