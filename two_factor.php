<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/Exception.php';
require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/PHPMailer.php';
require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/SMTP.php';

include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $twofa = isset($_POST['enable_2fa']) ? 1 : 0;
    $stmt = $conn->prepare("UPDATE users SET two_factor=? WHERE id=?");
    $stmt->bind_param("ii", $twofa, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    if ($twofa) {
        $code = rand(100000, 999999);
        $_SESSION['twofa_code'] = $code;

        // Fetch user email
        $stmt = $conn->prepare("SELECT email FROM users WHERE id=?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $stmt->bind_result($userEmail);
        $stmt->fetch();
        $stmt->close();

        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'househubke254@gmail.com'; // replace with your Gmail
            $mail->Password = 'ucfj btvy ysga duqc';   // replace with Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yourgmail@gmail.com', 'HouseHubKe');
            $mail->addAddress($userEmail);

            $mail->Subject = 'Your HouseHubKe Verification Code';
            $mail->Body    = "Hello,\n\nYour verification code is: $code\n\nEnter this code on the site to complete 2FA.";

            $mail->send();
            echo "<p style='color:green;text-align:center;'>".$translations[$lang]['twofa_updated']."<br>A verification code has been sent to your email.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;text-align:center;'>Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:green;text-align:center;'>".$translations[$lang]['twofa_updated']."</p>";
    }

    // Handle verification
    if (isset($_POST['verify_code'])) {
        if ($_POST['verify_code'] == $_SESSION['twofa_code']) {
            echo "<p style='color:lightgreen;text-align:center;'>Two-Factor Authentication verified successfully!</p>";
            unset($_SESSION['twofa_code']);
        } else {
            echo "<p style='color:red;text-align:center;'>Invalid code. Please try again.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['twofa_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body, h2, label { color: white; }
        .form-section { background: #1e1e1e; padding: 20px; border-radius: 8px; }
        .glow-btn { background: #FFD700; color: black; font-weight: bold; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section">
            <h2><?= $translations[$lang]['twofa_heading'] ?></h2>
            <form method="post">
                <label><input type="checkbox" name="enable_2fa"> <?= $translations[$lang]['enable_2fa'] ?></label><br>
                <button type="submit" class="glow-btn"><?= $translations[$lang]['save_button'] ?></button>
            </form>

            <?php if (!empty($_SESSION['twofa_code'])): ?>
                <form method="post" style="margin-top:20px;">
                    <label>Enter Verification Code:</label><br>
                    <input type="text" name="verify_code" required>
                    <button type="submit" class="glow-btn">Verify</button>
                </form>
            <?php endif; ?>
        </section>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
