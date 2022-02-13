<?php
include_once ("dbconnect.php");
session_start();
$useremail = "Guest";
if (isset($_SESSION['sessionid']))
{
    $useremail = $_SESSION['user_email'];
}
$carttotal = 0;
if (isset($_GET['submit']))
{
    include_once ("dbconnect.php");
    if ($_GET['submit'] == "cart")
    {
        if ($useremail != "Guest")
        {
            $watchid = $_GET['watchid'];
            $cartqty = "1";
            $stmt = $conn->prepare("SELECT * FROM tbl_carts WHERE user_email = '$useremail' AND watch_id = '$watchid'");
            $stmt->execute();
            $number_of_rows = $stmt->rowCount();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $rows = $stmt->fetchAll();
            if ($number_of_rows > 0)
            {
                foreach ($rows as $carts)
                {
                    $cartqty = $carts['cart_qty'];
                }
                $cartqty = $cartqty + 1;
                $updatecart = "UPDATE `tbl_carts` SET `cart_qty`= '$cartqty' WHERE user_email = '$useremail' AND watch_id = '$watchid'";
                $conn->exec($updatecart);
                echo "<script>alert('Cart updated')</script>";
                echo "<script> window.location.replace('index.php')</script>";
            }
            else
            {
                $addcart = "INSERT INTO `tbl_carts`(`user_email`, `watch_id`, `cart_qty`) VALUES ('$useremail','$watchid','$cartqty')";
                try
                {
                    $conn->exec($addcart);
                    echo "<script>alert('Success')</script>";
                    echo "<script> window.location.replace('index.php')</script>";
                }
                catch(PDOException $e)
                {
                    echo "<script>alert('Failed')</script>";
                }
            }

        }
        else
        {
            echo "<script>alert('Please login or register')</script>";
            echo "<script>window.location.replace('login.php')</script>";
        }
    }
    if ($_GET['submit'] == "search")
    {
        $search = $_GET['search'];
        $sqlquery = "SELECT * FROM tbl_watchs WHERE watch_name LIKE '%$search%'";
    }
}
else
{
    $sqlquery = "SELECT * FROM tbl_watchs WHERE watch_qty = 0 ";
}

$stmtqty = $conn->prepare("SELECT * FROM tbl_carts WHERE user_email = '$useremail'");
$stmtqty->execute();
$resultqty = $stmtqty->setFetchMode(PDO::FETCH_ASSOC);
$rowsqty = $stmtqty->fetchAll();
foreach ($rowsqty as $carts)
{
    $carttotal = $carts['cart_qty'] + $carttotal;
}

$results_per_page = 10;
if (isset($_GET['pageno']))
{
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
}
else
{
    $pageno = 1;
    $page_first_result = 0;
}

$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
$number_of_result = $stmt->rowCount();
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlquery = $sqlquery . " LIMIT $page_first_result , $results_per_page";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="../js/script.js"></script>

<body>
    <!-- Sidebar (hidden by default) -->
    <nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:40%;min-width:300px" id="mySidebar">
        <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button">Close Menu</a>
        <a href="login.php" onclick="w3_close()" class="w3-bar-item w3-button">Login</a>
        <a href="register.php" onclick="w3_close()" class="w3-bar-item w3-button">Register</a>
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Watchs</a>
        <a href="mycart.php" onclick="w3_close()" class="w3-bar-item w3-button">Carts</a>
        <a href="mypayment.php" onclick="w3_close()" class="w3-bar-item w3-button">Payment</a>
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Logout</a>
    </nav>

    <!-- Top menu -->
    <div class="w3-top">
        <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
            <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
             <a href="mycart.php"> <div class="w3-right w3-padding-16" id = "carttotalidb" >Cart (<?php echo $carttotal?>)</div></a>
            <div class="w3-center w3-padding-16">Time Shop</div>
        </div>
    </div>
    
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        <div class="w3-container w3-card w3-padding w3-row w3-round" style="width:100%">
            <form class="w3-container" action="index.php" method="get">
                <div class="w3-twothird"><input class="w3-input w3-border w3-round w3-center" placeholder = "Enter your search term here" type="text" name="search"></div>
                <div class="w3-third"><input class="w3-input w3-border w3-blue w3-round" type="submit" name="submit" value="search"></div>
            </form>
        </div>
        <hr>
    
        <div class="w3-grid-template">
            <?php
            $cart = "cart";
                foreach ($rows as $watchs){
                    $watchid = $watchs['watch_id'];
                    $watch_name = subString($watchs['watch_name']);
                    $watch_brand = $watchs['watch_brand'];
                    $watch_number = $watchs['watch_number'];
                    $watch_price = $watchs['watch_price'];
                    $watch_desc = $watchs['watch_desc'];
                    $watch_colour = $watchs['watch_colour'];
                    $watch_strap = $watchs['watch_strap'];
                    $data = $watchs['data'];
                    $watch_qty = $watchs['watch_qty'];
                    
                    echo "<div class='w3-center w3-padding-small'><div class ='w3-card w3-round-large'>
                    <div class='w3-padding-small'><a href='watch_details.php?watchid=$watchid'><img class='w3-container w3-image'
                    src=../images/watchs/$watch_number.png onerror=this.onerror=null;this.src='../images/watchs/default.png'></a></div>
                    <b>$watch_name</b><br>$watch_brand<br>RM $watch_price<br>
                    <input type='button' class='w3-btn w3-blue w3-round'  id='button_id' value='Add to Cart' onClick='addCart($watchid);'><br><br>
                    </div></div>";
                    //<a href='index.php?bookid=$bookid&submit=$cart' class='w3-btn w3-blue w3-round'>Add to Cart</a><br><br>
                }
            ?>
        </div>
    </div>
    <?php
    $num = 1;
    if ($pageno == 1) {
        $num = 1;
    } else if ($pageno == 2) {
        $num = ($num) + $results_per_page;
    } else {
        $num = $pageno * $results_per_page - 9;
    }
    echo "<div class='w3-container w3-row'>";
    echo "<center>";
    for ($page = 1; $page <= $number_of_page; $page++) {
        echo '<a href = "index.php?pageno=' . $page . '" style=
        "text-decoration: none">&nbsp&nbsp' . $page . ' </a>';
    }
    echo " ( " . $pageno . " )";
    echo "</center>";
    echo "</div>";
    ?>
    
    <footer class="w3-row-padding w3-padding-32">
        <hr></hr>
        <p class="w3-center">TimeShop&reg;</p>
        
    </footer>

<script>
 function addCart(watchid) {
	jQuery.ajax({
		type: "GET",
		url: "updatecartajax.php",
		data: {
			watchid: watchid,
			submit: 'add',
		},
		cache: false,
		dataType: "json",
		success: function(response) {
		    var res = JSON.parse(JSON.stringify(response));
		    console.log("HELLO ");
			    console.log(res.status);
			    if (res.status == "success") {
			        console.log(res.data.carttotal);
				    //document.getElementById("carttotalida").innerHTML = "Cart (" + res.data.carttotal + ")";
				    document.getElementById("carttotalidb").innerHTML = "Cart (" + res.data.carttotal + ")";
				    alert("Success");
			    }
			    if (res.status == "failed") {
			        alert("Please login or register account");
			        window.location.replace('login.php')
			    }
			

		    }
	});
}
</script>
</body>

</html>