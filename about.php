<?php
session_start();
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>HouseHubKe - <?= $translations[$lang]['about_us_page'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <section class="hero">
        <div class="hero-content">
            <h1><?= $translations[$lang]['about_heading'] ?></h1>
            <p><?= $translations[$lang]['about_intro'] ?></p>
        </div>
    </section>

    <section class="container">
        <h2 style="text-shadow:0 0 10px #FFD700;"><?= $translations[$lang]['vision_heading'] ?></h2>
        <p><?= $translations[$lang]['vision_text'] ?></p>

        <h2 style="margin-top:30px;text-shadow:0 0 10px #FFD700;"><?= $translations[$lang]['values_heading'] ?></h2>
        <ul style="margin-top:10px;line-height:1.8;">
            <li><?= $translations[$lang]['value_transparency'] ?></li>
            <li><?= $translations[$lang]['value_accessibility'] ?></li>
            <li><?= $translations[$lang]['value_innovation'] ?></li>
            <li><?= $translations[$lang]['value_customer'] ?></li>
        </ul>
    </section>

    <?php include('footer.php'); ?>
</body>
</html>
