<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/Exception.php';
require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/PHPMailer.php';
require 'C:/xampp/htdocs/househub/PHPMailer-7.1.1/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userEmail = $_POST['email'] ?? '';
    $feedback  = $_POST['feedback'] ?? '';

    if (!empty($userEmail) && !empty($feedback)) {
        // 1. Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'househubke254@gmail.com'; // your site Gmail
            $mail->Password = 'ucfj btvy ysga duqc';       // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('househubke254@gmail.com', 'HouseHubKe Feedback');
            $mail->addAddress('househubke254@gmail.com'); // site inbox
            $mail->addReplyTo($userEmail, 'Feedback User'); // reply directly to sender
            $mail->Subject = 'New Feedback Submission';
            $mail->Body    = "Feedback from: $userEmail\n\nMessage:\n$feedback";

            $mail->send();

            // 2. Save to database
            $stmt = $conn->prepare("INSERT INTO feedback_messages (email, message) VALUES (?, ?)");
            $stmt->bind_param("ss", $userEmail, $feedback);
            $stmt->execute();
            $stmt->close();

            echo "<p style='color:green;text-align:center;'>Thank you for your feedback! It has been emailed and saved successfully.</p>";
        } catch (Exception $e) {
            echo "<p style='color:red;text-align:center;'>Failed to send feedback: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>Please enter both your email and feedback message.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Feedback - HouseHubKe</title>
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
        .form-section input, .form-section textarea {
            background: #111;
            color: #fff;
            border: 1px solid #FFD700;
            border-radius: 6px;
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }
        .form-section input:focus, .form-section textarea:focus {
            outline: none;
            box-shadow: 0 0 10px #FFD700;
        }
        ::placeholder { color: #ccc; font-style: italic; }
        .form-section label { color: #FFD700; font-weight: bold; margin-top: 10px; display: block; }
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
<main>
    <section class="form-section">
        <h2>Send Feedback</h2>
        <form method="post">
            <label>Your Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            
            <label>Your Feedback</label>
            <textarea name="feedback" rows="5" placeholder="Enter your feedback message..." required></textarea>
            
            <button type="submit" class="glow-btn">Submit</button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
