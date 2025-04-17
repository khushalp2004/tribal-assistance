<?php
session_start();
$conn = new mysqli("localhost", "root", "", "user_data");

// Check for DB connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is authenticated
if (!isset($_SESSION['auth_user']['id'])) {
    die("Unauthorized access!");
}

$user_id = $_SESSION['auth_user']['id'];
$target_dir = "uploads/";

// Create uploads directory if it doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$allowed_types = ['jpg', 'jpeg', 'png'];
$field = 'profilepic';

// Check if a file was uploaded
if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
    // Generate unique file name
    $file_ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
    $file_name = "profile_" . $user_id . "_" . time() . "." . $file_ext;
    $target_file = $target_dir . $file_name;

    // Validate file type
    if (in_array(strtolower($file_ext), $allowed_types)) {
        // Move the uploaded file
        if (move_uploaded_file($_FILES[$field]["tmp_name"], $target_file)) {
            // Check if profile pic exists
            $check_sql = "SELECT COUNT(*) FROM profilephoto WHERE user_id = ?";
            $check_stmt = $conn->prepare($check_sql);
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
                    // $_SESSION['status'] = "Profile picture updated successfully.";
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Database error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                // Insert profile pic into database
                $insert_sql = "INSERT INTO profilephoto (user_id, profilepic) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("is", $user_id, $target_file);

                if ($insert_stmt->execute()) {
                    // $_SESSION['status'] = "Profile picture inserted successfully.";
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Database error: " . $insert_stmt->error;
                }
                $insert_stmt->close();
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Invalid file type. Allowed types: jpg, jpeg, png.";
    }
} else {
    echo "No file uploaded or an error occurred.";
}

$query = "SELECT * FROM profilephoto WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $row = ['profilepic' => '']; // Default values
}
$stmt->close();

$conn->close();
?>