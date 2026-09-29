<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

// Load current user data
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $_SESSION['user_id']);
    if ($stmt->execute()) {
        echo "<p style='color:green;text-align:center;'>".$translations[$lang]['profile_updated']."</p>";
    } else {
        echo "<p style='color:red;text-align:center;'>".$translations[$lang]['error_updating_profile']."</p>";
    }
    $stmt->close();

    // Refresh user data after update
    $stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['edit_profile_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2><?= $translations[$lang]['edit_profile_heading'] ?></h2>
            <form method="post">
                <label><?= $translations[$lang]['name'] ?></label>
                <input type="text" name="name" placeholder="<?= $translations[$lang]['enter_full_name'] ?>" required 
                       value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">

                <label><?= $translations[$lang]['email'] ?></label>
                <input type="email" name="email" placeholder="<?= $translations[$lang]['enter_email'] ?>" required 
                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">

                <label><?= $translations[$lang]['phone'] ?></label>
                <input type="text" name="phone" placeholder="<?= $translations[$lang]['enter_phone'] ?>" 
                       value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">

                <button type="submit" class="glow-btn"><?= $translations[$lang]['save_changes'] ?></button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
