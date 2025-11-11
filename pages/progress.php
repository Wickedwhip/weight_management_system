<?php 
include('../includes/header.php');
include('../includes/functions.php');
include('../config/db.php');
checkLogin();

$user_id = $_SESSION['user_id'];

// Handle new weight submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_weight = floatval($_POST['new_weight']);
    updateProgress($conn, $user_id, $new_weight);
    echo "<p class='success'>Progress updated successfully!</p>";
}

// Fetch progress history
$progress_query = $conn->query("SELECT date, weight, bmi FROM progress WHERE user_id=$user_id ORDER BY date ASC");

$dates = [];
$weights = [];
$bmis = [];

while ($row = $progress_query->fetch_assoc()) {
    $dates[] = $row['date'];
    $weights[] = $row['weight'];
    $bmis[] = $row['bmi'];
}
?>

<section class="form-container">
  <h2>📈 Track Your Progress</h2>
  <form method="POST">
      <label>Update Current Weight (kg):</label>
      <input type="number" step="0.1" name="new_weight" required>
      <button type="submit" class="btn">Update Progress</button>
  </form>
</section>

<section class="chart-container">
  <h3>Weight and BMI Progress Over Time</h3>
  <canvas id="progressChart"></canvas>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('progressChart').getContext('2d');
const progressChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [
            {
                label: 'Weight (kg)',
                data: <?php echo json_encode($weights); ?>,
                borderColor: '#0074D9',
                tension: 0.3,
                fill: false
            },
            {
                label: 'BMI',
                data: <?php echo json_encode($bmis); ?>,
                borderColor: '#FF4136',
                tension: 0.3,
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: { y: { beginAtZero: false } }
    }
});
</script>

<?php include('../includes/footer.php'); ?>
