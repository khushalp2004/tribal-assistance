<?php
session_start();
if(!isset($_SESSION['authenticated'])){
    $_SESSION['status']="Please login to access the dashboard";
    header("Location: login.php");
    exit(0);
}
?>