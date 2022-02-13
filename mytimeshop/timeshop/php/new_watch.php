<?php
if (isset($_POST["submit"])) {
    include_once("dbconnect.php");
    $name = $_POST["name"];
    $brand = $_POST["brand"];
    $watch = $_POST["watch"];
    $price = $_POST["price"];
    $description = addslashes($_POST["description"]);
    $colour = $_POST["colour"];
    $strap = $_POST["strap"];
    $sqlinsert = "INSERT INTO `tbl_watchs`(`watch_name`, `watch_brand`, `watch_number`, `watch_price`, `watch_desc`, `watch_colour`, `watch_strap`) VALUES('$name','$brand', '$watch', '$price', '$description', '$colour', '$strap')";
    try {
        $conn->exec($sqlinsert);
        if (file_exists($_FILES["fileToUpload"]["tmp_name"]) || is_uploaded_file($_FILES["fileToUpload"]["tmp_name"])) {
            uploadImage($watch);
        }
        echo "<script>alert('Registration successful')</script>";
        echo "<script>window.location.replace('new_watch.php')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Registration failed')</script>";
        echo "<script>window.location.replace('new_watch.php')</script>";
    }
}

function uploadImage($watch)
{
    $target_dir = "../images/watchs/";
    $target_file = $target_dir . $watch . ".png";
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
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
        <a href="index.php" onclick="w3_close()" class="w3-bar-item w3-button">Watchs</a>
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
        <form class="w3-container" action="new_watch.php" method="post" enctype="multipart/form-data" onsubmit="return confirmDialog()">
            <div class="w3-container w3-blue">
                <h2>New Watch</h2>
            </div>
             <div class="w3-container w3-border w3-center w3-padding">
                    <img class="w3-image w3-round w3-margin" src="../images/watch.jpeg" style="height:100%;width:100%;max-width:330px"><br>
                    <input type="file" onchange="previewFile()" name="fileToUpload" id="fileToUpload"><br>
                </div>
            <br>
            <label>Watch Name</label>
            <input class="w3-input" name="name" id="idname" type="text" required>

            <label>Brand</label>
            <input class="w3-input" name="brand" id="idbrand" type="text" required>

            <label>Watch Number</label>
            <input class="w3-input" name="watch" id="idwatch" type="text" required>

            <label>Price</label>
            <input class="w3-input" name="price" id="idprice" type="text" required>
            <p>
            <label>Description</label>
            <textarea class="w3-input w3-border" id="iddesc" name="description" rows="4" cols="50" width="100%" placeholder="Watch Description" required></textarea>
            </p>
            <label>Colour</label>
            <input class="w3-input" name="colour" id="idcolour" type="text" required>
            <p>
            <select class="w3-select" name="strap" id="idstrap" required>
                <option value="" disabled selected>Choose Strap</option>
                <option value="Press Mesh">Press Mesh</option>
                <option value="Rubber Stra">Rubber Strap</option>
                <option value="Leather Strap">Leather Strap</option>
            </select></p>
            <div class="w3-row">
                <input class="w3-input w3-border w3-block w3-blue w3-round" type="submit" name="submit" value="Submit">
            </div>
        </form>
    </div>
    <footer class="w3-row-padding w3-padding-32">
        <hr>
        </hr>
        <p class="w3-center">TimeShop&reg;</p>
    </footer>

</body>

</html>