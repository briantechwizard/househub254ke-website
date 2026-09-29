<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Adjusted path to match your PHPMailer location
require __DIR__ . '/PHPMailer-7.1.1/src/Exception.php';
require __DIR__ . '/PHPMailer-7.1.1/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-7.1.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate code
        $code = rand(100000, 999999);
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_code'] = $code;

        // Send email with PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'househubke254@gmail.com';
            $mail->Password = 'ucfj btvy ysga duqc'; // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('househubke254@gmail.com', 'HouseHubKe');
            $mail->addAddress($email);

            $mail->Subject = 'HouseHubKe Password Reset Code';
            $mail->Body    = "Your verification code is: $code";

            $mail->send();
            echo "<p style='color:green;text-align:center;'>Verification code sent to your email.</p>";
            echo "<p style='text-align:center;'><a href='verify_code.php'>Go to Verify Code Page</a></p>";
        } catch (Exception $e) {
            echo "<p style='color:red;text-align:center;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p style='color:red;text-align:center;'>No account found with that email.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
