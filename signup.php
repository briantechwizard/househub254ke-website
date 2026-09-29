<?php
include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>HouseHubKe - <?= $translations[$lang]['signup_page'] ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Main content -->
    <main>
        <section class="form-section">
            <h2><?= $translations[$lang]['signup_heading'] ?></h2>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirm = $_POST['confirm'];

                if ($password !== $confirm) {
                    echo "<p style='color:red; text-align:center;'>".$translations[$lang]['password_mismatch']."</p>";
                } else {
                    $hashed = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $email, $hashed);

                    if ($stmt->execute()) {
                        echo "<p style='color:green; text-align:center;'>".$translations[$lang]['signup_success']."</p>";
                        header("Refresh:2; url=login.php");
                    } else {
                        echo "<p style='color:red; text-align:center;'>".$translations[$lang]['signup_error']." " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                }
            }
            ?>

            <form class="form-box" method="post" autocomplete="off">
                <label for="name"><?= $translations[$lang]['full_name'] ?></label>
                <input type="text" id="name" name="name" required autocomplete="off">

                <label for="email"><?= $translations[$lang]['email'] ?></label>
                <input type="email" id="email" name="email" required autocomplete="off">

                <label for="password"><?= $translations[$lang]['password'] ?></label>
                <input type="password" id="password" name="password" required autocomplete="off">

                <label for="confirm"><?= $translations[$lang]['confirm_password'] ?></label>
                <input type="password" id="confirm" name="confirm" required autocomplete="off">

                <button type="submit" class="glow-btn"><?= $translations[$lang]['signup_button'] ?></button>
            </form>
            <p><?= $translations[$lang]['already_account'] ?> <a href="login.php"><?= $translations[$lang]['login'] ?></a></p>
        </section>
    </main>

    <!-- Footer -->
    <?php include('footer.php'); ?>
</body>
</html>
