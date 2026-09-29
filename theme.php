<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $theme = "dark"; // force dark theme only
    $stmt = $conn->prepare("UPDATE users SET theme=? WHERE id=?");
    $stmt->bind_param("si", $theme, $_SESSION['user_id']);
    $stmt->execute();
    echo "<p style='color:green;text-align:center;'>Theme updated to Dark!</p>";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Theme Selection - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure text is visible */
        .form-section label {
            color: #fff;
            font-weight: bold;
            display: block;
            margin: 12px 0;
        }
        .form-section input[type="radio"] {
            accent-color: #FFD700;
            margin-right: 8px;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2>Select Theme</h2>
            <form method="post">
                <label><input type="radio" name="theme" value="dark" checked> Dark</label><br>
                <button type="submit" class="glow-btn">Apply Theme</button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
