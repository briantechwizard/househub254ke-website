<?php
// Include database connection
include('db.php');
session_start(); // ensure session is started to access $_SESSION['user_id']

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    // Fetch house record including file names
    $sql = "SELECT user_id, image, video FROM houses WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $house = $result->fetch_assoc();

    if (!$house) {
        die("House not found.");
    }

    // Only allow deletion if current user owns the house
    if ($house['user_id'] != $_SESSION['user_id']) {
        die("Unauthorized action.");
    }

    // ✅ Absolute path to uploads folder
    $uploadDir = realpath(__DIR__ . "/uploads") . DIRECTORY_SEPARATOR;

    // Delete images
    if (!empty($house['image'])) {
        $images = explode(',', $house['image']); // handle multiple images
        foreach ($images as $img) {
            $filePath = $uploadDir . basename(trim($img));
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }

    // Delete video
    if (!empty($house['video'])) {
        $videoPath = $uploadDir . basename(trim($house['video']));
        if (file_exists($videoPath)) {
            unlink($videoPath);
        }
    }

    // Delete record from DB
    $sql = "DELETE FROM houses WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: browse.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "No ID provided.";
}
?>
