<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "weight_management";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
