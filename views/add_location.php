<!DOCTYPE html>
<html>
<head>
    <title>Add Location</title>
</head>
<body>
    <nav>
        <a href="admin.php">Admin Dashboard</a> |
        <a href="add_location.php">Add Location</a> |
        <a href="dashboard.php">User Dashboard</a> |
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Add a New Location</h1>
    <form action="../controllers/LocationController.php?action=add" method="post">
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" required>
        <br>
        <label for="number_of_stations">Number of Stations:</label>
        <input type="number" id="number_of_stations" name="number_of_stations" min="1" required>
        <br>
        <label for="cost_per_hour">Cost per Hour ($):</label>
        <input type="number" id="cost_per_hour" name="cost_per_hour" min="0" step="0.01" required>
        <br>
        <input type="submit" value="Add Location">
    </form>
</body>
</html>