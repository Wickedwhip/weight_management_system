<?php 
include('../includes/header.php');
include('../includes/functions.php');
include('../config/db.php');
checkLogin();

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meal_name = trim($_POST['meal_name']);
    $calories = floatval($_POST['calories']);
    $date = $_POST['date'];

    $stmt = $conn->prepare("INSERT INTO meals (user_id, meal_name, calories, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $user_id, $meal_name, $calories, $date);
    if ($stmt->execute()) {
        echo "<p class='success'>Meal added successfully!</p>";
    } else {
        echo "<p class='error'>Failed to add meal.</p>";
    }
}
?>

<section class="form-container">
  <h2>🍽️ Log a Meal</h2>
  <form method="POST">
      <label>Meal Name:</label>
      <input type="text" name="meal_name" required>

      <label>Calories:</label>
      <input type="number" step="0.1" name="calories" required>

      <label>Date:</label>
      <input type="date" name="date" required>

      <button type="submit" class="btn">Add Meal</button>
  </form>
</section>

<section class="list-container">
  <h3>Recent Meals</h3>
  <table>
    <tr><th>Date</th><th>Meal</th><th>Calories</th></tr>
    <?php
    $result = $conn->query("SELECT * FROM meals WHERE user_id=$user_id ORDER BY date DESC LIMIT 10");
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['date']}</td><td>{$row['meal_name']}</td><td>{$row['calories']} kcal</td></tr>";
    }
    ?>
  </table>
</section>

<?php include('../includes/footer.php'); ?>
