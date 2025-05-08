<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Location</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Delete Location</h1>
    </header>
    <nav>
        <a href="admin.php">Admin Dashboard</a> |
        <a href="add_location.php">Add Location</a> |
        <a href="dashboard.php">User Dashboard</a> |
        <a href="logout.php">Logout</a>
    </nav>
    <main>
        <form action="../controllers/LocationController.php?action=delete" method="post">
            <label for="location_id">Location ID:</label>
            <input type="number" id="location_id" name="location_id" value="<?= isset($_GET['id']) ? intval($_GET['id']) : '' ?>" required readonly>
            <br>
            <input type="submit" value="Delete Location" onclick="return confirm('Are you sure you want to delete this location?');">
        </form>
    </main>
    <footer>
        <p>&copy; 2023 Your Company</p>
    </footer>
</body>
</html>