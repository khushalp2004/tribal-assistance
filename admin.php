<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_data";

$language = $_SESSION['auth_user']['language'] ?? 'English';
$user_id = $_SESSION['auth_user']['id'] ?? null;

if (!isset($_SESSION['auth_user']['id']) || $_SESSION['auth_user']['email'] != "abcbank29@gmail.com") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_id = $_POST['form_id'];
    $status = $_POST['status'];
    
    if ($status == 'ACCEPTED') {
        // Delete only the remarks for this form (not the form itself)
        $delete_remark_stmt = $conn->prepare("DELETE FROM form_remarks WHERE form_id = ?");
        $delete_remark_stmt->bind_param("i", $form_id);
        $delete_remark_executed = $delete_remark_stmt->execute();
        
        if (!$delete_remark_executed) {
            die("Failed to delete remarks: " . $delete_remark_stmt->error);
        }
        
        // Update form status to ACCEPTED
        $update_stmt = $conn->prepare("UPDATE forms SET status = ? WHERE id = ?");
        $update_stmt->bind_param("si", $status, $form_id);
        $update_executed = $update_stmt->execute();
        
        if (!$update_executed) {
            die("Failed to update form status: " . $update_stmt->error);
        }
    } else {
        // For declined forms, handled in decline_remark.php
        $update_stmt = $conn->prepare("UPDATE forms SET status = ? WHERE id = ?");
        $update_stmt->bind_param("si", $status, $form_id);
        $update_stmt->execute();
    }
    
    header("Location: admin.php");
    exit;
}

