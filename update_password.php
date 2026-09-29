<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $newPassword, $email);

    if ($stmt->execute()) {
        // Fetch user details to log them in
        $stmt2 = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt2->bind_param("s", $email);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $user = $result->fetch_assoc();

        // Set the same session variable your login.php uses
        $_SESSION['username'] = $user['name'];

        // Redirect to homepage as logged in
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color:red;text-align:center;'>".$translations[$lang]['error_updating_password']."</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
