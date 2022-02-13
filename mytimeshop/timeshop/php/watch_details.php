<?php
include_once ("dbconnect.php");
$watchid = $_GET['watchid'];
$sqlquery = "SELECT * FROM tbl_watchs WHERE watch_id = $watchid";
$stmt = $conn->prepare($sqlquery);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

foreach ($rows as $watchs)
{
    $watchid = $watchs['watch_id'];
    $watch_name = $watchs['watch_name'];
    $watch_brand = $watchs['watch_brand'];
    $watch_number = $watchs['watch_number'];
    $watch_price = $watchs['watch_price'];
    $watch_desc = $watchs['watch_desc'];
    $watch_colour = $watchs['watch_colour'];
    $watch_strap = $watchs['watch_strap'];
    $date = date_format(date_create($watchs['date']), 'd/m/y h:i A');;
}
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
            <div class="w3-right w3-padding-16">Mail</div>
            <div class="w3-center w3-padding-16">Time Shop</div>
        </div>
    </div>
    
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
        
        <div class="w3-row w3-card">
            <div class="w3-half w3-center">
                <img class="w3-image w3-margin w3-center" style="height:100%;width:100%;max-width:330px" src="../images/watchs/<?php echo $watch_number?>.png">
            </div>
        <div class="w3-half w3-container">
            <?php 
            echo "<h3 class='w3-center'><b>$watch_name</h3></b>
            <p>Watch Brand: $watch_brand<br>Watch_number: $watch_number<br>Watch Colour: $watch_colour<br>Watch Strap: $watch_strap<br>
            <p style='text-align: justify'>Description<br>$watch_desc</p>
            <p style='font-size:160%;'>RM $watch_price</p>
            <p> <a href='index.php?watchid=$watchid' class='w3-btn w3-blue w3-round'>Home</a><p><br>
            <p>Date added<br>$date</p>
            ";
            
            ?>
        </div>
        </div>
    </div>
    </div>
    <footer class="w3-row-padding w3-padding-32">
        <hr></hr>
        <p class="w3-center">TimeShop&reg;</p>
    </footer>

</body>
</html>