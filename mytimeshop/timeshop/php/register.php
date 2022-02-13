<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '/home11/tgscom1/public_html/PHPMailer/src/Exception.php';
require '/home11/tgscom1/public_html/PHPMailer/src/PHPMailer.php';
require '/home11/tgscom1/public_html/PHPMailer/src/SMTP.php';

if(isset($_POST['submit'])){
    include_once("dbconnect.php");
    $email = $_POST['email'];
    $password = sha1($_POST['password']);
    $otp = rand(10000,99999);
    $sqlregister ="INSERT INTO `tbl_users`(`user_email`, `user_password`, `user_otp`) VALUES ('$email','$password','$otp')";
    try {
        $conn->exec($sqlregister);
        sendMail($email,$otp);
        echo "<script>alert('Registration successful')</script>";
        echo "<script>window.location.replace('login.php')</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Registration failed')</script>";
        echo "<script>window.location.replace('register.php')</script>";
    }
}
function sendMail($email,$otp){
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;                                               //Disable verbose debug output
    $mail->isSMTP();                                                    //Send using SMTP
    $mail->Host       = 'mail.tgs1999.com';                          //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                           //Enable SMTP authentication
    $mail->Username   = 'guosheng0000@tgs1999.com';  
    $mail->Password   = 'Tgs@173098';                                 //g(v!@D([]7UP$K7wty  /  T0MizfNmCddW
    $mail->SMTPSecure = 'tls';         
    $mail->Port       = 587;
    $from = "guosheng0000@tgs1999.com";
    $to = $email;
    $subject = 'MyTimeShop - Please verify your account';
    $message = "<h2>Welcome to Time Shop App</h2> <p>Thank you for registering your account with us. To complete your registration please click the following.<p>
    <p><button><a href ='https://tgs1999.com/mytimeshop/timeshop/php/verifyaccount.php?email=$email&otp=$otp'>Verify Here</a></button>";
    
    $mail->setFrom($from,"Guosheng0000");
    $mail->addAddress($to);                                             //Add a recipient
    
    //Content
    $mail->isHTML(true);                                                //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->send();
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
        <div class="w3-right w3-padding-16">Cart</div>
        <div class="w3-center w3-padding-16">Time Shop</div>
      </div>
    </div>
    <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">
      <div class="w3-row w3-card">
    <div class="w3-half w3-center">
        <img class="w3-image w3-margin w3-center" style="height: 100%; width:100%; max-width:400px" src="../images/login.png">
    </div>
    <div class="w3-half w3-container">
        <form name="RegisterForm" class="w3-container" action="register.php" method="post">
        <div class="w3-row w3-center">
                <h4 class="w3-center">Register New Account</h4>
        </div>
            <p>
                <label class="w3-text-blue">
                    <b>Email</b>
                </label>
                <input class="w3-input w3-border w3-round" name="email" type="email" id="idemail" required>
            </p>
            <p>
                <label class="w3-text-blue">
                    <b>Password</b>
                </label>
                <input class="w3-input w3-border w3-round" name="password" type="password" id="idpass" required>
            </p>
            <p>
                <label class="w3-text-blue">
                    <b>Reenter Password</b>
                </label>
                <input class="w3-input w3-border w3-round" name="passwordb" type="password" id="idpassb" required>
            </p>
            <p>
                <button class="w3-btn w3-round w3-blue w3-block" name="submit">Register</button>
            </p>
            <p><a href="login.php" style="text-decoration:none">Already registered. Login here</a><br>
        </form>
    </div>
</div>
    </div>
    <footer class="w3-row-padding w3-padding-32">
      <hr>
      </hr>
      <p class="w3-center">TimeShop&reg;</p>
    </footer>
  </body>
</html>