<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['code'];

    if (isset($_SESSION['reset_code']) && $code == $_SESSION['reset_code']) {
        header("Location: reset_password.php");
        exit();
    } else {
        echo "<p style='color:red;text-align:center;'>Invalid code. Try again.</p>";
        echo "<p style='text-align:center;'><a href='verify_code.php'>Back</a></p>";
    }
}
?>
