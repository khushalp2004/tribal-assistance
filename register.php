<?php
session_start();
    $page_title="Register";
    include('includes/header.php');
    // include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="alert">
                    <?php
                        if(isset($_SESSION['status'])){
                            echo "<h4>".$_SESSION['status']."</h4>";
                            unset($_SESSION['status']);
                        }
                    ?>
                </div>
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Registration Form</h5>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <div class="form- mb-3">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="form- mb-3">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control" id="email" required>
                            </div>
                            <div class="form- mb-3">
                                <label for="">City</label>
                                <input type="text" name="city" class="form-control" id="city" required>
                            </div>
                            <div class="form- mb-3">
                            <label for="">Language</label>
                            <select class="form-select" name="language" aria-label="Default select example" required>
                                <option name="language" selected>Select Language</option>
                                <option name="language" value="English">English</option>
                                <option name="language" value="Gujarati">Gujarati</option>
                                <!-- <option name="language" value="Hindi">Hindi</option> -->
                            </select>
                            </div>
                            <!-- <div class="form- mb-3">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" required>
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
                            <div class="form-mb-3">
                                <a href="resend-email-verification.php">Resend</a>
                            </div>
                            <div class=" d-grid gap-2 ">
                                <button type="submit" class="btn btn-primary" name="register_btn">Register</button>
                            </div>
                            
                            <div class="form-group">
                                <div class="float-end mt-2">
                                    Already registered?
                                    <a href="login.php" class="">Login Now</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/register-speech.js"></script>

<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        let passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
    });
</script>




<!-- <?php include('includes/footer.php');?> -->