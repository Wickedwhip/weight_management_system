<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}
include('../config/db.php');
include('includes/admin_header.php');
?>

<section class="manage-progress">
  <h1>User Progress Records</h1>
  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>User ID</th>
      <th>Current Weight</th>
      <th>Goal Weight</th>
      <th>Date</th>
    </tr>

    <?php
    $query = "SELECT * FROM progress ORDER BY created_at DESC";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['user_id']}</td>
                <td>{$row['current_weight']}</td>
                <td>{$row['goal_weight']}</td>
                <td>{$row['created_at']}</td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='5' style='text-align:center;'>No progress records found.</td></tr>";
    }
    ?>
  </table>
</section>

<style>
.manage-progress {
  text-align: center;
  color: #fff;
  padding: 40px;
}
.manage-progress table {
  margin: 0 auto;
  width: 90%;
  background: #111;
  border-collapse: collapse;
  color: #ddd;
}
.manage-progress th {
  background: #ff2e63;
  color: #fff;
  padding: 12px;
}
.manage-progress td {
  padding: 10px;
  border: 1px solid #333;
}
</style>

<?php include('../includes/footer.php'); ?>
