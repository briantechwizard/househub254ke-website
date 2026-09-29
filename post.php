<?php
include('db.php');
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

$editMode = false;
$house = null;
$id = null;

// Check if editing
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = intval($_GET['edit']);
    $sql = "SELECT * FROM houses WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $house = $result->fetch_assoc();

    // Security check: only allow editing if current user owns the house
    if ($house && $house['user_id'] != $_SESSION['user_id']) {
        die($translations[$lang]['unauthorized_edit']);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $title    = $_POST['title'];
    $price    = $_POST['price'];
    $phone    = $_POST['phone'];
    $location = $_POST['location'];

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    // Handle multiple image uploads
    $imagePaths = [];
    if (!empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['name'] as $key => $filename) {
            if (!empty($filename)) {
                $uniqueName = time() . "_" . basename($filename);
                $path = $targetDir . $uniqueName;
                if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $path)) {
                    $imagePaths[] = $path;
                }
            }
        }
    }
    $images = !empty($imagePaths) ? implode(',', $imagePaths) : ($house['image'] ?? "");

    // Handle video upload
    $video = $house['video'] ?? "";
    if (!empty($_FILES['videos']['name'][0])) {
        $videoSize = $_FILES['videos']['size'][0];
        if ($videoSize <= 1024 * 1024 * 1024) {
            $uniqueVideo = time() . "_" . basename($_FILES['videos']['name'][0]);
            $videoPath = $targetDir . $uniqueVideo;
            if (move_uploaded_file($_FILES['videos']['tmp_name'][0], $videoPath)) {
                $video = $videoPath;
            }
        } else {
            echo "<p style='color:red;text-align:center;'>".$translations[$lang]['video_error']."</p>";
            exit();
        }
    }

    if ($editMode) {
        // UPDATE existing record
        $sql = "UPDATE houses SET name=?, title=?, price=?, phone=?, location=?, image=?, video=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssi", $name, $title, $price, $phone, $location, $images, $video, $id);
    } else {
        // INSERT new record
        $userId = $_SESSION['user_id'];
        $sql = "INSERT INTO houses (name, title, price, phone, location, image, video, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissssi", $name, $title, $price, $phone, $location, $images, $video, $userId);
    }

    if ($stmt->execute()) {
        header("Location: browse.php?msg=success");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $editMode ? $translations[$lang]['edit_house'] : $translations[$lang]['post_house'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            width: 70%;
            margin: 40px auto;
            padding: 30px;
            background: rgba(255,255,255,0.9);
            border: 3px solid #FFD700;
            border-radius: 12px;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 0 0 10px #FFD700;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 25px;
            padding: 12px 25px;
            background: #FFD700;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: black;
            color: #FFD700;
            box-shadow: 0 0 15px #FFD700;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main>
        <div class="form-container">
            <h2><?= $editMode ? $translations[$lang]['edit_listing'] : $translations[$lang]['post_listing'] ?></h2>
            <form action="post.php<?= $editMode ? '?edit='.$id : '' ?>" method="post" enctype="multipart/form-data">
                <label for="name"><?= $translations[$lang]['poster_name'] ?>:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($house['name'] ?? '') ?>" required>

                <label for="title"><?= $translations[$lang]['house_type'] ?>:</label>
                <select id="title" name="title" required>
                    <option value=""><?= $translations[$lang]['select_type'] ?></option>
                    <option <?= ($house['title'] ?? '')=='Bedsitter'?'selected':'' ?>>Bedsitter</option>
                    <option <?= ($house['title'] ?? '')=='1 Bedroom'?'selected':'' ?>>1 Bedroom</option>
                    <option <?= ($house['title'] ?? '')=='2 Bedroom'?'selected':'' ?>>2 Bedroom</option>
                    <option <?= ($house['title'] ?? '')=='3 Bedroom'?'selected':'' ?>>3 Bedroom</option>
                    <option <?= ($house['title'] ?? '')=='4 Bedroom'?'selected':'' ?>>4 Bedroom</option>
                    <option <?= ($house['title'] ?? '')=='5 Bedroom'?'selected':'' ?>>5 Bedroom</option>
                    <option <?= ($house['title'] ?? '')=='Mansion'?'selected':'' ?>>Mansion</option>
                </select>
                <label for="price"><?= $translations[$lang]['price'] ?> (KSh):</label>
                <input type="text" id="price" name="price" value="<?= htmlspecialchars($house['price'] ?? '') ?>" required>

                <label for="phone"><?= $translations[$lang]['phone_number'] ?>:</label>
                <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($house['phone'] ?? '') ?>" required>

                <label for="location"><?= $translations[$lang]['location'] ?>:</label>
                <input type="text" id="location" name="location" value="<?= htmlspecialchars($house['location'] ?? '') ?>" required>

                <label for="photos"><?= $translations[$lang]['upload_photos'] ?>:</label>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                <?php if ($editMode && !empty($house['image'])): ?>
                    <div class="preview-gallery">
                        <?php
                        $images = explode(',', $house['image']);
                        foreach ($images as $img) {
                            $imgPath = htmlspecialchars(trim($img));
                            if (!empty($imgPath)) {
                                echo "<img src='$imgPath' alt='".$translations[$lang]['house_image']."'>";
                            }
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <label for="videos"><?= $translations[$lang]['upload_video'] ?> (max 1GB):</label>
                <input type="file" id="videos" name="videos[]" accept="video/mp4,video/webm">
                <?php if ($editMode && !empty($house['video'])): ?>
                    <div class="preview-video">
                        <video controls>
                            <source src="<?= htmlspecialchars($house['video']) ?>" type="video/mp4">
                            <?= $translations[$lang]['video_not_supported'] ?>
                        </video>
                    </div>
                <?php endif; ?>

                <button type="submit"><?= $editMode ? $translations[$lang]['update_house'] : $translations[$lang]['post_house_btn'] ?></button>
            </form>
        </div>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>
