<?php
include('../includes/header.php');
include('../includes/functions.php');
include('../config/db.php');
include('../includes/mail_config.php');

checkLogin();

$user_id = $_SESSION['user_id'];

// Handle new reminder form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    $send_time = $_POST['send_time'];

    if (!empty($message) && !empty($send_time)) {
        $stmt = $conn->prepare("INSERT INTO reminders (user_id, message, send_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $message, $send_time);
        if ($stmt->execute()) {
            // Store success message in session for next page load
            $_SESSION['reminder_success'] = "✅ Reminder added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $_SESSION['reminder_error'] = "❌ Failed to add reminder.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        $_SESSION['reminder_error'] = "⚠️ Please fill in all fields.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Show flash messages (if any)
if (isset($_SESSION['reminder_success'])) {
    echo "<p class='success'>{$_SESSION['reminder_success']}</p>";
    unset($_SESSION['reminder_success']);
}

if (isset($_SESSION['reminder_error'])) {
    echo "<p class='error'>{$_SESSION['reminder_error']}</p>";
    unset($_SESSION['reminder_error']);
}


// Fetch reminders for this user
$stmt = $conn->prepare("SELECT * FROM reminders WHERE user_id=? ORDER BY send_time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="form-container">
    <h2>📧 Set Email Reminder</h2>
    <form method="POST">
        <label>Reminder Message:</label>
        <textarea name="message" rows="3" placeholder="E.g. Log your workout today!" required></textarea>

        <label>Send Time:</label>
        <input type="datetime-local" name="send_time" required>

        <button type="submit" class="btn">Save Reminder</button>
    </form>
</section>

<section class="dashboard">
    <h2>Your Reminders</h2>
    <table>
        <tr>
            <th>Message</th>
            <th>Send Time</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo htmlspecialchars($row['send_time']); ?></td>
                <td>
                    <?php echo $row['sent'] ? "<span style='color: #00c851;'>Sent</span>" : "<span style='color: #ffbb33;'>Pending</span>"; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<footer>
  © <?php echo date('Y'); ?> Weight Management System | Stay Consistent 💪
</footer>

<?php include('../includes/footer.php'); ?>
