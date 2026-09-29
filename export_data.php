<?php
include('db.php');
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('lang.php');
$lang = $_SESSION['language'] ?? 'en';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch user data (example: from users and houses tables)
    $userId = $_SESSION['user_id'];

    // Prepare CSV headers
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=user_data.csv');

    $output = fopen('php://output', 'w');

    // Export user info
    fputcsv($output, ['User Info']);
    $stmt = $conn->prepare("SELECT id, full_name, email, phone FROM users WHERE id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        fputcsv($output, array_keys($row)); // column headers
        fputcsv($output, $row);             // values
    }
    $stmt->close();

    // Export listings
    fputcsv($output, []); // blank line
    fputcsv($output, ['Listings']);
    $stmt = $conn->prepare("SELECT id, title, price, location, phone FROM houses WHERE user_id=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        fputcsv($output, ['ID','Title','Price','Location','Phone']);
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, $row);
        }
    } else {
        fputcsv($output, ['No listings found']);
    }
    $stmt->close();

    fclose($output);
    exit; // stop rendering HTML, only download file
}
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $translations[$lang]['export_page'] ?> - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<main>
    <section class="form-section">
        <h2><?= $translations[$lang]['export_heading'] ?></h2>
        <p><?= $translations[$lang]['export_description'] ?></p>
        <form method="post">
            <button type="submit" class="glow-btn"><?= $translations[$lang]['export_button'] ?></button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
