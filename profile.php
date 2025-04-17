<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require 'authentication.php'; // Include authentication

$conn = new mysqli("localhost", "root", "", "user_data");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['auth_user']['username'];
$language = $_SESSION['auth_user']['language'];
$user_id = $_SESSION['auth_user']['id'];

// Fetch user profile data
$query = "SELECT * FROM extra_details WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

//Fetch profile picture data
$query2 = "SELECT * FROM profilephoto WHERE user_id = ?";
$stmtp = $conn->prepare($query2);
$stmtp->bind_param("i", $user_id);
$stmtp->execute();
$resultp = $stmtp->get_result();
$rowp = $resultp->fetch_assoc();
$stmtp->close();

/// Handle profile picture upload
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $allowed_types = ['jpg', 'jpeg', 'png'];
    $field = 'profilepic';

    if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
        $file_name = basename($_FILES[$field]['name']);
        $target_file = $target_dir . $file_name;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowed_types)) {
            if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
                // Check if profile photo exists
                $check_query = "SELECT COUNT(*) FROM profilephoto WHERE user_id = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("i", $user_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $count = $check_result->fetch_row()[0];
                $check_stmt->close();

                if ($count > 0) {
                    // Update profile pic in database
                    $sql = "UPDATE profilephoto SET profilepic = ? WHERE user_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $target_file, $user_id);
                    if ($stmt->execute()) {
                        $_SESSION['status'] = "Profile picture updated successfully.";
                    } else {
                        $_SESSION['error'] = "Database error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    // Insert profile pic into database
                    $sql = "INSERT INTO profilephoto (user_id, profilepic) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $user_id, $target_file);
                    if ($stmt->execute()) {
                        $_SESSION['status'] = "Profile picture uploaded successfully.";
                    } else {
                        $_SESSION['error'] = "Database error: " . $stmt->error;
                    }
                    $stmt->close();
                }
                header("Location: profile.php");
                exit;
            } else {
                $_SESSION['error'] = "Error uploading file.";
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Allowed types: jpg, jpeg, png.";
        }
    } else {
        $_SESSION['error'] = "No file uploaded or an error occurred.";
    }
}

$profilepic = isset($rowp['profilepic']) ? $rowp['profilepic'] : null;

