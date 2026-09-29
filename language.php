<?php
include('db.php');
// Step 1: Save language choice
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $language = $_POST['language'];
    $_SESSION['language'] = $language;

    // Optional: save to database for persistence
    $stmt = $conn->prepare("UPDATE users SET language=? WHERE id=?");
    $stmt->bind_param("si", $language, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:green;text-align:center;'>Language updated to $language!</p>";
}

// Step 2: Load translations
$translations = [
    'en' => [
        'welcome' => 'Welcome to HouseHubKe',
        'browse' => 'Browse Houses',
        'signup' => 'Sign Up',
        'login' => 'Login',
        'language' => 'Select Language'
    ],
    'sw' => [
        'welcome' => 'Karibu HouseHubKe',
        'browse' => 'Tazama Nyumba',
        'signup' => 'Jisajili',
        'login' => 'Ingia',
        'language' => 'Chagua Lugha'
    ]
];

// Step 3: Pick current language
$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['language'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<main>
    <section class="form-section">
        <h2><?= $translations[$lang]['language'] ?></h2>
        <form method="post">
            <select name="language">
                <option value="en" <?= $lang == 'en' ? 'selected' : '' ?>>English</option>
                <option value="sw" <?= $lang == 'sw' ? 'selected' : '' ?>>Swahili</option>
            </select>
            <button type="submit" class="glow-btn">Save</button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
