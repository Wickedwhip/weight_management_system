<?php
include('../includes/mail_config.php');

// BMI Calculator
function calculateBMI($weight, $height) {
    if ($height <= 0) return 0;
    $bmi = $weight / pow($height / 100, 2);
    return round($bmi, 2);
}


// Protect pages
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /weight_management_system/pages/login.php");
        exit();
    }
}
// Update user's BMI progress record
function updateProgress($conn, $user_id, $new_weight) {
    // Get user height
    $result = $conn->query("SELECT height FROM users WHERE id=$user_id LIMIT 1");
    $row = $result->fetch_assoc();
    $height = $row['height'];
    $bmi = calculateBMI($new_weight, $height);

    $stmt = $conn->prepare("INSERT INTO progress (user_id, weight, bmi, date) VALUES (?, ?, ?, CURDATE())");
    $stmt->bind_param("idd", $user_id, $new_weight, $bmi);
    $stmt->execute();

    // Update user's current weight in users table
    $conn->query("UPDATE users SET current_weight=$new_weight WHERE id=$user_id");
}

?>
