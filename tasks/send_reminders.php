<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../includes/mail_config.php');

// Fetch all pending reminders that are due
$sql = "SELECT r.id, r.message, r.send_time, u.email
        FROM reminders r
        JOIN users u ON r.user_id = u.id
        WHERE r.sent = 0 AND r.send_time <= NOW()";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $toEmail = $row['email'];
        $subject = "💪 Weight Management Reminder";
        $message = "<p>{$row['message']}</p><br><b>Stay consistent!</b>";

        if (sendReminderEmail($toEmail, $subject, $message)) {
            // Mark as sent
            $update = $conn->prepare("UPDATE reminders SET sent = 1 WHERE id=?");
            $update->bind_param("i", $row['id']);
            $update->execute();

            echo "[" . date('Y-m-d H:i:s') . "] ✅ Sent reminder to {$toEmail} ({$row['message']})\n";
        } else {
            echo "[" . date('Y-m-d H:i:s') . "] ❌ Failed to send reminder to {$toEmail}\n";
        }
    }
} else {
    echo "[" . date('Y-m-d H:i:s') . "] ⏳ No due reminders found.\n";
}

$conn->close();
?>
