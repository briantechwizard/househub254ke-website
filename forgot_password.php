<?php
include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['forgot_password_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2><?= $translations[$lang]['forgot_password_heading'] ?></h2>
            <form method="post" action="send_code.php">
                <label><?= $translations[$lang]['email_address'] ?></label>
                <input type="email" name="email" placeholder="<?= $translations[$lang]['enter_registered_email'] ?>" required>
                <button type="submit" class="glow-btn"><?= $translations[$lang]['send_verification_code'] ?></button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
