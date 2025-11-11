<?php 
include('../includes/header.php'); 
include('../config/db.php'); 
?>

<section class="form-container">
  <h2>Login</h2>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $email = trim($_POST['email']);
      $password = $_POST['password'];

      $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 1) {
          $user = $result->fetch_assoc();
          if (password_verify($password, $user['password'])) {
              $_SESSION['user_id'] = $user['id'];
              $_SESSION['user_name'] = $user['name'];
              header("Location: dashboard.php");
              exit();
          } else {
              echo "<p class='error'>Incorrect password.</p>";
          }
      } else {
          echo "<p class='error'>User not found.</p>";
      }
  }
  ?>

  <form method="POST">
      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit" class="btn">Login</button>
  </form>
</section>

<?php include('../includes/footer.php'); ?>
