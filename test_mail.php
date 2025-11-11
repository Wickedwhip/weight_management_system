<?php
include('includes/mail_config.php');

if (sendReminderEmail('receiver@gmail.com', 'Test Email', 'This is a test message.')) {
    echo "✅ Email sent successfully!";
} else {
    echo "❌ Email sending failed!";
}
?>
