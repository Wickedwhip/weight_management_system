<?php
session_start();
include('../config/db.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];

            // ✅ Use JS redirect with sessionStorage for login success toast
            echo "<script>
                sessionStorage.setItem('loginSuccess', 'true');
                window.location.href = 'dashboard.php';
            </script>";
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>


<?php include('../includes/admin_header.php'); ?>
<section class="home-section">
  <div class="overlay">
    <h1>Admin Login</h1>
    <?php if (!empty($error)): ?>
      <p style="color:#ff4d4d;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" class="form-box">
  <label for="username">Username or Email</label>
  <input type="text" id="username" name="username" placeholder="Enter username or email" required>

  <label for="password">Password</label>
  <input type="password" id="password" name="password" placeholder="Enter password" required>

  <button type="submit" name="login" class="btn main-btn">Login</button>
</form>

  </div>
</section>

<style>
  .form-box {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 300px;
    margin: 30px auto;
  }
  input {
    padding: 12px;
    border: 1px solid #555;
    border-radius: 8px;
    background: #222;
    color: #fff;
  }
</style>
<?php include('../includes/admin_footer.php'); ?>
