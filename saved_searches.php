<?php
include('db.php');

// Handle delete single search
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM saved_searches WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:red;text-align:center;'>Search deleted.</p>";
}

// Handle clear all
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['clear_all'])) {
    $stmt = $conn->prepare("DELETE FROM saved_searches WHERE user_id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:red;text-align:center;'>All saved searches cleared.</p>";
}

// Fetch saved searches
$stmt = $conn->prepare("SELECT id, search_name, filters FROM saved_searches WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Saved Searches - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .search-card {
            width: 70%;
            margin: 20px auto;
            padding: 15px;
            border: 2px solid #FFD700;
            border-radius: 8px;
            background: rgba(255,255,255,0.9);
            color: black;
        }
        .search-card h3 {
            margin: 0;
            text-shadow: 0 0 5px #FFD700;
        }
        .actions a {
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
            color: #FFD700;
        }
        .actions a:hover { color: black; }
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
        <h2>Manage Saved Searches</h2>
        <p>Here you can view, run, or delete your saved searches.</p>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="search-card">
                    <h3><?= htmlspecialchars($row['search_name']) ?></h3>
                    <p><strong>Filters:</strong> <?= htmlspecialchars($row['filters']) ?></p>
                    <div class="actions">
                        <a href="browse.php?<?= htmlspecialchars($row['filters']) ?>">Run Search</a>
                        <a href="saved_searches.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this saved search?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No saved searches found.</p>
        <?php endif; ?>
        <?php $stmt->close(); ?>

        <!-- Clear All Button -->
        <form method="post" onsubmit="return confirm('Clear all saved searches?');">
            <button type="submit" name="clear_all" class="clear-btn">Clear All Saved Searches</button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
