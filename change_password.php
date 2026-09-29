<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current = $_POST['current'];
    $new = $_POST['new'];
    $confirm = $_POST['confirm'];

    // Fetch current password hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result && password_verify($current, $result['password'])) {
        if ($new === $confirm) {
            $hashed = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed, $_SESSION['user_id']);
            if ($stmt->execute()) {
                echo "<p style='color:green;text-align:center;'>Password updated!</p>";
            } else {
                echo "<p style='color:red;text-align:center;'>Error updating password!</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color:red;text-align:center;'>Passwords do not match!</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>Current password incorrect!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure placeholders are visible in dark theme */
        ::placeholder {
            color: #ccc;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2>Change Password</h2>
            <form method="post">
                <label>Current Password</label>
                <input type="password" name="current" placeholder="Enter current password" required>

                <label>New Password</label>
                <input type="password" name="new" placeholder="Enter new password" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirm" placeholder="Confirm new password" required>

                <button type="submit" class="glow-btn">Update Password</button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
