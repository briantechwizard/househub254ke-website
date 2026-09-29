<?php
include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

$toastMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['notify_email']) ? 1 : 0;
    $sms = isset($_POST['notify_sms']) ? 1 : 0;
    $whatsapp = isset($_POST['notify_whatsapp']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET notify_email=?, notify_sms=?, notify_whatsapp=? WHERE id=?");
    $stmt->bind_param("iiii", $email, $sms, $whatsapp, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();

    // Build toast message with chosen preferences
    $chosen = [];
    if ($email) $chosen[] = $translations[$lang]['email_notifications'];
    if ($sms) $chosen[] = $translations[$lang]['sms_notifications'];
    if ($whatsapp) $chosen[] = $translations[$lang]['whatsapp_notifications'];

    if (!empty($chosen)) {
        $toastMessage = $translations[$lang]['notification_updated'] . " " .
                        implode(", ", $chosen);
    } else {
        $toastMessage = $translations[$lang]['notification_updated'];
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['notification_settings'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .notification-container label {
            color: #fff;
            font-weight: bold;
            display: block;
            margin: 12px 0;
        }
        .notification-container input[type="checkbox"] {
            accent-color: #FFD700;
            margin-right: 8px;
        }
        /* Toast styling */
        #toast {
            visibility: hidden;
            min-width: 250px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 12px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            transform: translateX(-50%);
            font-size: 16px;
        }
        #toast.show {
            visibility: visible;
            animation: fadein 0.5s, fadeout 0.5s 3s;
        }
        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }
        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <section class="form-section notification-container">
            <h2><?= $translations[$lang]['notification_preferences'] ?></h2>
            <form method="post">
                <label><input type="checkbox" name="notify_email"> <?= $translations[$lang]['email_notifications'] ?></label>
                <label><input type="checkbox" name="notify_sms"> <?= $translations[$lang]['sms_notifications'] ?></label>
                <label><input type="checkbox" name="notify_whatsapp"> <?= $translations[$lang]['whatsapp_notifications'] ?></label>
                <button type="submit" class="glow-btn"><?= $translations[$lang]['save_preferences'] ?></button>
            </form>
        </section>
    </main>
    <?php include('footer.php'); ?>

    <!-- Toast element -->
    <div id="toast"><?= htmlspecialchars($toastMessage) ?></div>

    <script>
        window.onload = function() {
            var toast = document.getElementById("toast");
            if (toast.textContent.trim() !== "") {
                toast.className = "show";
                setTimeout(function(){ toast.className = toast.className.replace("show", ""); }, 3500);
            }
        };
    </script>
</body>
</html>
