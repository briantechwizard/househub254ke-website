<?php
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';
?>

<footer>
    <div class="footer-container">
        <!-- About Us -->
        <div class="footer-column">
            <h3><?= $translations[$lang]['about_us'] ?></h3>
            <p><?= $translations[$lang]['about_text'] ?></p>
        </div>

        <!-- Open Hours -->
        <div class="footer-column">
            <h3><?= $translations[$lang]['open_hours'] ?></h3>
            <p><?= $translations[$lang]['weekday_hours'] ?></p>
            <p><?= $translations[$lang]['saturday_hours'] ?></p>
        </div>

        <!-- Address & Contact -->
        <div class="footer-column">
            <h3><?= $translations[$lang]['contact_us'] ?></h3>
            <p>HouseHubKe HQ</p>
            <p>Nairobi, Kenya</p>
            <p><?= $translations[$lang]['phone'] ?>: <a href="tel:+254115024238">+254 115 024 238</a></p>
            <p><?= $translations[$lang]['email'] ?>: 
                <a href="mailto:househubke254@gmail.com">househubke254@gmail.com</a>
                <a href="https://wa.me/254115024238" class="whatsapp-link">
                    <img src="/househub/images/whatsapp.png" alt="whatsapp" class="whatsapp">

                </a>
            </p>
        </div>

        <!-- Follow Us -->
        <div class="footer-column">
            <h3><?= $translations[$lang]['follow_us'] ?></h3>
            <p>
                <a href="#" class="social-link">
                    <img src="images/instagram.png" alt="Instagram" class="icon"> Instagram
                </a><br>
                <a href="#" class="social-link">
                    <img src="images/facebook.png" alt="Facebook" class="icon"> Facebook
                </a><br>
                <a href="#" class="social-link">
                    <img src="images/tiktok.png" alt="TikTok" class="icon"> TikTok
                </a><br>
            </p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date("Y"); ?> HouseHubKe. <?= $translations[$lang]['rights_reserved'] ?></p>
    </div>
</footer>
