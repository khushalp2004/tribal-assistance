<?php
session_start();   

    $page_title="Login";
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
                        <h5>Login Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="logincode.php" method="POST">
                            <div class="form- mb-3">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <!-- <div class="form- mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" id="password"required>
                            </div> -->

                            <div class="form-group mb-3">
    <label for="password">Password</label>
    <div class="input-group">
        <input type="password" name="password" class="form-control" id="password" required>
        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
            👁️
        </button>
    </div><br>
</div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" name="login_btn">Login</button>
                            </div>
                            <div class="form- mb-3 mt-2">
                                <label for="">Create account? </label>
                                <a href="register.php">Register Now</a>
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

<script src="js/login-speech-email.js"></script>
<script src="js/login-speech-password.js"></script>
<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        let passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    });
</script>




<?php include('includes/footer.php');?>