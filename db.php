<?php
// Start session for login system
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection settings (default localhost)
$host     = "localhost";     // usually localhost
$user     = "root";          // default MySQL username in XAMPP
$password = "";              // default MySQL password (empty in XAMPP)
$database = "househubke254"; // your database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

