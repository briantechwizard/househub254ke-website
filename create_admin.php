<?php
// Include database connection
include('db.php');

// --- Set your admin credentials here ---
$username = "Brian Kiiti";   // change this to your preferred username
$password = "Goojara007$"; // change this to your preferred password

// Hash the password for security
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into admins table
$sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "✅ Admin account created successfully.<br>";
    echo "Username: " . htmlspecialchars($username) . "<br>";
    echo "Password: " . htmlspecialchars($password) . "<br>";
    echo "Now delete this file (create_admin.php) for security.";
} else {
    echo "❌ Error creating admin: " . $conn->error;
}
?>
