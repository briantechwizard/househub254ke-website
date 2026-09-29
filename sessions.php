<?php
include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

// Detect device info
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

// Simple parsing for browser/OS
function getBrowser($userAgent) {
    if (strpos($userAgent, 'Firefox') !== false) return "Firefox";
    elseif (strpos($userAgent, 'Chrome') !== false) return "Chrome";
    elseif (strpos($userAgent, 'Safari') !== false) return "Safari";
    elseif (strpos($userAgent, 'Edge') !== false) return "Edge";
    else return "Unknown Browser";
}

function getOS($userAgent) {
    if (strpos($userAgent, 'Windows') !== false) return "Windows";
    elseif (strpos($userAgent, 'Mac') !== false) return "MacOS";
    elseif (strpos($userAgent, 'Linux') !== false) return "Linux";
    elseif (strpos($userAgent, 'Android') !== false) return "Android";
    elseif (strpos($userAgent, 'iPhone') !== false) return "iOS";
    else return "Unknown OS";
}

$browser = getBrowser($userAgent);
$os = getOS($userAgent);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Manage Sessions - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .session-card {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #FFD700;
            border-radius: 10px;
            background: rgba(255,255,255,0.9);
            color: black; /* ✅ Force text to black */
        }
        .session-card h3 {
            margin-top: 0;
            text-shadow: 0 0 5px #FFD700;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>
<main>
    <section class="form-section">
        <h2 style="color:black;">Active Sessions</h2>
        <p style="color:black;">List of devices logged into your account.</p>

        <div class="session-card">
            <h3>Current Device</h3>
            <p><strong>Browser:</strong> <?= htmlspecialchars($browser) ?></p>
            <p><strong>Operating System:</strong> <?= htmlspecialchars($os) ?></p>
            <p><strong>User Agent:</strong> <?= htmlspecialchars($userAgent) ?></p>
        </div>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
