<?php
include('../config/db.php');

$new_password = password_hash('admin123', PASSWORD_DEFAULT);
$conn->query("UPDATE admins SET password='$new_password' WHERE username='admin'");
echo "✅ Admin password reset to 'admin123'";
?>
