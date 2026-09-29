<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

if (!isset($_GET['id'])) {
    die($translations[$lang]['no_house_selected']);
}
$id = intval($_GET['id']);
$sql = "SELECT * FROM houses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$house = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['house_details_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .details-container {
            width: 70%;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255,255,255,0.9);
            border: 3px solid #FFD700;
            border-radius: 12px;
            color: #000;
        }
        .details-container h2 {
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px #FFD700;
        }
        .details-container p {
            color: #000;
        }
        .media-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        .media-gallery img,
        .media-gallery video {
            width: 250px;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .media-gallery img:hover,
        .media-gallery video:hover {
            transform: scale(1.05);
        }
        .contact {
            margin-top: 20px;
            font-size: 18px;
        }
        .contact a {
            color: #FFD700;
            font-weight: bold;
            text-decoration: none;
        }
        .contact a:hover {
            color: black;
        }
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.9);
            text-align: center;
        }
        .lightbox img {
            max-width: 90%;
            max-height: 80%;
            margin-top: 5%;
        }
        .lightbox .close {
            position: absolute;
            top: 20px; right: 35px;
            color: #fff;
            font-size: 40px;
            cursor: pointer;
        }
        .lightbox .prev, .lightbox .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            color: #fff;
            font-size: 60px;
            padding: 16px;
            user-select: none;
        }
        .lightbox .prev { left: 0; }
        .lightbox .next { right: 0; }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <div class="details-container">
            <h2><?= $translations[$lang]['house_details_heading'] ?></h2>
            <p><strong><?= $translations[$lang]['name'] ?>:</strong> <?= htmlspecialchars($house['name']) ?></p>
            <p><strong><?= $translations[$lang]['type'] ?>:</strong> <?= htmlspecialchars($house['title']) ?></p>
            <p><strong><?= $translations[$lang]['price'] ?>:</strong> KSh <?= htmlspecialchars($house['price']) ?></p>
            <p><strong><?= $translations[$lang]['location'] ?>:</strong> <?= htmlspecialchars($house['location']) ?></p>
            <div class="contact">
                <?php if (!empty($house['phone'])): ?>
                    <p><strong><?= $translations[$lang]['phone'] ?>:</strong> <?= htmlspecialchars($house['phone']) ?>
                       <a href="https://wa.me/254<?= htmlspecialchars($house['phone']) ?>">WhatsApp</a>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Media Gallery -->
            <div class="media-gallery">
                <?php
                $imagesArray = [];
                if (!empty($house['image'])) {
                    $images = explode(',', $house['image']);
                    foreach ($images as $index => $img) {
                        $imgPath = htmlspecialchars(trim($img));
                        if (!empty($imgPath)) {
                            $imagesArray[] = $imgPath;
                            echo "<img src='$imgPath' alt='".$translations[$lang]['house_image']."' onclick='openLightbox($index)'>";
                        }
                    }
                }

                if (!empty($house['video'])) {
                    $videoPath = htmlspecialchars(trim($house['video']));
                    echo "<video controls>
                            <source src='$videoPath' type='video/mp4'>
                            ".$translations[$lang]['video_not_supported']."
                          </video>";
                }
                ?>
            </div>
        </div>
    </main>
    <?php include('footer.php'); ?>

    <!-- Lightbox -->
    <div id="lightbox" class="lightbox">
        <span class="close" onclick="closeLightbox()">&times;</span>
        <img id="lightbox-img" src="" alt="<?= $translations[$lang]['zoomed_image'] ?>">
        <a class="prev" onclick="changeImage(-1)">&#10094;</a>
        <a class="next" onclick="changeImage(1)">&#10095;</a>
    </div>

    <script>
        let currentIndex = 0;
        let images = <?php echo json_encode($imagesArray); ?>;
        function openLightbox(index) {
            currentIndex = index;
            document.getElementById("lightbox").style.display = "block";
            document.getElementById("lightbox-img").src = images[currentIndex];
        }
        function closeLightbox() {
            document.getElementById("lightbox").style.display = "none";
        }
        function changeImage(direction) {
            currentIndex += direction;
            if (currentIndex < 0) currentIndex = images.length - 1;
            if (currentIndex >= images.length) currentIndex = 0;
            document.getElementById("lightbox-img").src = images[currentIndex];
        }
    </script>
</body>
</html>
