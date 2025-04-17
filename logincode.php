<?php
session_start();
include('dbcon.php');
if(isset($_POST['login_btn'])){
    if(!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))){
        $email=mysqli_real_escape_string($conn,$_POST['email']);
        $password=mysqli_real_escape_string($conn,$_POST['password']);

        $login_query="SELECT * FROM users WHERE email='$email' AND password='$password'";
        $login_query_run=mysqli_query($conn,$login_query);

        if(mysqli_num_rows($login_query_run)>0){
            $row=mysqli_fetch_array($login_query_run);
            if($row['verify_status']=="1"){
                $_SESSION['authenticated']=TRUE;
                $_SESSION['auth_user']=[
                    'username'=>$row['name'],
                    'email'=>$row['email'],
                    'language'=>$row['language'],
                    'city'=>$row['city'],
                    'verify_status'=>$row['verify_status'],
                    'id'=>$row['id']

                ];
                $_SESSION['status']='You are logged in successfully';
                header("Location: index.php");
                exit(0);
            }else{
                $_SESSION['status']="Please verify to login";
                header("Location: login.php");
                exit(0);
            }
        }else{
            $_SESSION['status']="Invalid Credentials";
            header("Location: login.php");
            exit(0);
        }
    }else{
        $_SESSION['status']="All fields are mandatory";
        header("Location: login.php");
        exit(0);
    }
    
}
?>