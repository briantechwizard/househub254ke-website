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
    <title><?= $translations[$lang]['verify_code_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2><?= $translations[$lang]['verify_code_heading'] ?></h2>
            <form method="post" action="check_code.php">
                <label><?= $translations[$lang]['enter_verification_code'] ?></label>
                <input type="text" name="code" placeholder="<?= $translations[$lang]['enter_code_placeholder'] ?>" required>
                <button type="submit" class="glow-btn"><?= $translations[$lang]['verify_button'] ?></button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