$result = $conn->query("SELECT forms.id, forms.name, users.email, users.city, forms.age, forms.dob, forms.gender, 
                        forms.phone, forms.aadhaar, forms.voter, forms.drivingLicense, forms.electricityBill, 
                        forms.bankStatement, forms.agriculturalLandDocument, forms.achEcsMandate, forms.status 
                        FROM forms JOIN users ON forms.user_id = users.id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language === "English" ? "Tractor Trolley Bank Approval" : "ટ્રેક્ટર ટ્રોલી બેંક મંજૂરી" ?></title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #000000;
            --secondary: #333333;
            --accent: #4a6fa5;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --light: #f8f9fa;
            --dark: #343a40;
            --text-dark: #212529;
            --text-light: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* Navbar styles - kept exactly the same */
        header {
            background-color: var(--primary);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .admin-container {
            padding: 2rem;
            max-width: 95%;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin: 2rem 0;
            padding-bottom: 1rem;
            position: relative;
        }

        .page-header h1 {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent);
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        th {
            background-color: var(--primary);
            color: var(--text-light);
            font-weight: 500;
            position: sticky;
            top: 0;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn {
            padding: 0.6rem 1rem;
            margin: 0.2rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--success);
            color: white;
        }

        .btn-secondary {
            background-color: var(--danger);
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        .document-link {
            color: var(--accent);
            text-decoration: none;
            transition: var(--transition);
        }

        .document-link:hover {
            text-decoration: underline;
            color: var(--secondary);
        }

        .status-pending {
            color: var(--warning);
            font-weight: 500;
        }

        .status-accepted {
            color: var(--success);
            font-weight: 500;
        }

        .status-declined {
            color: var(--danger);
            font-weight: 500;
        }

        @media (max-width: 1200px) {
            .admin-container {
                padding: 1rem;
            }
            
            th, td {
                padding: 0.8rem;
                font-size: 0.9rem;
            }
            
            .btn {
                padding: 0.5rem 0.8rem;
                font-size: 0.8rem;
            }
            
            .page-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 0.5rem;
            }
            
            th, td {
                padding: 0.6rem;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .table-container {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body>
    <?php if($language === "English"): ?>
        <header>
            <nav>
                <div class="logo">Tribal<span> Assistance</span></div>
                <div class="menu-icon" id="menu-icon" aria-label="Toggle navigation menu" role="button" tabindex="0">
                    <span class="nav-icon"></span>
                </div>
                <ul class="nav-links" id="nav-links">
                    <li><a href="admin.php">Tractor Trolley</a></li>
                    <li><a href="admin2.php">Testing</a></li>
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
                    <li><a href="admin.php">ટ્રેક્ટર ટ્રોલી</a></li>
                    <li><a href="admin2.php">પરીક્ષણ</a></li>
                    <li><a href="profile.php">મારી પ્રોફાઇલ</a></li>
                    <li><a href="logout.php">લૉગ આઉટ</a></li>
                </ul>
            </nav>
        </header>
    <?php endif; ?>
    
    <div class="admin-container">
        <div class="page-header">
            <h1><?= $language === "English" ? "Tractor Trolley Applications" : "ટ્રેક્ટર ટ્રોલી અરજીઓ" ?></h1>
        </div>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><?= $language === "English" ? "Scheme" : "યોજના" ?></th>
                        <th><?= $language === "English" ? "Name" : "નામ" ?></th>
                        <th><?= $language === "English" ? "Email" : "ઇમેઇલ" ?></th>
                        <th><?= $language === "English" ? "City" : "શહેર" ?></th>
                        <th><?= $language === "English" ? "Age" : "ઉંમર" ?></th>
                        <th><?= $language === "English" ? "Date of Birth" : "જન્મ તારીખ" ?></th>
                        <th><?= $language === "English" ? "Gender" : "જાતિ" ?></th>
                        <th><?= $language === "English" ? "Phone" : "ફોન" ?></th>
                        <th><?= $language === "English" ? "Aadhaar" : "આધાર" ?></th>
                        <th><?= $language === "English" ? "Voter ID" : "મતદાર આઈડી" ?></th>
                        <th><?= $language === "English" ? "Driving License" : "ડ્રાઇવિંગ લાઇસન્સ" ?></th>
                        <th><?= $language === "English" ? "Electricity Bill" : "ઇલેક્ટ્રિસિટી બીલ" ?></th>
                        <th><?= $language === "English" ? "Bank Statement" : "બેંક સ્ટેટમેન્ટ" ?></th>
                        <th><?= $language === "English" ? "Land Document" : "જમીન દસ્તાવેજ" ?></th>
                        <th><?= $language === "English" ? "ACH/ECS" : "ACH/ECS" ?></th>
                        <th><?= $language === "English" ? "Status" : "સ્થિતિ" ?></th>
                        <th><?= $language === "English" ? "Actions" : "ક્રિયાઓ" ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $language === "English" ? "Tractor Trolley" : "ટ્રેક્ટર ટ્રોલી" ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['city']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                        <td><?= htmlspecialchars(date("d-m-Y", strtotime($row['dob']))) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><a href="<?= htmlspecialchars($row['aadhaar']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td><a href="<?= htmlspecialchars($row['voter']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td><a href="<?= htmlspecialchars($row['drivingLicense']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td><a href="<?= htmlspecialchars($row['electricityBill']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td><a href="<?= htmlspecialchars($row['bankStatement']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td><a href="<?= htmlspecialchars($row['agriculturalLandDocument']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td><a href="<?= htmlspecialchars($row['achEcsMandate']) ?>" class="document-link" target="_blank"><?= $language === "English" ? "View" : "જુઓ" ?></a></td>
                        <td class="status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="form_id" value="<?= $row['id'] ?>">
                                <button name="status" class="btn btn-primary" value="ACCEPTED" 
                                    onclick="return confirm('<?= $language === "English" ? "Are you sure you want to accept this application?" : "શું તમે ખરેખર આ અરજીને સ્વીકારવા માંગો છો?" ?>')">
                                    <?= $language === "English" ? "Accept" : "સ્વીકારો" ?>
                                </button>
                                <button type="button" onclick="showDeclineModal(<?= $row['id'] ?>)" 
                                    class="btn btn-secondary">
                                    <?= $language === "English" ? "Decline" : "નામંજૂર" ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/tryyy.js"></script>
    <script>
    function showDeclineModal(formId) {
        window.location.href = `decline_remark.php?form_id=${formId}`;
    }
    </script>
</body>
</html>

<?php $conn->close(); ?>