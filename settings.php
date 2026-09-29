<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>HouseHubKe - <?= $translations[$lang]['settings_page'] ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Grid layout for settings */
        .settings-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 2rem;
            text-align: center;
        }

        .settings-group {
            background: #1e1e1e;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }

        .settings-group h3 {
            margin-bottom: 0.5rem;
            color: #FFD700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .settings-group h3 i {
            font-size: 1.2rem;
        }

        .settings-group ul {
            list-style: none;
            padding: 0;
        }

        .settings-group ul li {
            margin: 0.5rem 0;
        }

        .settings-group ul li a {
            color: #fff;
            text-decoration: none;
        }

        .settings-group ul li a:hover {
            text-decoration: underline;
        }

        /* Logout section centered at bottom */
        .logout-section {
            text-align: center;
            margin-top: 2rem;
        }

        .logout-section form {
            display: inline-block;
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Main content -->
    <main>
        <h2 style="text-align:center; margin-top:1rem;"><?= $translations[$lang]['settings_heading'] ?></h2>

        <section class="settings-section">
            <!-- Account Management -->
            <div class="settings-group">
                <h3><i class="fas fa-user"></i> <?= $translations[$lang]['account'] ?></h3>
                <ul>
                    <li><a href="edit_profile.php"><?= $translations[$lang]['edit_profile'] ?></a></li>
                    <li><a href="change_password.php"><?= $translations[$lang]['change_password'] ?></a></li>
                    <li><a href="delete_account.php"><?= $translations[$lang]['delete_account'] ?></a></li>
                </ul>
            </div>

            <!-- Preferences -->
            <div class="settings-group">
                <h3><i class="fas fa-cog"></i> <?= $translations[$lang]['preferences'] ?></h3>
                <ul>
                    <li><a href="theme.php"><?= $translations[$lang]['theme_selection'] ?></a></li>
                    <li><a href="language.php"><?= $translations[$lang]['language_settings'] ?></a></li>
                    <li><a href="notifications.php"><?= $translations[$lang]['notification_settings'] ?></a></li>
                </ul>
            </div>

            <!-- Listings Management -->
            <div class="settings-group">
                <h3><i class="fas fa-home"></i> <?= $translations[$lang]['listings'] ?></h3>
                <ul>
                    <li><a href="my_listings.php"><?= $translations[$lang]['manage_listings'] ?></a></li>
                </ul>
            </div>

            <!-- Privacy & Security -->
            <div class="settings-group">
                <h3><i class="fas fa-shield-alt"></i> <?= $translations[$lang]['privacy_security'] ?></h3>
                <ul>
                    <li><a href="sessions.php"><?= $translations[$lang]['manage_sessions'] ?></a></li>
                    <li><a href="two_factor.php"><?= $translations[$lang]['enable_2fa'] ?></a></li>
                    <li><a href="visibility.php"><?= $translations[$lang]['contact_visibility'] ?></a></li>
                </ul>
            </div>

            <!-- System Settings -->
            <div class="settings-group">
                <h3><i class="fas fa-database"></i> <?= $translations[$lang]['system'] ?></h3>
                <ul>
                    <li><a href="saved_searches.php"><?= $translations[$lang]['saved_searches'] ?></a></li>
                    <li><a href="export_data.php"><?= $translations[$lang]['export_data'] ?></a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="settings-group">
                <h3><i class="fas fa-life-ring"></i> <?= $translations[$lang]['support'] ?></h3>
                <ul>
                    <li><a href="help.php"><?= $translations[$lang]['help_faqs'] ?></a></li>
                    <li><a href="contact_support.php"><?= $translations[$lang]['contact_support'] ?></a></li>
                    <li><a href="feedback.php"><?= $translations[$lang]['send_feedback'] ?></a></li>
                </ul>
            </div>
        </section>

        <!-- Logout at bottom center -->
        <div class="logout-section">
            <form method="post" action="logout.php">
                <button type="submit" class="glow-btn"><?= $translations[$lang]['logout'] ?></button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <?php include('footer.php'); ?>
</body>
</html>
