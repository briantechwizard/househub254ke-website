<?php
session_start();
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
include('db.php');

// Clear session
session_unset();
session_destroy();

// Optional: show a logout message before redirect
// echo "<p style='text-align:center;color:green;'>".$translations[$lang]['logout_message']."</p>";

// Redirect to homepage as guest
header("Location: index.php");
exit();
?>
