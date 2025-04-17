<?php
session_start();
$page_title='Reset Password';
include("includes/header.php");
// include("includes/navbar.php");

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
                <div class="card">
                    <div class="card-header">
                        <h5>Reset Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="password-reset-code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="password_reset_link" class="btn btn-primary">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/login-speech-email.js"></script>

<?php include("includes/footer.php");