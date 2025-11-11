<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}
include('../config/db.php');
include('includes/admin_header.php');
?>

<section class="manage-reminders">
  <h1>User Reminders</h1>
  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>User ID</th>
      <th>Message</th>
      <th>Date</th>
      <th>Action</th>
    </tr>

    <?php
    $query = "SELECT * FROM reminders ORDER BY created_at DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['message']}</td>
                <td>{$row['created_at']}</td>
                <td><a href='delete_reminder.php?id={$row['id']}'>Delete</a></td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='5' style='text-align:center;'>No reminders found.</td></tr>";
    }
    ?>
  </table>
</section>

<style>
.manage-reminders {
  text-align: center;
  color: #fff;
  padding: 40px;
}
.manage-reminders table {
  margin: 0 auto;
  width: 90%;
  background: #111;
  border-collapse: collapse;
  color: #ddd;
}
.manage-reminders th {
  background: #feca57;
  color: #111;
  padding: 12px;
}
.manage-reminders td {
  padding: 10px;
  border: 1px solid #333;
}
.manage-reminders a {
  color: #ff2e63;
  text-decoration: none;
}
.manage-reminders a:hover {
  text-decoration: underline;
}
</style>

<?php include('../includes/footer.php'); ?>
