<?php
session_start();
include('../config/db.php');

// Restrict access — only logged-in admins can add new ones
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$message = '';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username exists
    $check = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "⚠️ Username already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $message = "✅ New admin registered successfully!";
        } else {
            $message = "❌ Error adding admin. Try again.";
        }
    }
}
?>

<?php include('includes/admin_header.php');?>

<section class="home-section">
  <div class="overlay">
    <h1>Add New Admin</h1>
    <?php if (!empty($message)): ?>
      <p style="color:#feca57;"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" class="form-box">
      <input type="text" name="username" placeholder="Admin Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="register" class="btn main-btn">Register</button>
    </form>
  </div>
</section>

<style>
  .form-box {
    display: flex;
    flex-direction: column;
    gap: 15px;
    width: 320px;
    margin: 30px auto;
  }
  input {
    padding: 12px;
    border: 1px solid #555;
    border-radius: 8px;
    background: #222;
    color: #fff;
  }
  h1 {
    margin-bottom: 20px;
    color: #feca57;
  }
</style>

<?php include('includes/admin_footer.php');?>
