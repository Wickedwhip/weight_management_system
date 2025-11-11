<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Weight Management System</title>
  <link rel="stylesheet" href="/weight_management_system/assets/css/style.css">
</head>
<body>
  <header>
    <nav>
      <div class="nav-left">
        <img src="/weight_management_system/assets/img/logo.jpg" alt="Gym Logo" class="nav-logo">
        <h2>Weight Management System</h2>
      </div>
      <ul>
        <?php if(isset($_SESSION['user_id'])): ?>
          <li><a href="/weight_management_system/pages/dashboard.php">Dashboard</a></li>
          <li><a href="/weight_management_system/pages/add_meal.php">Add Meal</a></li>
          <li><a href="/weight_management_system/pages/add_workout.php">Add Workout</a></li>
          <li><a href="/weight_management_system/pages/progress.php">Progress</a></li>
          <li><a href="/weight_management_system/pages/logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="/weight_management_system/pages/login.php">Login</a></li>
          <li><a href="/weight_management_system/pages/register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <main>
