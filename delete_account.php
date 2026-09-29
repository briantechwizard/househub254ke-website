<?php include('db.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Account - HouseHubKe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('navbar.php'); ?>
<main>
    <section class="form-section">
        <h2>Delete Account</h2>
        <p style="color:red;">Warning: This action cannot be undone.</p>
        <form method="post">
            <button type="submit" class="glow-btn" style="background:red;">Delete My Account</button>
        </form>
    </section>
</main>
<?php include('footer.php'); ?>
</body>
</html>
