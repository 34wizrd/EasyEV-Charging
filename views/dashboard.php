<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database configuration
require_once '../config/config.php';
require_once '../controllers/ChargingController.php';

$chargingController = new ChargingController($conn);

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle check-in
if (isset($_GET['checkin']) && isset($_GET['location_id'])) {
    $chargingController->checkIn($user_id, intval($_GET['location_id']));
    header("Location: dashboard.php");
    exit();
}

// Handle check-out
if (isset($_GET['checkout']) && isset($_GET['location_id'])) {
    $chargingController->checkOut($user_id, intval($_GET['location_id']));
    header("Location: dashboard.php");
    exit();
}

// Fetch available charging locations
$query = "SELECT * FROM locations";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$locations = $result->fetch_all(MYSQLI_ASSOC);

// Fetch charging history
$history = $chargingController->getChargingHistory($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <a href="logout.php">Logout</a>
    </header>
    <main>
        <h2>Available Charging Locations</h2>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Number of Stations</th>
                    <th>Cost per Hour</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($location['description']); ?></td>
                        <td><?php echo htmlspecialchars($location['number_of_stations']); ?></td>
                        <td><?php echo htmlspecialchars($location['cost_per_hour']); ?></td>
                        <td>
                            <a href="dashboard.php?checkin=1&location_id=<?php echo $location['location_id']; ?>">Check In</a> |
                            <a href="dashboard.php?checkout=1&location_id=<?php echo $location['location_id']; ?>">Check Out</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h2>Your Charging History</h2>
        <table>
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Location</th>
                    <th>Check-In Time</th>
                    <th>Check-Out Time</th>
                    <th>Total Cost</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($history): ?>
                    <?php foreach ($history as $session): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($session['session_id']); ?></td>
                            <td><?php echo htmlspecialchars($session['description']); ?></td>
                            <td><?php echo htmlspecialchars($session['check_in_time']); ?></td>
                            <td><?php echo htmlspecialchars($session['check_out_time']); ?></td>
                            <td><?php echo htmlspecialchars($session['total_cost']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No charging history found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>
</html>