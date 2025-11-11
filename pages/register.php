<?php 
include('../includes/header.php'); 
include('../config/db.php'); 
include('../includes/functions.php'); // ✅ make sure this file has calculateBMI()
?>

<section class="form-container">
  <h2>Create Account</h2>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $name = trim($_POST['name']);
      $email = trim($_POST['email']);
      $height = floatval($_POST['height']);
      $weight = floatval($_POST['weight']);
      $goal_weight = floatval($_POST['goal_weight']);
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

      // Check if email exists
      $check = $conn->prepare("SELECT * FROM users WHERE email=?");
      $check->bind_param("s", $email);
      $check->execute();
      $result = $check->get_result();

      if ($result->num_rows > 0) {
          echo "<p class='error'>Email already registered!</p>";
      } else {
          // ✅ Insert into users table
          $stmt = $conn->prepare("INSERT INTO users (name, email, password, height, current_weight, goal_weight) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("sssddd", $name, $email, $password, $height, $weight, $goal_weight);

          if ($stmt->execute()) {
              // ✅ AUTO INITIAL PROGRESS ENTRY
              $newUserId = $stmt->insert_id;
              $initialWeight = $weight;
              $bmi = calculateBMI($initialWeight, $height);

              // Insert baseline progress
              $conn->query("INSERT INTO progress (user_id, weight, bmi, date) VALUES ($newUserId, $initialWeight, $bmi, CURDATE())");

              echo "<p class='success'>Account created! <a href='login.php'>Login now</a>.</p>";
          } else {
              echo "<p class='error'>Error creating account.</p>";
          }
      }
  }
  ?>

  <form method="POST">
      <label>Name:</label>
      <input type="text" name="name" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Height (cm):</label>
      <input type="number" step="0.1" name="height" required>

      <label>Current Weight (kg):</label>
      <input type="number" step="0.1" name="weight" required>

      <label>Goal Weight (kg):</label>
      <input type="number" step="0.1" name="goal_weight" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit" class="btn">Register</button>
  </form>
</section>

<?php include('../includes/footer.php'); ?>
