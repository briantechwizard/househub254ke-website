<?php
include('db.php');
include('lang.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ✅ Block unlogged users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$lang = $_SESSION['language'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['browse'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-bar {
            width: 70%;
            margin: 30px auto;
            text-align: center;
        }
        .search-bar input {
            width: 60%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .search-bar button {
            padding: 10px 20px;
            margin-left: 10px;
            background: #FFD700;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
        /* Container for house cards */
        .house-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px auto;
            width: 90%;
        }
        .house-card {
            flex: 0 0 calc(33.333% - 40px); /* 3 per row */
            box-sizing: border-box;
            padding: 20px;
            border: 2px solid #FFD700;
            border-radius: 10px;
            background: rgba(255,255,255,0.9);
            box-shadow: 0 0 15px rgba(255,215,0,0.3);
        }
        .house-card h3 {
            margin-top: 0;
            text-shadow: 0 0 5px #FFD700;
            color: #000;
        }
        .house-card p {
            color: #000;
        }
        .house-card img {
            width: 100px;              /* fixed thumbnail size */
            height: 100px;
            object-fit: cover;         /* crop neatly */
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #FFD700;
            cursor: pointer;           /* show clickable cursor */
            display: block;
        }
        .actions a {
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
            color: #FFD700;
        }
        .actions a:hover {
            color: black;
        }
        /* Fullscreen overlay for zoomed image */
        .image-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .image-overlay img {
            max-width: 90%;
            max-height: 90%;
            border: 3px solid #FFD700;
            border-radius: 8px;
        }
        /* Close button */
        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 32px;
            font-weight: bold;
            color: #FFD700;
            cursor: pointer;
            z-index: 10000;
        }
        .close-btn:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include('navbar.php'); ?>

    <!-- Main content -->
    <main>
        <div class="search-bar">
            <form method="get">
                <input type="text" name="search" placeholder="<?= $translations[$lang]['search_placeholder'] ?>">
                <button type="submit"><?= $translations[$lang]['search_button'] ?></button>
            </form>
        </div>

        <?php
        // Handle search if provided
        $search = "";
        if (isset($_GET['search']) && $_GET['search'] !== "") {
            $search = $conn->real_escape_string($_GET['search']);
            $sql = "SELECT * FROM houses 
                    WHERE price LIKE '%$search%' 
                       OR location LIKE '%$search%' 
                       OR title LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM houses ORDER BY id DESC";
        }

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            echo "<div class='house-container'>"; // ✅ wrapper for flex layout
            while($row = $result->fetch_assoc()) {
                echo "<div class='house-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p><strong>".$translations[$lang]['price'].":</strong> KSh " . htmlspecialchars($row['price']) . "</p>";
                echo "<p><strong>".$translations[$lang]['location'].":</strong> " . htmlspecialchars($row['location']) . "</p>";
                echo "<p><strong>".$translations[$lang]['phone'].":</strong> " . htmlspecialchars($row['phone']) . " 
                       <a href='https://wa.me/254" . htmlspecialchars($row['phone']) . "'>WhatsApp</a></p>";

                // ✅ Show only the first image if multiple are stored
                if (!empty($row['image'])) {
                    $firstImage = $row['image'];
                    if (strpos($row['image'], ',') !== false) {
                        $images = explode(',', $row['image']);
                        $firstImage = trim($images[0]);
                    }
                    echo "<img src='" . htmlspecialchars($firstImage) . "' alt='House Image' onclick=\"openImage(this.src)\">";
                }

                // ✅ Actions block preserved
                echo "<div class='actions'>";
                echo "<a href='details.php?id=" . $row['id'] . "'>".$translations[$lang]['view_details']."</a>";
                if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
                    echo "<a href='post.php?edit=" . $row['id'] . "'>".$translations[$lang]['edit']."</a>";
                    echo "<a href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('".$translations[$lang]['delete_confirm']."');\">".$translations[$lang]['delete']."</a>";
                }
                echo "</div>";

                echo "</div>";
            }
            echo "</div>"; // ✅ close wrapper
        } else {
            echo "<p style='text-align:center; margin-top:50px;'>".$translations[$lang]['no_houses']."</p>";
        }
        ?>
    </main>

    <!-- Fullscreen overlay -->
    <div class="image-overlay" id="imageOverlay">
        <span class="close-btn" onclick="closeImage()">×</span>
        <img id="overlayImg" src="" alt="Zoomed Image">
    </div>

    <script>
    function openImage(src) {
        document.getElementById('overlayImg').src = src;
        document.getElementById('imageOverlay').style.display = 'flex';
    }
    function closeImage() {
        document.getElementById('imageOverlay').style.display = 'none';
    }
    </script>

    <!-- Footer -->
    <?php include('footer.php'); ?>
</body>
</html>
