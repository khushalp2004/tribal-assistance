<?php
$host="localhost";
$username="root";
$password="";
$dbname="user_data";
    $conn=new mysqli($host,$username,$password);
    if($conn->connect_error){
        die("connection failed");
    }

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
        // echo "Database created successfully.<br>";
    } else {
        die("Error creating database: " . $conn->error);
    }
    $conn->select_db($dbname);
?>