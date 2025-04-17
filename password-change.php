<?php
session_start();   

if(isset($_SESSION['authenticated'])){
    $_SESSION['status']="You are already loggedin ";
    header("Location: dashboard.php");
    exit(0);
}

    $page_title="Change Password";
    include('includes/header.php');
    // include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                    if(isset($_SESSION['status'])){
                ?>
                    <div class="alert alert-success">
                        <h5><?=$_SESSION['status']?></h5>
                    </div>
                <?php
                        unset($_SESSION['status']);
                    }
                ?>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="password-reset-code.php" method="POST">
                            <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];}?>">
                            <div class="form- mb-3">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}?>">
                            </div>
                            <!-- <div class="form mb-3">
                                <label for="">Password</label>
                                <input type="password" name="new_password" id="password" class="form-control">
                            </div> -->
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control" id="password" required>
                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                     👁️
                                </button>
                            </div><br>
                            <!-- </div> -->
<label for="password">Confirm Password</label>
    <div class="input-group form mb-3">
        <input type="password" name="confirm_password" class="form-control" id="cpassword" required>
        <button type="button" class="btn btn-outline-secondary" id="togglePassword2">
            👁️
        </button>
    </div><br>
                            <!-- <div class="form mb-3">
                                <label for="">Confirm Password</label>
                                <input type="password" name="confirm_password" id="cpassword" class="form-control">
                            </div> -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" name="password_update">Change Password</button>
                                <a href="password-reset.php" class="float-end">Forgot your password?</a>
                            </div>
                            <hr>
                            <div class="form-group">
                                Did not receive verification email? <a href="resend-email-verification.php">Resend</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/password-change-speech.js"></script>
<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        let passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
        let passwordField2=document.getElementById("cpassword");
        passwordField2.type = passwordField2.type === "password" ? "text" : "password";
    });
    document.getElementById("togglePassword2").addEventListener("click", function () {
        let passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
        let passwordField2=document.getElementById("cpassword");
        passwordField2.type = passwordField2.type === "password" ? "text" : "password";
    });
</script>



<?php include('includes/footer.php');?>