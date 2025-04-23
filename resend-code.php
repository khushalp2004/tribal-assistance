<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function resend_email_verify($name,$email,$verify_token){
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
    $mail->Subject="Resend Email Verification from Tribal Assistance";

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

if(isset($_POST['resend_email_verify_btn'])){
    if(!empty(trim($_POST['email']))){
        $email=mysqli_real_escape_string($conn,$_POST['email']);
        $checkemail_query="SELECT * FROM users WHERE email='$email' LIMIT 1";
        $checkemail_query_run=mysqli_query($conn,$checkemail_query);

        if(mysqli_num_rows($checkemail_query_run)>0){
            $row=mysqli_fetch_array($checkemail_query_run);
            if($row['verify_status']=="0"){
                $name=$row['name'];
                $email=$row['email'];
                $verify_token=$row['verify_token'];
                resend_email_verify($name,$email,$verify_token);
                $_SESSION['status']="Verification link has been sent to your email";
                header("Location: https://mail.google.com/mail");
                exit(0);
            }else{
                $_SESSION['status']="Already verified";
                header("Location: login.php");
                exit(0);    
            }
        }else{
            $_SESSION['status']="Email is not registered";
            header("Location: register.php");
            exit(0);
        }
    }else{
        $_SESSION['status']="Please enter the email field";
        header("Location: resend-email-verification.php");
        exit(0);
    }
}
?>