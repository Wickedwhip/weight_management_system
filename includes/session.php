<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: /weight_management_system/admin/index.php");
    exit();
}
?>
