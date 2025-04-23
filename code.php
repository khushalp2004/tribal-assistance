<?php 
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($name,$email,$verify_token){
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

    $mail->setFrom("tribaldevelopmentssip@gmail.com",$name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject="Email Verification from Tribal Assistance";

    $email_template="
        <h2>You have registered with Tribal Assistance</h2>
        <h5>Verify your email to login with the given link</h5>
        <br/><br/>
        <a href='http://localhost/tribal-assistance/verify-email.php?token=$verify_token'>Click Me </a>
    ";

    $mail->Body=$email_template;
    $mail->send();
    // echo "Message sent";
}
if(isset($_POST['register_btn'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $city=$_POST['city'];
    $language=$_POST['language'];
    $password=$_POST['password'];
    $verify_token=md5(rand());

    //email exists or not
    $check_email_query="SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run=mysqli_query($conn, $check_email_query);

    if(mysqli_num_rows($check_email_query_run)>0){
        $_SESSION['status']="Email already exists";
        header("Location: register.php");
    }
    else{
        ////insert user
        $query="INSERT INTO users(name,email,city,language,password,verify_token) VALUES ('$name','$email','$city','$language','$password','$verify_token')";
        $query_run=mysqli_query($conn,$query);

        if($query_run){
            sendemail_verify("$name","$email","$verify_token");
            $_SESSION['status']='Registration successfull! Verify your email';
            // echo"<script>alert('Registration successfull! Verify your Email');</script>";
            // header("location: https://mail.google.com/mail");
            header("Location: register.php");
        }else{
            // $_SESSION['status']='Registration failed';
            echo"<script>alert('Registration Failed!');</script>";
            // header("Location: register.php");
        }
    }
}