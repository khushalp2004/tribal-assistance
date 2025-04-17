<?php
session_start();
if (!isset($_SESSION['auth_user']['id']) || $_SESSION['auth_user']['email'] == "abcbank29@gmail.com") {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "user_data");
$user_id = $_SESSION['auth_user']['id'];
$email = $_SESSION['auth_user']['email'];
$language = $_SESSION['auth_user']['language'];

// Get all forms data with remarks
$result = $conn->query("
    SELECT f.*, fr.remark 
    FROM forms f 
    LEFT JOIN form_remarks fr ON f.id = fr.form_id 
    WHERE f.user_id='$user_id'
");
$result2 = $conn->query("
    SELECT f.*, fr.remark 
    FROM forms2 f 
    LEFT JOIN form_remarks fr ON f.id = fr.form_id 
    WHERE f.user_id='$user_id'
");

// Initialize data structures
$stats = [
    'forms1' => [
        'total' => 0, 'accepted' => 0, 'pending' => 0, 'declined' => 0,
        'declined_details' => []
    ],
    'forms2' => [
        'total' => 0, 'accepted' => 0, 'pending' => 0, 'declined' => 0,
        'declined_details' => []
    ]
];

// Process form data
while ($row = $result->fetch_assoc()) {
    $stats['forms1']['total']++;
    if ($row['status'] == 'ACCEPTED') {
        $stats['forms1']['accepted']++;
    } elseif ($row['status'] == 'PENDING') {
        $stats['forms1']['pending']++;
    } elseif ($row['status'] == 'DECLINED') {
        $stats['forms1']['declined']++;
        $stats['forms1']['declined_details'][] = [
            'id' => $row['id'],
            'remark' => $row['remark'] ?? 'No reason provided'
        ];
    }
}

while ($row = $result2->fetch_assoc()) {
    $stats['forms2']['total']++;
    if ($row['status'] == 'ACCEPTED') {
        $stats['forms2']['accepted']++;
    } elseif ($row['status'] == 'PENDING') {
        $stats['forms2']['pending']++;
    } elseif ($row['status'] == 'DECLINED') {
        $stats['forms2']['declined']++;
        $stats['forms2']['declined_details'][] = [
            'id' => $row['id'],
            'remark' => $row['remark'] ?? 'No reason provided'
        ];
    }
}

// Calculate percentages
$stats['forms1']['percentage'] = $stats['forms1']['total'] > 0 
    ? round(($stats['forms1']['accepted'] / $stats['forms1']['total']) * 100) 
    : 0;
$stats['forms2']['percentage'] = $stats['forms2']['total'] > 0 
    ? round(($stats['forms2']['accepted'] / $stats['forms2']['total']) * 100) 
    : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $language === "English" ? "Progress Tracker" : "પ્રગતિ ટ્રેકર" ?></title>
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #f1c40f;
            --success:rgb(3, 184, 0);
            --danger:rgb(255, 0, 0);
            --warning:rgb(255, 191, 0);
            --dark: #2E2E2E; 
            --light: #F5F5F5;
            --card-bg: rgba(255, 255, 255, 0.95);
            --nav-bg: #4CAF50;
            --text-dark: #333;
            --text-light: #fff;
        }
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #F5F5F5 0%, #E8F5E9 100%);
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
        }
        
        /* Navbar Styles (Kept at top as requested) */
        header {
            background-color: black;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .dashboard-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .dashboard-title {
            font-size: 2rem;
            color:rgb(88, 88, 88);
            margin-bottom: 0.5rem;
        }
        
        .dashboard-subtitle {
            color: #666;
            font-size: 1rem;
        }
        
        .content-wrapper {
            flex: 1;
            padding: 2rem;
        }
        
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .progress-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .progress-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }
        
        .progress-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--light));
            border-radius: 12px 12px 0 0;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .card-badge {
            background: var(--success);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .progress-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .circular-progress {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 1rem 0;
        }
        
        .progress-circle {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }
        
        .progress-circle-bg {
            fill: none;
            stroke: #E8F5E9;
            stroke-width: 10;
        }
        
        .progress-circle-fill {
            fill: none;
            stroke: var(--primary);
            stroke-width: 10;
            stroke-linecap: round;
            stroke-dasharray: 565;
            stroke-dashoffset: calc(565 - (565 * var(--progress)) / 100);
            transition: stroke-dashoffset 1s ease;
            animation: progressAnimation 1.5s ease-out forwards;
        }

        .progress-circle-fill2 {
            fill: none;
            stroke: skyblue;
            stroke-width: 10;
            stroke-linecap: round;
            stroke-dasharray: 565;
            stroke-dashoffset: calc(565 - (565 * var(--progress)) / 100);
            transition: stroke-dashoffset 1s ease;
            animation: progressAnimation 1.5s ease-out forwards;
        }
        
        @keyframes progressAnimation {
            from { stroke-dashoffset: 565; }
        }
        
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .progress-text span {
            font-size: 1rem;
            color: #666;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #666;
            margin: 0;
        }
        
        .accepted { color: var(--success); }
        .pending { color: var(--warning); }
        .declined { color: var(--danger); }
        
        .declined-section {
            background: rgba(255, 152, 0, 0.08);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border: 1px solid rgba(255, 152, 0, 0.2);
        }
        
        .section-title {
            font-size: 1.1rem;
            color: var(--danger);
            margin-top: 0;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 0.5rem;
            color: var(--danger);
        }
        
        .declined-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .declined-item {
            background: white;
            border-left: 4px solid var(--danger);
            padding: 1rem;
            margin-bottom: 0.8rem;
            border-radius: 0 5px 5px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .declined-id {
            font-weight: 600;
            color: var(--danger);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .declined-reason {
            color: #555;
            font-size: 0.9rem;
            margin: 0;
            padding-left: 1.5rem;
        }
        
        .no-forms {
            text-align: center;
            padding: 2rem;
            color: #666;
            background: white;
            border-radius: 10px;
            grid-column: 1 / -1;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .user-panel {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        .user-info h3 {
            margin: 0;
            color: var(--dark);
            font-size: 1.2rem;
        }
        
        .user-info p {
            margin: 0.3rem 0 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .circular-progress {
                width: 140px;
                height: 140px;
            }
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
                    <li><a href="dashboard.php">Dashboard</a></li>
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
                    <li><a href="profile.php">મારું પ્રોફાઇલ</a></li>
                    <li><a href="logout.php">લૉગ આઉટ</a></li>
                </ul>
            </nav>
        </header>
    <?php endif; ?>
    
    <div class="dashboard-container">
        <div class="content-wrapper">
            <div class="user-panel">
                <div class="user-info">
                    <h3><?= htmlspecialchars($_SESSION['auth_user']['username']) ?></h3>
                    <p><?= htmlspecialchars($_SESSION['auth_user']['email']) ?></p>
                </div>
                <div class="user-status">
                    <span class="card-badge">
                        <?= $language === "English" ? "Active" : "સક્રિય" ?>
                    </span>
                </div>
            </div>
            <div class="dashboard-header">
            <h1 class="dashboard-title"><?= $language === "English" ? "Application Progress" : "અરજી પ્રગતિ" ?></h1>
            <p class="dashboard-subtitle"><?= $language === "English" ? "Track your submitted forms" : "તમારા સબમિટ કરેલા ફોર્મ ટ્રૅક કરો" ?></p>
        </div>
            
            <div class="cards-grid">
                <?php if ($stats['forms1']['total'] > 0 || $stats['forms2']['total'] > 0): ?>
                    <?php if ($stats['forms1']['total'] > 0): ?>
                        <div class="progress-card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <?= $language === "English" ? "Tractor Trolley" : "ટ્રેક્ટર ટ્રોલી" ?>
                                </h2>
                                <span class="card-badge">
                                    <?= $stats['forms1']['total'] ?> <?= $language === "English" ? "Forms" : "ફોર્મ" ?>
                                </span>
                            </div>
                            
                            <div class="progress-container">
                                <div class="circular-progress">
                                    <svg class="progress-circle" viewBox="0 0 200 200">
                                        <circle class="progress-circle-bg" cx="100" cy="100" r="90"></circle>
                                        <circle class="progress-circle-fill" cx="100" cy="100" r="90" 
                                            style="--progress: <?= $stats['forms1']['percentage'] ?>"></circle>
                                    </svg>
                                    <div class="progress-text">
                                        <?= $stats['forms1']['percentage'] ?><span>%</span>
                                    </div>
                                </div>
                                
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <p class="stat-label"><?= $language === "English" ? "Accepted" : "સ્વીકૃત" ?></p>
                                        <p class="stat-value accepted"><?= $stats['forms1']['accepted'] ?></p>
                                    </div>
                                    <div class="stat-item">
                                        <p class="stat-label"><?= $language === "English" ? "Pending" : "બાકી" ?></p>
                                        <p class="stat-value pending"><?= $stats['forms1']['pending'] ?></p>
                                    </div>
                                    <div class="stat-item">
                                        <p class="stat-label"><?= $language === "English" ? "Declined" : "નકાર્યું" ?></p>
                                        <p class="stat-value declined"><?= $stats['forms1']['declined'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($stats['forms1']['declined_details'])): ?>
                                <div class="declined-section">
                                    <h3 class="section-title">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <?= $language === "English" ? "Declined Forms" : "નકારાયેલ ફોર્મ" ?>
                                    </h3>
                                    <ul class="declined-list">
                                        <?php foreach ($stats['forms1']['declined_details'] as $form): ?>
                                            <li class="declined-item">
                                                <div class="declined-id">
                                                    <i class="fas fa-file-alt"></i>
                                                    <?= $language === "English" ? "Form ID" : "ફોર્મ આઈડી" ?>: <?= $form['id'] ?>
                                                </div>
                                                <p class="declined-reason">
                                                    <strong><?= $language === "English" ? "Reason" : "કારણ" ?>:</strong> 
                                                    <?= htmlspecialchars($form['remark']) ?>
                                                </p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($stats['forms2']['total'] > 0): ?>
                        <div class="progress-card">
                            <div class="card-header">
                                <h2 class="card-title">
                                    <?= $language === "English" ? "Tractor Trolley 2" : "ટ્રેક્ટર ટ્રોલી 2" ?>
                                </h2>
                                <span class="card-badge">
                                    <?= $stats['forms2']['total'] ?> <?= $language === "English" ? "Forms" : "ફોર્મ" ?>
                                </span>
                            </div>
                            
                            <div class="progress-container">
                                <div class="circular-progress">
                                    <svg class="progress-circle" viewBox="0 0 200 200">
                                        <circle class="progress-circle-bg" cx="100" cy="100" r="90"></circle>
                                        <circle class="progress-circle-fill2" cx="100" cy="100" r="90" 
                                            style="--progress: <?= $stats['forms2']['percentage'] ?>"></circle>
                                    </svg>
                                    <div class="progress-text">
                                        <?= $stats['forms2']['percentage'] ?><span>%</span>
                                    </div>
                                </div>
                                
                                <div class="stats-grid">
                                    <div class="stat-item">
                                        <p class="stat-label"><?= $language === "English" ? "Accepted" : "સ્વીકૃત" ?></p>
                                        <p class="stat-value accepted"><?= $stats['forms2']['accepted'] ?></p>
                                    </div>
                                    <div class="stat-item">
                                        <p class="stat-label"><?= $language === "English" ? "Pending" : "બાકી" ?></p>
                                        <p class="stat-value pending"><?= $stats['forms2']['pending'] ?></p>
                                    </div>
                                    <div class="stat-item">
                                        <p class="stat-label"><?= $language === "English" ? "Declined" : "નકાર્યું" ?></p>
                                        <p class="stat-value declined"><?= $stats['forms2']['declined'] ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($stats['forms2']['declined_details'])): ?>
                                <div class="declined-section">
                                    <h3 class="section-title">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <?= $language === "English" ? "Declined Forms" : "નકારાયેલ ફોર્મ" ?>
                                    </h3>
                                    <ul class="declined-list">
                                        <?php foreach ($stats['forms2']['declined_details'] as $form): ?>
                                            <li class="declined-item">
                                                <div class="declined-id">
                                                    <i class="fas fa-file-alt"></i>
                                                    <?= $language === "English" ? "Form ID" : "ફોર્મ આઈડી" ?>: <?= $form['id'] ?>
                                                </div>
                                                <p class="declined-reason">
                                                    <strong><?= $language === "English" ? "Reason" : "કારણ" ?>:</strong> 
                                                    <?= htmlspecialchars($form['remark']) ?>
                                                </p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-forms">
                        <h3><?= $language === "English" ? "No forms submitted yet" : "હજી સુધી કોઈ ફોર્મ સબમિટ કર્યા નથી" ?></h3>
                        <p><?= $language === "English" ? "Submit your first form to track progress" : "પ્રગતિ ટ્રૅક કરવા માટે તમારું પ્રથમ ફોર્મ સબમિટ કરો" ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="js/tryyy.js"></script>
</body>
</html>