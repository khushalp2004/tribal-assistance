<?php
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function send_password($get_name,$get_email,$token){
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

    $mail->setFrom("tribaldevelopmentssip@gmail.com",$get_name);
    $mail->addAddress($get_email);

    $mail->isHTML(true);
    $mail->Subject="Reset Password Notification";

    $email_template="
        <h2>Hello {$get_name}</h2>
        <h3>You are receiving this mail because we received a password reset request for your acount.</h3>
        <br/><br/>
        <a href='http://localhost/tribal-assistance/password-change.php?token=$token&email=$get_email'>Click Me </a>
    ";

    $mail->Body=$email_template;
    $mail->send();
    // echo "Message sent";
}
if(isset($_POST['password_reset_link'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $token=md5(rand());
    
    $check_email="SELECT email FROM users WHERE email='$email' LIMIT 1 ";
    $check_email_run=mysqli_query($conn,$check_email);
    if(mysqli_num_rows($check_email_run)>0){
        $row=mysqli_fetch_array($check_email_run);
        $get_name=$row['name'];
        $get_email=$row['email'];

        $update_token="UPDATE users SET verify_token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run=mysqli_query($conn,$update_token);

        if($update_token_run){
            send_password($get_name,$get_email,$token);
            $_SESSION['status']="Reset Password link is sent";
            header("Location: https://mail.google.com/mail");
            exit(0);
        }else{
            $_SESSION['status']="Something went wrong. #1";
            header("Location: password-reset.php");
            exit(0);
        }
    }else{
        $_SESSION['status']="No email found";
        header("Location: password-reset.php");
        exit(0);
    }
}


if(isset($_POST['password_update'])){
    $email=mysqli_real_escape_string($conn,$_POST['email']);
    $new_password=mysqli_real_escape_string($conn,$_POST['new_password']);
    $confirm_password=mysqli_real_escape_string($conn,$_POST['confirm_password']);
    $token=mysqli_real_escape_string($conn,$_POST['password_token']);

    if(!empty($token)){
        if(!empty($email) && !empty($new_password) && !empty($confirm_password)){
            ///check token is valid or not
            $check_token="SELECT verify_token FROM users WHERE verify_token='$token' LIMIT 1";
            $check_token_run=mysqli_query($conn,$check_token);
            if(mysqli_num_rows($check_token_run)>0){
                if($new_password==$confirm_password){
                    $update_password="UPDATE users SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
                    $update_password_run=mysqli_query($conn,$update_password);
                    if($update_password_run){
                        $new_token=md5(rand());
                        $update_to_new_token="UPDATE users SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
                    $update_to_new_token_run=mysqli_query($conn,$update_to_new_token);
                        $_SESSION['status']="Password changed";
                        header("Location: login.php");
                        exit(0);
                    }else{
                        $_SESSION['status']="Password did not change.Something went Wrong";
                        header("Location: password-change.php?token=$token&email=$email");
                        exit(0);
                    }
                }else{
                    $_SESSION['status']="Password does not match";
                    header("Location: password-change.php?token=$token&email=$email");
                    exit(0);
                }
            }else{
                $_SESSION['status']="Token expired";
                header("Location: password-change.php?token=$token&email=$email");
                exit(0);
            }
        }else{
            $_SESSION['status']="All Fields are Mandatory";
            header("Location: password-change.php?token=$token&email=$email");
            exit(0);
        }
    }else{
        $_SESSION['status']="No token available";
        header("Location: password-change.php");
        exit(0);
    }
}


?>