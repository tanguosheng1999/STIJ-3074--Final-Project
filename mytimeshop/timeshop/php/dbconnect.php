<?php
$servername = "localhost";
$username = "tgscom1_mytimeshopdbadmin";
$password = "GTZMn81t,&GC";
$dbname = "tgscom1_mytimeshopdb";

try {
   $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}
?>