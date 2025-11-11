<?php 
include('../includes/header.php');
include('../includes/functions.php');
include('../config/db.php');
checkLogin();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $workout_type = trim($_POST['workout_type']);
    $calories_burned = floatval($_POST['calories_burned']);
    $duration = intval($_POST['duration']);
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO workouts (user_id, workout_type, calories_burned, duration, date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $workout_type, $calories_burned, $duration, $date);

    if ($stmt->execute()) {
        echo "<p class='success'>Workout logged successfully!</p>";
    } else {
        echo "<p class='error'>Failed to log workout.</p>";
    }
}
?>

<section class="form-container">
  <h2>🏋️ Log a Workout</h2>
  <form method="POST">
      <label>Workout Type:</label>
      <input type="text" name="workout_type" required>

      <label>Calories Burned:</label>
      <input type="number" step="0.1" name="calories_burned" required>

      <label>Duration (minutes):</label>
      <input type="number" name="duration" required>

      <label>Date:</label>
      <input type="date" name="date" required>

      <button type="submit" class="btn">Add Workout</button>
  </form>
</section>

<section class="list-container">
  <h3>Recent Workouts</h3>
  <table>
    <tr><th>Date</th><th>Workout</th><th>Calories Burned</th><th>Duration</th></tr>
    <?php
    $result = $conn->query("SELECT * FROM workouts WHERE user_id=$user_id ORDER BY date DESC LIMIT 10");
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['date']}</td><td>{$row['workout_type']}</td><td>{$row['calories_burned']} kcal</td><td>{$row['duration']} min</td></tr>";
    }
    ?>
  </table>
</section>

<?php include('../includes/footer.php'); ?>
