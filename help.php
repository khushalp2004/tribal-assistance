<?php
session_start();
include("authentication.php");
$conn = new mysqli("localhost", "root", "", "user_data");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($email,$message){
    $mail = new PHPMailer(true);
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host='smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth=true;                                   //Enable SMTP authentication
    $mail->Username='tribaldevelopmentssip@gmail.com';                     //SMTP username
    $mail->Password='dvjj cvbf ymmw smgc'; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
    $mail->Port= 465; 

    // $mail->SMTPSecure="tls";
    // $mail->Port=587;

    $mail->setFrom("tribaldevelopmentssip@gmail.com",$email);
    $mail->addAddress("tribaldevelopmentssip@gmail.com");

    $mail->isHTML(true);
    $mail->Subject="{$email} needs your help";

    $email_template="
        <h3>Email: {$email} needs your help</h3>
        <h3>Below is the problem</h3>
        <h4>Message: {$message}</h4>
    ";

    $mail->Body=$email_template;
    $mail->send();
    // echo "Message sent";
}
$language=$_SESSION['auth_user']['language'];
$user_id=$_SESSION['auth_user']['id'];
$email=$_SESSION['auth_user']['email'];

if(isset($_POST['submit'])){
    $email=$_SESSION['auth_user']['email'];
    $message=$_POST['message'];

    $sql="INSERT INTO contact(user_id,email,message) VALUES ('$user_id','$email','$message')";
    if ($conn->query($sql) === TRUE) {
        // echo "Contact send to the team";
        sendemail_verify($email,$message);
        header("location: index.php");
        // echo "<script>alert('Contacted');</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            }
}
$conn->close();
?>