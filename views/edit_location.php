<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once '../config/config.php';

// Fetch location data
$location = null;
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM locations WHERE location_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $location = $result->fetch_assoc();
    } else {
        echo "Location not found.";
        exit();
    }
    $stmt->close();
} else {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Location</title>
</head>
<body>
    <nav>
        <a href="admin.php">Admin Dashboard</a> |
        <a href="add_location.php">Add Location</a> |
        <a href="dashboard.php">User Dashboard</a> |
        <a href="logout.php">Logout</a>
    </nav>
    <h1>Edit Location</h1>
    <form action="../controllers/LocationController.php?action=edit&id=<?= $location['location_id'] ?>" method="post">
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?= htmlspecialchars($location['description']) ?>" required>
        <br>
        <label for="number_of_stations">Number of Stations:</label>
        <input type="number" id="number_of_stations" name="number_of_stations" min="1" value="<?= htmlspecialchars($location['number_of_stations']) ?>" required>
        <br>
        <label for="cost_per_hour">Cost per Hour ($):</label>
        <input type="number" id="cost_per_hour" name="cost_per_hour" min="0" step="0.01" value="<?= htmlspecialchars($location['cost_per_hour']) ?>" required>
        <br>
        <input type="submit" value="Update Location">
    </form>
</body>
</html>