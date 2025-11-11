<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: index.php");
  exit();
}
include('../config/db.php');
include('../includes/admin_header.php');

// Fetch system totals
$tables = [
  "users" => "👤 Users",
  "workouts" => "💪 Workouts",
  "meals" => "🍽 Meals",
  "progress" => "📊 Progress",
  "reminders" => "⏰ Reminders"
];

$data = [];
foreach ($tables as $table => $label) {
  $result = $conn->query("SELECT COUNT(*) AS total FROM $table");
  $count = $result ? $result->fetch_assoc()['total'] : 0;
  $data[$table] = $count;
}

// Weekly user registration trend
$trendQuery = $conn->query("
  SELECT DATE(created_at) AS date, COUNT(*) AS count
  FROM users
  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
  GROUP BY DATE(created_at)
  ORDER BY DATE(created_at)
");
$trendDates = [];
$trendCounts = [];
while ($row = $trendQuery->fetch_assoc()) {
  $trendDates[] = $row['date'];
  $trendCounts[] = $row['count'];
}
?>

<section class="dashboard">
  <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['admin']); ?> 👋</h1>
  <p class="subtitle">Your analytics overview for the Weight Management System.</p>

  <!-- Stat Cards -->
  <div class="stats-grid">
    <?php foreach ($tables as $table => $label): ?>
      <div class="stat-card">
        <div class="stat-icon"><?php echo explode(' ', $label)[0]; ?></div>
        <div class="stat-info">
          <h2><?php echo $data[$table]; ?></h2>
          <p><?php echo $label; ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Charts -->
  <div class="charts">
    <div class="chart-card">
      <h2>📈 System Overview</h2>
      <canvas id="systemOverviewChart"></canvas>
    </div>

    <div class="chart-card">
      <h2>📊 User Composition</h2>
      <canvas id="userChart"></canvas>
    </div>

    <div class="chart-card wide">
      <h2>📅 Weekly User Registrations</h2>
      <canvas id="userTrendChart"></canvas>
    </div>
  </div>
</section>

<style>
.dashboard {
  text-align: center;
  color: #fff;
  padding: 40px;
  background: #111;
  min-height: 100vh;
}
.subtitle {
  color: #ccc;
  font-size: 14px;
  margin-bottom: 25px;
}
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 20px;
  margin-bottom: 40px;
}
.stat-card {
  background: #1e1e1e;
  border-radius: 12px;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
  box-shadow: 0 4px 10px rgba(255,46,99,0.25);
  transition: all 0.3s ease;
}
.stat-card:hover {
  transform: translateY(-4px);
  background: #222;
}
.stat-icon {
  font-size: 30px;
}
.stat-info h2 {
  margin: 0;
  font-size: 24px;
  color: #ff2e63;
}
.stat-info p {
  margin: 0;
  font-size: 14px;
  color: #bbb;
}
.charts {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
  gap: 30px;
  margin-top: 30px;
}
.chart-card {
  background: #1c1c1c;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.chart-card h2 {
  font-size: 16px;
  color: #eee;
  margin-bottom: 15px;
  text-align: left;
}
.chart-card.wide {
  grid-column: span 2;
}
canvas {
  max-width: 100%;
  height: 260px !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const data = <?php echo json_encode($data); ?>;
const trendLabels = <?php echo json_encode($trendDates); ?>;
const trendData = <?php echo json_encode($trendCounts); ?>;

// Overview bar chart
new Chart(document.getElementById('systemOverviewChart'), {
  type: 'bar',
  data: {
    labels: Object.keys(data).map(k => k.charAt(0).toUpperCase() + k.slice(1)),
    datasets: [{
      label: 'Total Entries',
      data: Object.values(data),
      backgroundColor: ['#ff2e63','#08d9d6','#ff8c00','#00c853','#aa00ff'],
      borderRadius: 8
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      x: { ticks: { color: '#ccc' }, grid: { color: '#333' } },
      y: { ticks: { color: '#ccc' }, grid: { color: '#333' } },
    }
  }
});

// Pie chart
new Chart(document.getElementById('userChart'), {
  type: 'doughnut',
  data: {
    labels: ['Users','Workouts','Meals','Progress','Reminders'],
    datasets: [{
      data: Object.values(data),
      backgroundColor: ['#ff2e63','#08d9d6','#feca57','#00c853','#aa00ff'],
      hoverOffset: 8
    }]
  },
  options: {
    plugins: {
      legend: {
        labels: { color: '#fff' }
      }
    }
  }
});

// Line chart for weekly registrations
new Chart(document.getElementById('userTrendChart'), {
  type: 'line',
  data: {
    labels: trendLabels,
    datasets: [{
      label: 'New Users per Day',
      data: trendData,
      borderColor: '#08d9d6',
      backgroundColor: 'rgba(8,217,214,0.2)',
      fill: true,
      tension: 0.3,
      borderWidth: 2,
      pointBackgroundColor: '#ff2e63',
      pointRadius: 4
    }]
  },
  options: {
    plugins: {
      legend: { labels: { color: '#fff' } }
    },
    scales: {
      x: { ticks: { color: '#ccc' }, grid: { color: '#333' } },
      y: { ticks: { color: '#ccc' }, grid: { color: '#333' } }
    }
  }
});
</script>

<?php include('../includes/footer.php'); ?>
