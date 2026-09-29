<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['reset_password_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2><?= $translations[$lang]['reset_password_heading'] ?></h2>
            <form method="post" action="update_password.php">
                <label><?= $translations[$lang]['new_password'] ?></label>
                <input type="password" name="password" placeholder="<?= $translations[$lang]['enter_new_password'] ?>" required>
                <button type="submit" class="glow-btn"><?= $translations[$lang]['reset_password_button'] ?></button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
