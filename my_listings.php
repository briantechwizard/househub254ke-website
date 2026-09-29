<?php
include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

// Handle clear all request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_all'])) {
    $stmt = $conn->prepare("DELETE FROM houses WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:red;text-align:center;'>All your listings have been cleared.</p>";
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['my_listings_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .house-card {
            width: 70%;
            margin: 20px auto;
            padding: 20px;
            border: 2px solid #FFD700;
            border-radius: 10px;
            background: rgba(255,255,255,0.9);
        }
        .house-card h3 {
            margin-top: 0;
            text-shadow: 0 0 5px #FFD700;
        }
        .house-card img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
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
        .clear-btn {
            display: block;
            margin: 30px auto;
            padding: 10px 20px;
            background: red;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>
<main>
    <section class="form-section">
        <h2><?= $translations[$lang]['my_listings_heading'] ?></h2>
        <p><?= $translations[$lang]['my_listings_intro'] ?></p>

        <?php
        $stmt = $conn->prepare("SELECT * FROM houses WHERE user_id=? ORDER BY id DESC");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='house-card'>";
                echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                echo "<p><strong>".$translations[$lang]['price'].":</strong> KSh " . htmlspecialchars($row['price']) . "</p>";
                echo "<p><strong>".$translations[$lang]['location'].":</strong> " . htmlspecialchars($row['location']) . "</p>";
                echo "<p><strong>".$translations[$lang]['phone'].":</strong> " . htmlspecialchars($row['phone']) . "</p>";
                if (!empty($row['image'])) {
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='".$translations[$lang]['house_image']."'>";
                }
                echo "<div class='actions'>
                        <a href='details.php?id=" . $row['id'] . "'>".$translations[$lang]['view_details']."</a>
                        <a href='post.php?edit=" . $row['id'] . "'>".$translations[$lang]['edit']."</a>
                        <a href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('".$translations[$lang]['delete_confirm']."');\">".$translations[$lang]['delete']."</a>
                      </div>";
                echo "</div>";
            }
        } else {
            echo "<p style='text-align:center; margin-top:50px;'>".$translations[$lang]['no_houses']."</p>";
        }
        $stmt->close();
        ?>

        <!-- Clear All Button -->
        <form method="post" onsubmit="return confirm('Are you sure you want to clear all your listings?');">
            <button type="submit" name="clear_all" class="clear-btn">Clear All Listings</button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
