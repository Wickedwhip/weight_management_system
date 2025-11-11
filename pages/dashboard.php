<?php 
include('../includes/header.php'); 
include('../includes/functions.php');
checkLogin();
?>

<section class="dashboard">
  <h2>Welcome, <span style="color:#feca57;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span> 💪</h2>
  <p>Stay consistent. Eat clean. Train hard. Track your journey below.</p>

  <div class="cards">
    <div class="card">
      <i class="fas fa-utensils"></i>
      <a href="add_meal.php">Add Meal</a>
    </div>

    <div class="card">
      <i class="fas fa-dumbbell"></i>
      <a href="add_workout.php">Add Workout</a>
    </div>

    <div class="card">
      <i class="fas fa-chart-line"></i>
      <a href="progress.php">View Progress</a>
    </div>

    <div class="card">
      <i class="fas fa-envelope-open-text"></i>
      <a href="reminders.php">Email Reminders</a>
    </div>
  </div>
</section>

<footer>
  © <?php echo date('Y'); ?> Weight Management System. Built for Strength & Discipline 🏆
</footer>

<!-- Font Awesome (for icons) -->
<script src="https://kit.fontawesome.com/a2d9d5a64d.js" crossorigin="anonymous"></script>

<?php include('../includes/footer.php'); ?>
