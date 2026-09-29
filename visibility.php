<?php
include('db.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $showPhone = isset($_POST['show_phone']) ? 1 : 0;
    $showEmail = isset($_POST['show_email']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET show_phone=?, show_email=? WHERE id=?");
    $stmt->bind_param("iii", $showPhone, $showEmail, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo "<p style='color:green;text-align:center;'>Visibility settings updated!</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>Error updating settings.</p>";
    }
    $stmt->close();
}

// Fetch current values from DB
$stmt = $conn->prepare("SELECT show_phone, show_email FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($showPhone, $showEmail);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Visibility - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-section h2,
        .form-section label {
            color: #fff;
            font-weight: bold;
        }
        .form-section input[type="checkbox"] {
            accent-color: #FFD700;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2>Control Contact Visibility</h2>
            <form method="post">
                <label>
                    <input type="checkbox" name="show_phone" <?= $showPhone ? 'checked' : '' ?>>
                    Show Phone Number
                </label><br>
                <label>
                    <input type="checkbox" name="show_email" <?= $showEmail ? 'checked' : '' ?>>
                    Show Email Address
                </label><br>
                <button type="submit" class="glow-btn">Save</button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
