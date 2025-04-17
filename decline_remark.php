<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

// Load Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check admin authentication
if (!isset($_SESSION['auth_user']['id'])) {
    header("Location: login.php");
    exit;
}

function sendemail_verify($user_email, $user_name, $remark, $form_id) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                     
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tribaldevelopmentssip@gmail.com';
        $mail->Password = 'dvjj cvbf ymmw smgc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom("tribaldevelopmentssip@gmail.com", "Tribal Development");
        $mail->addAddress($user_email); // Send to actual user

        $mail->isHTML(true);
        $mail->Subject = "Your Application (Form ID: $form_id) Has Been Declined";

        $email_template = "
            <h3>Dear $user_name</h3>
            <p><strong>We regret to inform you that your application (Form ID: $form_id) has been declined.</strong></p>
            <p>Reason: $remark</p>
            <p>If you have any questions, please contact to our support team.</p>
            <p>Regards,<br>The Support Team - Tribal Assistance</p>
        ";

        $mail->Body = $email_template;
        $mail->send();
        error_log("Email sent to $user_email");
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_id = $_POST['form_id'];
    $user_id = $_POST['user_id'];
    $remark = $_POST['remark'];
    
    // Get user email and name first
    $email_stmt = $conn->prepare("SELECT users.email, forms.name FROM users JOIN forms ON users.id = forms.user_id WHERE forms.id = ?");
    $email_stmt->bind_param("i", $form_id);
    $email_stmt->execute();
    $email_result = $email_stmt->get_result();
    $user_data = $email_result->fetch_assoc();
    $user_email = $user_data['email'];
    $user_name = $user_data['name'];
    
    // Update form status
    $update_stmt = $conn->prepare("UPDATE forms SET status='DECLINED' WHERE id=?");
    $update_stmt->bind_param("i", $form_id);
    $update = $update_stmt->execute();
    
    if (!$update) {
        die("Update failed: " . $update_stmt->error);
    }
    
    // Check if remark already exists
    $check_stmt = $conn->prepare("SELECT id FROM form_remarks WHERE form_id = ? AND user_id = ?");
    if (!$check_stmt) {
        die("Prepare check failed: " . $conn->error);
    }
    
    $check_stmt->bind_param("ii", $form_id, $user_id);
    if (!$check_stmt->execute()) {
        die("Execute check failed: " . $check_stmt->error);
    }
    
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        // Update existing remark
        $row = $check_result->fetch_assoc();
        $remark_id = $row['id'];
        
        $update_stmt = $conn->prepare("UPDATE form_remarks SET remark = ?, updated_at = NOW() WHERE id = ?");
        if (!$update_stmt) {
            die("Prepare update failed: " . $conn->error);
        }
        
        $bind = $update_stmt->bind_param("si", $remark, $remark_id);
        if (!$bind) {
            die("Bind update failed: " . $update_stmt->error);
        }
        
        $execute = $update_stmt->execute();
        if (!$execute) {
            die("Execute update failed: " . $update_stmt->error);
        }
    } else {
        // Insert new remark
        $insert_stmt = $conn->prepare("INSERT INTO form_remarks (form_id, user_id, remark) VALUES (?, ?, ?)");
        if (!$insert_stmt) {
            die("Prepare insert failed: " . $conn->error);
        }
        
        $bind = $insert_stmt->bind_param("iis", $form_id, $user_id, $remark);
        if (!$bind) {
            die("Bind insert failed: " . $insert_stmt->error);
        }
        
        $execute = $insert_stmt->execute();
        if (!$execute) {
            die("Execute insert failed: " . $insert_stmt->error);
        }
    }
    
    // Send email notification after database operations are complete
    sendemail_verify($user_email, $user_name, $remark, $form_id);
    
    header("Location: admin.php");
    exit;
}

// Get form details
$form_id = $_GET['form_id'];
$stmt = $conn->prepare("SELECT * FROM forms WHERE id = ?");
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}

$form = $result->fetch_assoc();
$user_id = $form['user_id'];

// Get existing remark if any
$existing_remark = '';
$remark_stmt = $conn->prepare("SELECT remark FROM form_remarks WHERE form_id = ? AND user_id = ?");
$remark_stmt->bind_param("ii", $form_id, $user_id);
$remark_stmt->execute();
$remark_result = $remark_stmt->get_result();

if ($remark_result->num_rows > 0) {
    $row = $remark_result->fetch_assoc();
    $existing_remark = $row['remark'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decline Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-yellow:rgb(196, 141, 0);
            --dark-yellow-light: #e8c15a;
            --dark-yellow-lighter: #f5e1a3;
            --black: #1a1a1a;
            --black-light: #333333;
            --white: #f8f8f8;
            --danger: #dc3545;
            --warning: #ffc107;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color:rgb(206, 206, 206);
        }

        .modal-content {
            background-color:rgb(255, 255, 255);
            padding: 30px;
            border-radius: 12px;
            width: 50%;
            max-width: 600px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            border: 1px solid var(--dark-yellow);
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: var(--danger);
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.8rem;
            border-bottom: 2px solid var(--dark-yellow);
            padding-bottom: 10px;
        }

        .info-text {
            font-size: 0.9em;
            color: var(--blue);
            margin-bottom: 15px;
            font-style: italic;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--black);
            font-weight: 500;
        }

        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--dark-yellow);
            border-radius: 6px;
            resize: vertical;
            min-height: 120px;
            background-color: var(--white);
            color: var(--dark);
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        textarea:focus {
            outline: none;
            border-color: var(--dark-yellow-light);
            box-shadow: 0 0 0 3px rgba(212, 160, 23, 0.3);
        }

        .btn-container {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .btn-submit {
            background-color: var(--danger);
            color: var(--white);
            transform: scale(0.90);
        }

        .btn-submit:hover {
            background-color: var(--danger);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .btn-cancel {
            background-color: var(--black-light);
            color: var(--white);
            transform: scale(0.85);
        }

        .btn-cancel:hover {
            background-color: var(--black);
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 85%;
                padding: 20px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            .btn {
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="modal">
        <div class="modal-content">
            <h2>Decline Application</h2>
            <p style="color:rgb(93, 93, 93); margin-bottom: 10px;">Please provide a reason for declining this application:</p>
            <form method="POST">
                Form ID: <?php echo $form_id;?>, 
                User ID: <?php echo $user_id;?>
                <input type="hidden" name="form_id" value="<?= htmlspecialchars($form_id) ?>">
                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
                
                <div class="form-group">
                    <label for="remark">Reason for Decline:</label>
                    <?php if (!empty($existing_remark)): ?>
                        <p class="info-text">Note: Existing remark will be updated</p>
                    <?php endif; ?>
                    <textarea name="remark" id="remark" required placeholder="Enter detailed reason for declining this application..."><?= htmlspecialchars($existing_remark) ?></textarea>
                </div>
                
                <div class="btn-container">
                    <a href="admin.php" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-submit"><?= empty($existing_remark) ? 'Submit Decline' : 'Update Remark' ?></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>