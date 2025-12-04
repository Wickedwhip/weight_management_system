<?php  
session_start();
include('../includes/header.php');
include('../includes/functions.php');
include('../config/db.php');
checkLogin();

$user_id = $_SESSION['user_id'];

// Email helper (placeholder)
function sendMail($to, $subject, $message){
    // prevent fatal error if mailing not implemented
}

// Fetch goal weight, email, height
$userQuery = $conn->query("SELECT goal_weight, email, height FROM users WHERE id=$user_id");
$userData = $userQuery->fetch_assoc();
$goal_weight = floatval($userData['goal_weight']);
$user_email = $userData['email'];
$currentHeight = floatval($userData['height']); // cm

// PROCESS FORM SUBMISSION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_weight = floatval($_POST['new_weight']);
    $new_height = isset($_POST['new_height']) ? floatval($_POST['new_height']) : null;

    if ($new_weight > 0) {

        // if height provided update it
        if ($new_height !== null && $new_height > 0) {
            $conn->query("UPDATE users SET height=$new_height WHERE id=$user_id");
            $currentHeight = $new_height;
        }

        // compute BMI using latest known height
        if ($currentHeight > 0) {
            $bmi = round($new_weight / (($currentHeight/100) * ($currentHeight/100)), 2);
        } else {
            $bmi = null;
        }

        // save
        $conn->query("
            INSERT INTO progress (user_id, weight, bmi) 
            VALUES ($user_id, $new_weight, $bmi)
        ");

        echo "<p class='success'>Progress updated successfully!</p>";
    }
}

