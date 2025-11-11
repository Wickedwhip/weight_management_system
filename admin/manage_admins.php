<?php
session_start();
include('../config/db.php');

// Restrict access — only logged-in admins
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Delete admin
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_admins.php");
    exit();
}

// Reset password (auto reset to 'admin123')
if (isset($_GET['reset'])) {
    $id = intval($_GET['reset']);
    $newPass = password_hash("admin123", PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPass, $id);
    $stmt->execute();
    $msg = "✅ Password reset to 'admin123'.";
}

// Fetch all admins
$result = $conn->query("SELECT id, username, created_at FROM admins ORDER BY id ASC");
?>

<?php include('../includes/admin_header.php'); ?>

<section class="home-section">
  <div class="overlay">
    <h1>Manage Admins</h1>
    <?php if (!empty($msg)): ?>
      <p style="color:#feca57;"><?php echo $msg; ?></p>
    <?php endif; ?>

    <table class="styled-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Username</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['username']); ?></td>
              <td><?php echo $row['created_at']; ?></td>
              <td>
                <a href="?reset=<?php echo $row['id']; ?>" class="btn small-btn">Reset Password</a>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn small-btn danger"
                   onclick="return confirm('Delete this admin?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4">No admins found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="admin_register.php" class="btn main-btn" style="margin-top:20px;">+ Add New Admin</a>
  </div>
</section>

<style>
  table.styled-table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background: #222;
    color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.3);
  }

  table.styled-table th, table.styled-table td {
    padding: 12px 16px;
    text-align: center;
    border-bottom: 1px solid #333;
  }

  table.styled-table th {
    background: #ff2e63;
    text-transform: uppercase;
  }

  table.styled-table tr:hover {
    background: rgba(255,46,99,0.1);
  }

  .btn.small-btn {
    padding: 8px 12px;
    font-size: 0.85rem;
    border-radius: 6px;
    text-decoration: none;
    margin-right: 5px;
    display: inline-block;
  }

  .btn.danger {
    background: #ff4747;
  }

  .btn.danger:hover {
    background: #ff2e2e;
  }
</style>

<?php include('../includes/admin_footer.php'); ?>
