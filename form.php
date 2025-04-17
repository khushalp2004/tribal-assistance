<?php
session_start();
// include("authentication.php");
if (!isset($_SESSION['auth_user']['id']) || $_SESSION['auth_user']['email'] == "abcbank29@gmail.com") {
    if($_SESSION['auth_user']['email'] == "abcbank29@gmail.com"){
        header("location: admin.php");
        exit();
    }else{
        header("Location: login.php");
        exit;
    }
}

$language=$_SESSION['auth_user']['language'];
$email=$_SESSION['auth_user']['email'];
$city=$_SESSION['auth_user']['city'];

// Create connection
$conn = new mysqli("localhost", "root", "", "user_data");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

function sendemail_verify($name,$email,$city,$age,$dob,$gender,$address,$phone,$documents){
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

    $mail->setFrom("tribaldevelopmentssip@gmail.com",$email);
    $mail->addAddress("abcbank29@gmail.com");

    $mail->isHTML(true);
    $mail->Subject="Tractor Trolley Documents uploaded by $email";

    $email_template = "
            <h3>Tractor Trolley Scheme</h3>
            <p><strong>Name:</strong> {$name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Age:</strong> {$age}</p>
            <p><strong>Date of Birth:</strong> {$dob}</p>
            <p><strong>Gender:</strong> {$gender}</p>
            <p><strong>Address:</strong> {$address}</p>
            <p><strong>City:</strong> {$city}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <h4>Documents:</h4><ul>";

            foreach ($documents as $field => $path) {
                // $email_template .= "<li><strong>{$field}:</strong> <a href='" . $path . "'>View</a></li>";
                 $mail->addAttachment($path, $field . "." . pathinfo($path, PATHINFO_EXTENSION)); // Attach the file
            }
        $email_template .= "
        </ul>
        <hr>
        <p>The link will directly redirect to the approvals/rejection page on link you can login through your admin email id</p>
        <a href='http://localhost/tribal-assistance/admin.php'>Link</a>
        ";

    $mail->Body=$email_template;
    $mail->send();
    // echo "Message sent";
}

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
    $sql = "INSERT INTO forms (user_id, name, age, dob, gender, address, phone, 
            aadhaar, voter, drivingLicense, electricityBill, bankStatement, agriculturalLandDocument, achEcsMandate) 
            VALUES ('$user_id', '$name', '$age', '$dob', '$gender', '$address', '$phone', 
            '{$documents['aadhaar']}', '{$documents['voter']}', '{$documents['drivingLicense']}', 
            '{$documents['electricityBill']}', '{$documents['bankStatement']}', '{$documents['agriculturalLandDocument']}', 
            '{$documents['achEcsMandate']}')";

    if ($conn->query($sql) === TRUE) {
        sendemail_verify($name,$email,$city,$age,$dob,$gender,$address,$phone,$documents);
        header("location: index.php");
        // echo "Documents uploaded successfully!";
        // echo "<script>alert('Form Submitted')</script>";
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
    <title>Tractor Trolley Loan Application</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-yellow: #FFD700;
            --dark-yellow: #FFB800;
            --light-yellow: #FFF5CC;
            --black: #1A1A1A;
            --white: #FFFFFF;
            --gray: #F8F8F8;
            --dark-gray: #333333;
            --border-color: #E0E0E0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--gray);
            color: var(--black);
            line-height: 1.6;
        }

        /* header {
            background-color: black;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        } */

        /* nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--black);
        }

        .logo span {
            color: var(--primary-yellow);
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--black);
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary-yellow);
        } */

        .menu-icon {
            display: none;
            cursor: pointer;
        }

        #all {
            max-width: 1000px;
            margin: 6rem auto 2rem;
            padding: 0 1.5rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-header h1 {
            font-size: 2rem;
            color: var(--black);
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .form-header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: var(--primary-yellow);
            border-radius: 2px;
        }

        .form-header p {
            color: var(--dark-gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            margin-bottom: 3rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        input:not([type="file"]),
        select,
        textarea {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s;
            background-color: var(--white);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.2);
        }

        .file-input {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1rem;
            background-color: var(--light-yellow);
            border: 1px dashed var(--primary-yellow);
            border-radius: 8px;
            color: var(--dark-gray);
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-input-label:hover {
            background-color: rgba(255, 215, 0, 0.1);
        }

        .file-input-icon {
            color: var(--primary-yellow);
        }

        .speech-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-yellow);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .speech-button:hover {
            background: var(--dark-yellow);
            transform: translateY(-50%) scale(1.05);
        }

        .speech-button svg {
            width: 16px;
            height: 16px;
        }

        .submit-btn {
            background-color: var(--primary-yellow);
            color: var(--black);
            border: none;
            border-radius: 8px;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .submit-btn:hover {
            background-color: var(--dark-yellow);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 184, 0, 0.3);
        }

        .submit-btn svg {
            margin-left: 8px;
        }

        .form-image {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            height: 300px;
            object-fit: cover;
        }

        .language-toggle {
            text-align: right;
            margin-bottom: 1rem;
        }

        .language-btn {
            background: none;
            border: none;
            color: var(--dark-gray);
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .language-btn:hover {
            color: var(--primary-yellow);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: span 1;
            }

            nav {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .menu-icon {
                display: block;
            }

            #all {
                margin-top: 5rem;
                padding: 0 1rem;
            }

            .form-container {
                padding: 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            animation: fadeIn 0.4s ease-out forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }
        .form-group:nth-child(6) { animation-delay: 0.6s; }
        .form-group:nth-child(7) { animation-delay: 0.7s; }
        .form-group:nth-child(8) { animation-delay: 0.8s; }
        .form-group:nth-child(9) { animation-delay: 0.9s; }
        .form-group:nth-child(10) { animation-delay: 1s; }
    </style>
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
                    <li><a href="profile.php">My Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>
    <?php else: ?>
        <header>
            <nav>
            <div class="logo">Tribal <span>Assistance</span></div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                <span class="nav-icon"></span>
            </div>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php">ઘર</a></li>
                <li><a href="dashboard.php">ડેશબોર્ડ</a></li>
                <li><a href="profile.php">મારું પ્રોફાઇલ</a></li>
                <li><a href="logout.php">લૉગ આઉટ</a></li>
            </ul>
        </nav>
        </header>
    <?php endif;?>

    <div id="all">
        <!-- <img src="https://tse4.mm.bing.net/th?id=OIP.AHTT9QIKPRVhXJHpcB8_XQHaEK&pid=Api&P=0&h=180" alt="Tractor Trolley" class="form-image"> -->
        
        <div class="form-container">
            <div class="form-header">
                <h1>Tractor / Trolley Loan Application</h1>
                <p>Fill out the form below to apply for your agricultural vehicle loan</p>
            </div>

            <form method="POST" action="form.php" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Name / નામ</label>
                        <div class="input-wrapper">
                            <input type="text" name="name" id="name" placeholder="Enter your full name" pattern="^[a-zA-Z\s]+$" title="Only letters and spaces are allowed" required>
                            <button type="button" class="speech-button" id="speech-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0z"/>
                                    <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="age">Age / વય</label>
                        <div class="input-wrapper">
                            <input type="number" name="age" id="age" placeholder="e.g. 25" pattern="^[0-9]+$" title="Age must be a number" required>
                            <button type="button" class="speech-button" id="age-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0z"/>
                                    <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth / જન્મદિવસ</label>
                        <input type="date" name="dob" id="dob" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender / લિંગ</label>
                        <select name="gender" id="gender" required>
                            <option value="male">Male / પુરુષ</option>
                            <option value="female">Female / સ્ત્રી</option>
                            <option value="others">Others / અન્ય</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="address">Address / સરનામું</label>
                        <div class="input-wrapper">
                            <textarea name="address" id="address" placeholder="D/6, Ankur Society, Ahmedabad, Gujarat" required></textarea>
                            <button type="button" class="speech-button" id="address-button" style="top: 10px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0z"/>
                                    <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number / ફોન નંબર</label>
                        <div class="input-wrapper">
                            <input type="tel" name="phone" id="phone" placeholder="Enter phone number" required title="Phone number must contain only digits">
                            <button type="button" class="speech-button" id="phone-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0z"/>
                                    <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <h3 style="margin: 1.5rem 0 1rem; color: var(--dark-gray); border-bottom: 2px solid var(--light-yellow); padding-bottom: 0.5rem;">Required Documents</h3>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="aadhar">Aadhaar Card / આધાર કાર્ડ</label>
                        <div class="file-input">
                            <input type="file" id="aadhar" name="aadhaar" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="aadhar" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="voter">Voter ID / મતદાર કાર્ડ</label>
                        <div class="file-input">
                            <input type="file" id="voter" name="voter" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="voter" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="drivingLicense">Driving License / ડ્રાઇવિંગ લાઇસન્સ</label>
                        <div class="file-input">
                            <input type="file" id="drivingLicense" name="drivingLicense" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="drivingLicense" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="electricityBill">Electricity Bill / વીજળી બિલ</label>
                        <div class="file-input">
                            <input type="file" id="electricityBill" name="electricityBill" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="electricityBill" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bankStatement">Bank Statement / બેંક નિવેદન</label>
                        <div class="file-input">
                            <input type="file" id="bankStatement" name="bankStatement" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="bankStatement" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="agriculturalLandDocument">Agricultural Land Document / કૃષિ જમીન દસ્તાવેજ</label>
                        <div class="file-input">
                            <input type="file" id="agriculturalLandDocument" name="agriculturalLandDocument" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="agriculturalLandDocument" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="achEcsMandate">ACH/ECS Mandate / મેન્ડેટ</label>
                        <div class="file-input">
                            <input type="file" id="achEcsMandate" name="achEcsMandate" accept=".jpg, .jpeg, .png, .pdf" required>
                            <label for="achEcsMandate" class="file-input-label">
                                <span>Choose file</span>
                                <span class="file-input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="submit-btn">
                    Submit Application
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <script src="js/speech.js"></script>
    <script src="js/tryyy.js"></script>
</body>
</html>