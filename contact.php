<?php
session_start();
include('lang.php');
include('db.php'); // make sure db.php connects to your database
$lang = $_SESSION['language'] ?? 'en';

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/Exception.php';
require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/PHPMailer.php';
require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!empty($name) && !empty($email) && !empty($message)) {
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'househubke254@gmail.com'; // your site Gmail
            $mail->Password = 'ucfj btvy ysga duqc';       // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email content
            $mail->setFrom('househubke254@gmail.com', 'HouseHubKe Contact Form');
            $mail->addAddress('househubke254@gmail.com'); // site inbox
            $mail->addReplyTo($email, $name); // user’s email for replies
            $mail->Subject = 'New Contact Us Message';
            $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

            $mail->send();

            // Save to database
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $message);
            $stmt->execute();
            $stmt->close();

            echo "<p style='color:green;text-align:center;'>Your message has been sent successfully!</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;text-align:center;'>Failed to send message: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>Please fill in all fields.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>HouseHubKe - <?= $translations[$lang]['contact_us_page'] ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-section {
            width: 60%;
            margin: 40px auto;
            padding: 20px;
            background: #1a1a1a;
            border: 2px solid #FFD700;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(255,215,0,0.5);
        }
        .form-section h2 {
            color: #FFD700;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-section label {
            color: #FFD700;
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        .form-section input, .form-section textarea {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #FFD700;
            border-radius: 6px;
            background: #111;
            color: #fff;
            font-size: 14px;
        }
        .form-section input:focus, .form-section textarea:focus {
            outline: none;
            box-shadow: 0 0 10px #FFD700;
        }
        .form-section button {
            background: #FFD700;
            color: #000;
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }
        .form-section button:hover {
            background: #e6c200;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>

    <section class="form-section">
        <h2><?= $translations[$lang]['contact_us_page'] ?></h2>
        <form class="form-box" method="post" autocomplete="off">
            <label for="name"><?= $translations[$lang]['your_name'] ?></label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>

            <label for="email"><?= $translations[$lang]['your_email'] ?></label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="message"><?= $translations[$lang]['your_message'] ?></label>
            <textarea id="message" name="message" rows="5" placeholder="Enter your message..." required></textarea>

            <button type="submit" class="glow-btn"><?= $translations[$lang]['send_message'] ?></button>
        </form>
        
    </section>

    <?php include('footer.php'); ?>
</body>
</html>
