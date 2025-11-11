<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
include('../config/db.php');
include('../includes/admin_header.php');
?>

<section class="manage-users">
  <h1>Manage Users</h1>

  <table border="1" cellpadding="10" cellspacing="0">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
      <th>Height</th>
      <th>Current Weight</th>
      <th>Goal Weight</th>
      <th>Created At</th>
      <th>Action</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM users");

    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
    ?>
      <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['height']); ?></td>
        <td><?php echo htmlspecialchars($row['current_weight']); ?></td>
        <td><?php echo htmlspecialchars($row['goal_weight']); ?></td>
        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
        <td>
         <a href="#" onclick="confirmDelete(event, 'delete_user.php?id=<?php echo $row['id']; ?>')">Delete</a>

        </td>
      </tr>
    <?php
        endwhile;
    else:
        echo "<tr><td colspan='8'>No users found.</td></tr>";
    endif;
    ?>
  </table>
</section>

<style>
  .manage-users {
    text-align: center;
    padding: 50px 20px;
    color: #fff;
  }

  table {
    margin: 0 auto;
    border-collapse: collapse;
    width: 90%;
    background: #111;
    color: #fff;
    border-radius: 8px;
    overflow: hidden;
  }

  th {
    background: #ff2e63;
    padding: 12px;
  }

  td {
    padding: 10px;
    border-bottom: 1px solid #333;
  }

  tr:hover {
    background: rgba(255, 46, 99, 0.1);
  }

  a {
    text-decoration: none;
  }
</style>

<?php include('../includes/admin_footer.php'); ?>