// FETCH PROGRESS HISTORY
$progress_query = $conn->query("
    SELECT date, weight, bmi 
    FROM progress 
    WHERE user_id=$user_id 
    ORDER BY date ASC
");

$dates = [];
$weights = [];
$bmis = [];

while ($row = $progress_query->fetch_assoc()) {
    $dates[] = date("M d", strtotime($row['date']));
    $weights[] = round(floatval($row['weight']), 1);
    $bmis[] = round(floatval($row['bmi']), 2);
}

// COMPUTE CURRENT VALUES
$latestBMI = !empty($bmis) ? end($bmis) : null;
$latestWeight = !empty($weights) ? end($weights) : null;

$previousWeight = count($weights) > 1 ? $weights[count($weights)-2] : null;
$previousBMI = count($bmis) > 1 ? $bmis[count($bmis)-2] : null;

// ANALYSIS NUMBERS
$weightChange = $previousWeight !== null ? round($latestWeight - $previousWeight, 1) : null;
$BMIChange = $previousBMI !== null ? round($latestBMI - $previousBMI, 1) : null;

if ($goal_weight > 0 && $latestWeight !== null) {
    $progressPercent = round((($latestWeight - $goal_weight) / $goal_weight) * -100);
}

function getBMICategory($bmi) {
    if ($bmi === null) return "Unknown";
    if ($bmi < 18.5) return "Underweight";
    if ($bmi < 24.9) return "Normal";
    if ($bmi < 29.9) return "Overweight";
    return "Obese";
}

// EMAIL CATEGORY CHANGE
if ($latestBMI !== null && $previousBMI !== null) {

    $newCat = getBMICategory($latestBMI);
    $prevCat = getBMICategory($previousBMI);

    if ($newCat !== $prevCat && isset($user_email)) {
        $subject = "BMI Category Updated";
        $message = "Your BMI moved from $prevCat to $newCat";
        sendMail($user_email, $subject, $message);
    }
}

// WEEKLY RANKING
$weeklyQuery = $conn->query("
    SELECT WEEK(date) as weekNo, AVG(weight) as avgWeight
    FROM progress
    WHERE user_id=$user_id
    GROUP BY WEEK(date)
");

$weeks = [];
while($w = $weeklyQuery->fetch_assoc()) {
    $weeks[] = [
        "week" => $w['weekNo'],
        "avgWeight" => round($w['avgWeight'],1)
    ];
}

$bestWeek = null;
$worstWeek = null;

if (count($weeks) > 1) {
    $sorted = $weeks;
    usort($sorted, fn($a,$b) => $a['avgWeight'] <=> $b['avgWeight']);
    $bestWeek = $sorted[0];
    $worstWeek = end($sorted);
}
?>

<style>
.content-card {
    width: 92%;
    max-width: 780px;
    margin: 15px auto;
    padding: 18px;
    background: rgba(255,255,255,0.10);
    border-radius: 14px;
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
}
.chart-container {
    width: 92%;
    max-width: 850px;
    margin: 25px auto;
    background: rgba(255,255,255,0.08);
    border-radius: 14px;
    padding: 12px;
    border: 1px solid rgba(255,255,255,0.10);
    color:#fff;
}
</style>


<section class="form-container">
    <h2>📈 Track Your Progress</h2>
    <form method="POST">
        <label>Update Weight (kg):</label>
        <input type="number" step="0.1" name="new_weight" required>

        <label>Update Height (cm):</label>
        <input type="number" step="0.1" name="new_height">

        <button type="submit" class="btn">Update</button>
    </form>
</section>


<?php if ($latestBMI !== null): ?>
<div class="content-card">
    <h3>Your Latest BMI Report</h3>

    <p>BMI Value: <b><?php echo $latestBMI; ?></b></p>

    <?php 
        $cat = getBMICategory($latestBMI);
        $color = ($cat == "Underweight") ? "#ffc107" :
                 (($cat == "Normal") ? "#28a745" :
                 (($cat == "Overweight") ? "#d67700" : "#dc3545"));
    ?>

    <p style="color:<?php echo $color; ?>">
        <strong>Category: <?php echo $cat; ?></strong>
        <span style="
            background:<?php echo $color; ?>;
            color:white;
            padding:3px 8px;
            border-radius:12px;
            font-size:12px;
            margin-left:8px;
        ">
            <?php echo strtoupper(substr($cat,0,3)); ?>
        </span>
    </p>
</div>
<?php endif; ?>


<?php if ($latestWeight !== null): ?>
<div class="content-card">
    <h3>📊 Status Summary</h3>

    <p>Current Weight: <b><?php echo $latestWeight; ?> kg</b></p>

    <?php if ($previousWeight !== null): ?>
        <p>Weight Change:
            <b style="color:<?php echo ($weightChange < 0 ? 'lime' : 'red'); ?>">
                <?php echo ($weightChange > 0 ? "+" : "") . $weightChange; ?> kg
            </b>
        </p>
    <?php endif; ?>

    <?php if ($previousBMI !== null): ?>
        <p>BMI Change:
            <b style="color:<?php echo ($BMIChange < 0 ? 'lime' : 'red'); ?>">
                <?php echo ($BMIChange > 0 ? "+" : "") . $BMIChange; ?>
            </b>
        </p>
    <?php endif; ?>

    <?php if (isset($progressPercent)): ?>
        <p>Goal Progress: <b><?php echo $progressPercent; ?>%</b> complete</p>
    <?php endif; ?>

    <small>Update weekly for smart insights.</small>
</div>
<?php endif; ?>


<?php if ($bestWeek && $worstWeek): ?>
<div class="content-card">
    <h3>📅 Weekly Rankings</h3>
    <p>🏆 Best Week: <b>Week <?php echo $bestWeek['week']; ?></b> | Avg Weight: <b><?php echo $bestWeek['avgWeight']; ?> kg</b></p>
    <p>😓 Worst Week: <b>Week <?php echo $worstWeek['week']; ?></b> | Avg Weight: <b><?php echo $worstWeek['avgWeight']; ?> kg</b></p>
</div>
<?php endif; ?>


<section class="chart-container">
    <h3>Weight & BMI Progress Over Time</h3>
    <canvas id="progressChart"></canvas>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart = new Chart(document.getElementById('progressChart').getContext('2d'),{
    type:"line",
    data:{
        labels:<?php echo json_encode($dates); ?>,
        datasets:[
            {
                label:"Weight (kg)",
                data:<?php echo json_encode($weights); ?>,
                borderColor:"cyan",
                tension:0.3
            },
            {
                label:"BMI",
                data:<?php echo json_encode($bmis); ?>,
                borderColor:"orange",
                tension:0.3
            }
        ]
    }
});
</script>

<?php include('../includes/footer.php'); ?>