if (isset($_POST['crop-redirect'])) {
    header("location: crop-pic.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #f1c40f;
            --primary-dark:rgb(233, 240, 90);
            --primary-light: #C8E6C9;
            --accent: #FFC107;
            --text-dark: #212121;
            --text-light: #757575;
            --divider: #BDBDBD;
            --white: #FFFFFF;
            --background: #F5F5F5;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-dark);
        }
        
        /* Enhanced Navbar */
        header {
            background-color: black;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo {
            color: white;
            /* font-weight: 700; */
        }
        
        .logo span {
            color: var(--accent);
        }
        
        .nav-links li a {
            color: white;
            transition: var(--transition);
        }
        
        .nav-links li a:hover {
            color: black;
        }
        
        /* Main Content */
        .py-5 {
            padding: 3rem 0;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Profile Card */
        .profile-container {
            background-color: var(--white);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: var(--transition);
            margin-top: 2rem;
        }
        
        .profile-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .profile-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .profile-header h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .edit-link {
            color: black;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }
        
        .edit-link:hover {
            /* color: ; */
            transform: scale(1.1);
        }
        
        .edit-link i {
            margin-left: 5px;
        }
        
        .profile-content {
            display: flex;
            flex-wrap: wrap;
            padding: 2rem;
            position: relative;
        }
        
        .profile-details {
            flex: 1;
            min-width: 300px;
            padding-right: 2rem;
        }
        
        .profile-details h3 {
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            color: var(--text-dark);
        }
        
        .profile-details h3 i {
            margin-right: 10px;
            color: var(--primary);
            width: 24px;
            text-align: center;
        }
        
        /* Profile Photo Section */
        .profile-photo-container {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            border: 5px solid var(--primary-light);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: var(--transition);
            margin: 0 auto;
        }
        
        .profile-photo-container:hover {
            transform: scale(1.03);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .profile-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .profile-photo-container:hover img {
            opacity: 0.8;
        }
        
        .edit-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: var(--transition);
            color: white;
        }
        
        .profile-photo-container:hover .edit-overlay {
            opacity: 1;
        }
        
        .edit-overlay i {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .edit-overlay span {
            font-weight: 500;
        }
        
        /* Form Buttons */
        .profile-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .profile-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-btn i {
            margin-right: 8px;
        }
        
        .crop-btn {
            background-color: #607D8B;
            color: white;
        }
        
        .crop-btn:hover {
            background-color: #455A64;
            transform: translateY(-2px);
        }
        
        .save-btn {
            background-color: var(--primary);
            color: white;
        }
        
        .save-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
            }
            
            .profile-details {
                padding-right: 0;
                margin-bottom: 2rem;
            }
            
            .profile-photo {
                position: static;
                margin: 0 auto 2rem;
            }
            
            .profile-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .profile-btn {
                width: 100%;
                max-width: 200px;
            }
        }
        
        /* Status Messages */
        .status-message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .status-message.success {
            background-color: #E8F5E9;
            color: #2E7D32;
        }
        
        .status-message.error {
            background-color: #FFEBEE;
            color: #C62828;
        }
        
        .status-message i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Tribal<span> Assistance</span></div>
            <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                <span class="nav-icon"></span>
            </div>
            <ul class="nav-links" id="nav-links">
                <li><a href="index.php"><?php echo ($language === "English") ? 'Home' : 'ઘર'; ?></a></li>
                <?php if ($_SESSION['auth_user']['email'] !== "abcbank29@gmail.com"): ?>
                    <li><a href="dashboard.php"><?php echo ($language === "English") ? 'Dashboard' : 'ડેશબોર્ડ'; ?></a></li>
                <?php endif; ?>
                <li><a href="profile.php"><?php echo ($language === "English") ? 'My Profile' : 'મારું પ્રોફાઇલ'; ?></a></li>
                <li><a href="logout.php"><?php echo ($language === "English") ? 'Logout' : 'લૉગ આઉટ'; ?></a></li>
            </ul>
        </nav>
    </header>
    
    <div class="py-5">
        <div class="container">
            <?php if (isset($_SESSION['status'])): ?>
                <div class="status-message success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $_SESSION['status']; unset($_SESSION['status']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="status-message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="profile-container">
                <div class="profile-header">
                    <h1><?php echo ($language === "English") ? 'My Profile' : 'મારી પ્રોફાઇલ'; ?></h1>
                    <a href="edit-profile.php" class="edit-link">
                        <?php echo ($language === "English") ? 'Edit Profile' : 'પ્રોફાઇલ સંપાદિત કરો'; ?>
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
                
                <div class="profile-content">
                    <div class="profile-details">
                        <?php if ($language === "English"): ?>
                            <h3><i class="fas fa-user"></i> Name: <?=htmlspecialchars($username);?></h3>
                            <h3><i class="fas fa-envelope"></i> Email: <?=htmlspecialchars($_SESSION['auth_user']['email']);?></h3>
                            <h3><i class="fas fa-birthday-cake"></i> Date of Birth: <?=!empty($row['dob']) ? date("d-m-Y", strtotime($row['dob'])) : 'N/A';?></h3>
                            <h3><i class="fas fa-calendar-alt"></i> Age: <?php
                                if (!empty($row['dob'])) {
                                    $dob = new DateTime($row['dob']);
                                    $today = new DateTime('today');
                                    echo $dob->diff($today)->y;
                                } else {
                                    echo 'N/A';
                                }
                            ?></h3>
                            <h3><i class="fas fa-venus-mars"></i> Gender: <?=$row['gender'] ?? 'N/A';?></h3>
                            <h3><i class="fas fa-language"></i> Language: <?=$language;?></h3>
                            <h3><i class="fas fa-home"></i> Address: <?=!empty($row['address']) ? htmlspecialchars($row['address']) : 'N/A'; ?></h3>
                            <h3><i class="fas fa-city"></i> City: <?=$_SESSION['auth_user']['city'] ?? 'N/A';?></h3>
                        <?php else: ?>
                            <h3><i class="fas fa-user"></i> નામ: <?=htmlspecialchars($username);?></h3>
                            <h3><i class="fas fa-envelope"></i> ઈમેલ: <?=htmlspecialchars($_SESSION['auth_user']['email']);?></h3>
                            <h3><i class="fas fa-birthday-cake"></i> જન્મ તારીખ: <?=!empty($row['dob']) ? date("d-m-Y", strtotime($row['dob'])) : 'N/A';?></h3>
                            <h3><i class="fas fa-calendar-alt"></i> ઉંમર: <?php
                                if (!empty($row['dob'])) {
                                    $dob = new DateTime($row['dob']);
                                    $today = new DateTime('today');
                                    echo $dob->diff($today)->y;
                                } else {
                                    echo 'N/A';
                                }
                            ?></h3>
                            <h3><i class="fas fa-venus-mars"></i> જાતિ: <?=$row['gender'] ?? 'N/A';?></h3>
                            <h3><i class="fas fa-language"></i> ભાષા: <?=$language;?></h3>
                            <h3><i class="fas fa-home"></i> સરનામું: <?=!empty($row['address']) ? htmlspecialchars($row['address']) : 'N/A'; ?></h3>
                            <h3><i class="fas fa-city"></i> શહેર: <?=$_SESSION['auth_user']['city'] ?? 'N/A';?></h3>
                        <?php endif; ?>
                    </div>
                    
                    <div class="profile-photo">
                        <form action="profile.php" method="POST" enctype="multipart/form-data">
                            <div class="profile-photo-container" onclick="document.getElementById('profile-pic-upload').click()">
                                <?php if(empty($profilepic)): ?>
                                    <img src="/images/user2.jpg" alt="Profile Picture" id="profile-pic">
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($profilepic); ?>" alt="Profile Picture" id="profile-pic">
                                <?php endif; ?>
                                
                                <div class="edit-overlay">
                                    <i class="fas fa-camera"></i>
                                    <span><?php echo ($language === "English") ? 'Change Photo' : 'ફોટો બદલો'; ?></span>
                                </div>
                            </div>
                            
                            <input type="file" accept=".jpg,.jpeg,.png" name="profilepic" id="profile-pic-upload" style="display: none;" onchange="previewImage()">
                            
                            <div class="profile-actions">
                                <button type="submit" name="crop-redirect" class="profile-btn crop-btn" id="cropbtn" style="display: none;">
                                    <i class="fas fa-crop"></i> <?php echo ($language === "English") ? 'Crop' : 'ક્રોપ'; ?>
                                </button>
                                <button type="submit" class="profile-btn save-btn" id="submitBtn" name="submit" style="display: none;">
                                    <i class="fas fa-save"></i> <?php echo ($language === "English") ? 'Save' : 'સાચવો'; ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('profile-pic-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-pic').src = e.target.result;
                    document.getElementById('cropbtn').style.display = 'flex';
                    document.getElementById('submitBtn').style.display = 'flex';
                };
                reader.readAsDataURL(file);
            }
        });
        
        function previewImage() {
            // This function is kept for compatibility but the logic is moved to the event listener above
        }
    </script>
    
    <script src="js/tryyy.js"></script>
    <script src="js/profilepic.js"></script>
</body>
</html>