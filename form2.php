<?php
session_start();
include("authentication.php");
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$dbname = "user_data";
$language=$_SESSION['auth_user']['language'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // $user_id = $_POST['user_id'];
    $user_id=$_SESSION['auth_user']['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Upload directory
    $target_dir = "uploads/";

    // Ensure the directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Array to store file paths
    $documents = [];
    $fields = ["aadhaar", "voter", "drivingLicense", "electricityBill", "bankStatement", "agriculturalLandDocument", "achEcsMandate"];

    // Allowed file types
    $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];

    // Process each document
    foreach ($fields as $field) {
        $file_name = basename($_FILES[$field]["name"]);
        $target_file = $target_dir . $file_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowed_types)) {
            if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
                $documents[$field] = $target_file;
            } else {
                die("Error uploading $field");
            }
        } else {
            die("Invalid file type for $field");
        }
    }

    // Insert into database
    $sql = "INSERT INTO forms2 (user_id, name, age, dob, gender, address, phone, 
            aadhaar, voter, drivingLicense, electricityBill, bankStatement, agriculturalLandDocument, achEcsMandate) 
            VALUES ('$user_id', '$name', '$age', '$dob', '$gender', '$address', '$phone', 
            '{$documents['aadhaar']}', '{$documents['voter']}', '{$documents['drivingLicense']}', 
            '{$documents['electricityBill']}', '{$documents['bankStatement']}', '{$documents['agriculturalLandDocument']}', 
            '{$documents['achEcsMandate']}')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Documents uploaded');</script>";
        header("location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tractor Trolley-2</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
<?php if($language==="English"):?>
<header>
        <nav>
            <div class="logo">Tribal<span> Assistance</span></div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                <span class="nav-icon"></span>
            </div>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="dashboard.php">Dashboard</a></li>
                <!-- <li><a href="#">Help</a></li> -->
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <?php else: ?>
        <header>
            <nav>
            <div class="logo">Tribal<span> Assistance</span></div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                <span class="nav-icon"></span>
            </div>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php">ઘર</a></li>
                <li><a href="dashboard.php">ડેશબોર્ડ</a></li>
                <!-- <li><a href="#">મદદ</a></li> -->
                <li><a href="profile.php">મારું પ્રોફાઇલ</a></li>
                <li><a href="logout.php">લૉગ આઉટ</a></li>
            </ul>
        </nav>
        </header>
    <?php endif;?>
    <br><br><br><br>
    <div id="all">
        <img src="https://tse4.mm.bing.net/th?id=OIP.AHTT9QIKPRVhXJHpcB8_XQHaEK&pid=Api&P=0&h=180" alt="">
        <div id="fom">
            <div id="" style="
    margin-bottom: -50px;">
                <center>
                    <h1>Tractor / Trolley Loan-2</h1>
                </center>
                <hr style="margin: 10px 100px;">
            </div>
            <div class="formbold-main-wrapper">
                <div class="formbold-form-wrapper">
                    <form method="POST" action="form2.php" enctype="multipart/form-data">

                        <div class="formbold-input-wrapp formbold-mb-3">
                            <label for="firstname" class="formbold-form-label"> Name </label>
                            <div>
                                <input type="text" name="name" id="" placeholder="Enter Name"
                                    class="formbold-form-input" />
                            </div>
                        </div>

                        <div class="formbold-mb-3">
                            <label for="age" class="formbold-form-label"> Age </label>
                            <input type="text" name="age" id="age" placeholder="ex:25" class="formbold-form-input" />
                        </div>

                        <div class="formbold-mb-3">
                            <label for="dob" class="formbold-form-label"> Date of Birth </label>
                            <input type="date" name="dob" id="dob" class="formbold-form-input" />
                        </div>

                        <div class="formbold-mb-3">
                            <label class="formbold-form-label">Gender</label>

                            <select class="formbold-form-input" name="gender" id="occupation">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="others">Others</option>
                            </select>
                        </div>

                        <div class="formbold-mb-3">
                            <label for="address" class="formbold-form-label"> Address </label>
                            <textarea type="text" name="address" id="address"
                                placeholder="D/6 , Ankur Society , Ahmedabad , Gujarat"
                                class="formbold-form-input formbold-mb-3" ></textarea>
            
                        </div> 
                        <div class="formbold-mb-3 formbold-input-wrapp">
                            <label for="phone" class="formbold-form-label"> Phone </label>
                            <div>
                                <input type="text" name="phone" id="phone" placeholder="Phone number"
                                    class="formbold-form-input" />
                            </div>
                        </div>

                        <div class="formbold-mb-3">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="aadhar" class='formbold-form-label'>
                                Aadhaar Card:
                            </label>
                            <input type="file" id="aadhar" name="aadhaar" accept=".jpg, .jpeg, .png, .pdf" required
                                class="formbold-form-input formbold-form-file">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="voter" class='formbold-form-label'>
                                Voter ID
                            </label>
                            <input type="file" name="voter" accept=".jpg, .jpeg, .png, .pdf" required
                                class="formbold-form-input formbold-form-file">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="drivingLicense" class='formbold-form-label'>
                                Driving License
                            </label>
                            <input type="file" name="drivingLicense" accept=".jpg, .jpeg, .png, .pdf" required
                                class="formbold-form-input formbold-form-file">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="electricityBill" class='formbold-form-label'>
                                Electricity Bill
                            </label>
                            <input type="file" name="electricityBill" accept=".jpg, .jpeg, .png, .pdf" required
                                class="formbold-form-input formbold-form-file">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="bankStatement" class='formbold-form-label'>
                                Bank Statement:
                            </label>
                            <input type="file"  name="bankStatement" accept=".jpg, .jpeg, .png, .pdf" required
                                class="formbold-form-input formbold-form-file">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="agriculturalLandDocument" class='formbold-form-label'>
                                Agricultural Land Document
                            </label>
                            <input type="file" name="agriculturalLandDocument" accept=".jpg, .jpeg, .png, .pdf"
                                required class="formbold-form-input formbold-form-file">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="achEcsMandate" class='formbold-form-label'>
                                ACH/ECS Mandate
                            </label>
                            <input type="file" name="achEcsMandate" accept=".jpg, .jpeg, .png, .pdf" required
                                class="formbold-form-input formbold-form-file">
                        </div>
                        <button class="formbold-btn" type='submit' name='submit'>Submit</button><br>
                        <!-- <a href="logout.php">Logout</a> -->
                    </form>
                </div>
            </div>

        </div>
    </div>
    <script src="js/tryyy.js"></script>

</body>

</html>