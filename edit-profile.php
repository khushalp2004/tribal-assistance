<?php
session_start();
include('authentication.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "user_data";

$language = $_SESSION['auth_user']['language'];
$user_id = $_SESSION['auth_user']['id'];

// Create database connection
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $language = $_POST['language'];
    $city = $_POST['city'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    // Update users table
    $stmt = $conn->prepare("UPDATE users SET name=?, language=?, city=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $language, $city, $user_id);

    if ($stmt->execute()) {
        $_SESSION['auth_user']['username'] = $name;
        $_SESSION['auth_user']['language'] = $language;
        $_SESSION['auth_user']['city'] = $city;
    } else {
        echo "Error updating users table: " . $stmt->error;
    }
    $stmt->close();

    // Check if extra_details record exists
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM extra_details WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    // Update or insert into extra_details
    if ($count > 0) {
        if (empty($dob)) {
            $dob = $row['dob']; 
        }
        $stmt2 = $conn->prepare("UPDATE extra_details SET dob=?, age=?, gender=?, address=? WHERE user_id=?");
        $stmt2->bind_param("ssssi", $dob, $age, $gender, $address, $user_id);
    } else {
        $stmt2 = $conn->prepare("INSERT INTO extra_details (user_id, dob, age, gender, address) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param("issss", $user_id, $dob, $age, $gender, $address);
    }

    if ($stmt2->execute()) {
        header("location: profile.php"); // Redirect only after successful execution
        exit();
    } else {
        echo "Error updating/inserting extra_details: " . $stmt2->error;
    }

    $stmt2->close();
}

// Fetch user details
$query = "SELECT * FROM extra_details WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = ['dob' => '', 'age' => '', 'gender' => '', 'address' => '']; // Default values
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language === "English" ? "Edit Profile" : "પ્રોફાઇલ સંપાદિત કરો" ?></title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f1c40f;
            --secondary:rgb(193, 154, 0);
            --accent:rgb(212, 169, 0);
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --dark: #2E2E2E;
            --light: #F5F5F5;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-dark: #333;
            --text-light: #fff;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #F5F5F5 0%, #E8F5E9 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Navbar styles - kept exactly the same */
        header {
            background-color: black;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .profile-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }

        .profile-container:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .profile-header h1 {
            font-size: 2rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .profile-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent);
        }

        .profile-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--secondary);
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background: white;
        }

        .form-control:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.2);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .submit-btn {
            grid-column: span 2;
            display: block;
            width: 200px;
            margin: 1rem auto 0;
            padding: 0.8rem 1.5rem;
            background-color: var(--success);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
            box-shadow: var(--box-shadow);
        }

        .submit-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .profile-form {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
            
            .profile-container {
                margin: 1rem;
                padding: 1.5rem;
            }
            
            .profile-header h1 {
                font-size: 1.5rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .profile-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <?php if($language==="English"): ?>
        <header>
            <nav>
                <div class="logo">Tribal<span> Assistance</span></div>
                <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                    <span class="nav-icon"></span>
                </div>
                <ul class="nav-links" id="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <?php if($_SESSION['auth_user']['email']==="abcbank29@gmail.com"): ?>
                        <?php else: ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
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
                    <?php if($_SESSION['auth_user']['email']==="abcbank29@gmail.com"): ?>
                        <?php else: ?>
                    <li><a href="dashboard.php">ડેશબોર્ડ</a></li>
                    <?php endif; ?>
                    <li><a href="profile.php">મારું પ્રોફાઇલ</a></li>
                    <li><a href="logout.php">લૉગ આઉટ</a></li>
                </ul>
            </nav>
        </header>
    <?php endif; ?>
    
    <div class="profile-container">
        <div class="profile-header">
            <h1><?= $language === "English" ? "Edit Profile" : "પ્રોફાઇલ સંપાદિત કરો" ?></h1>
        </div>
        
        <form action="edit-profile.php" method="POST" class="profile-form">
            <div class="form-group">
                <label for="name" class="form-label"><?= $language === "English" ? "Name" : "નામ" ?></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($_SESSION['auth_user']['username']) ?>">
            </div>
            
            <div class="form-group">
                <label for="dob" class="form-label"><?= $language === "English" ? "Date of Birth" : "જન્મ તારીખ" ?></label>
                <input type="date" id="dob" name="dob" class="form-control" value="<?= htmlspecialchars($row['dob']) ?>">
            </div>
            
            <div class="form-group">
                <label for="age" class="form-label"><?= $language === "English" ? "Age" : "ઉંમર" ?></label>
                <input type="number" id="age" name="age" class="form-control" readonly value="<?= htmlspecialchars($row['age']) ?>">
            </div>
            
            <div class="form-group">
                <label for="gender" class="form-label"><?= $language === "English" ? "Gender" : "જાતિ" ?></label>
                <select id="gender" name="gender" class="form-control">
                    <option value="<?= htmlspecialchars($row['gender']) ?>"><?= htmlspecialchars($row['gender']) ?></option>
                    <?php if($row['gender']==="Male"): ?>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                        <option value="Rather Not to say">Rather not to say</option>
                    <?php elseif($row['gender']==="Female"): ?>
                        <option value="Male">Male</option>
                        <option value="Other">Other</option>
                        <option value="Rather Not to say">Rather not to say</option>
                    <?php elseif($row['gender']==="Other"): ?>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Rather Not to say">Rather not to say</option>
                    <?php else: ?>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="language" class="form-label"><?= $language === "English" ? "Language" : "ભાષા" ?></label>
                <select id="language" name="language" class="form-control">
                    <option value="<?= htmlspecialchars($language) ?>"><?= htmlspecialchars($language) ?></option>
                    <?php if($language==="English"): ?>
                    <option value="Gujarati">ગુજરાતી</option>
                    <?php else: ?>
                    <option value="English">English</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="city" class="form-label"><?= $language === "English" ? "City" : "શહેર" ?></label>
                <input type="text" id="city" name="city" class="form-control" value="<?= htmlspecialchars($_SESSION['auth_user']['city']) ?>">
            </div>
            
            <div class="form-group full-width">
                <label for="address" class="form-label"><?= $language === "English" ? "Address" : "સરનામું" ?></label>
                <textarea id="address" name="address" class="form-control" rows="3"><?= htmlspecialchars($row['address']) ?></textarea>
            </div>
            
            <button type="submit" name="submit" class="submit-btn">
                <?= $language === "English" ? "Save Changes" : "બદલો સાચવો" ?>
            </button>
        </form>
    </div>
    
    <script src="js/tryyy.js"></script>
    <script src="js/dob.js"></script>
</body>
</html>