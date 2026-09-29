<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    // Later: save to DB or send email
    echo "<p style='color:green;text-align:center;'>Support request sent successfully!</p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Support - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Ensure form fields are readable in dark theme */
        .form-section input,
        .form-section textarea {
            background: #111;       /* dark background */
            color: #fff;            /* white text */
            border: 1px solid #FFD700;
            border-radius: 6px;
            padding: 10px;
        }
        .form-section input:focus,
        .form-section textarea:focus {
            outline: none;
            box-shadow: 0 0 10px #FFD700;
        }
        ::placeholder {
            color: #ccc;            /* light grey placeholder */
            font-style: italic;
        }
        .form-section label {
            color: #FFD700;         /* gold labels */
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>
<main>
    <section class="form-section">
        <h2>Contact Support</h2>
        <form method="post">
            <label>Subject</label>
            <input type="text" name="subject" placeholder="Enter subject" required>

            <label>Message</label>
            <textarea name="message" rows="5" placeholder="Enter your message" required></textarea>

            <button type="submit" class="glow-btn">Send</button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
