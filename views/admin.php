<?php
session_start();
require_once '../config/config.php';
require_once '../classes/User.php';
require_once '../classes/Location.php';
require_once '../classes/ChargingSession.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$userModel = new User($conn);
$locationModel = new Location($conn);

// Fetch all users
$users = $conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);
// Fetch all locations
$locations = $locationModel->listLocations();
// Fetch all charging sessions
$sessions = $conn->query("SELECT cs.session_id, u.name AS user_name, l.description AS location, cs.check_in_time, cs.check_out_time, cs.total_cost FROM charging_sessions cs JOIN users u ON cs.user_id = u.user_id JOIN locations l ON cs.location_id = l.location_id ORDER BY cs.check_in_time DESC")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="admin.php">Admin Dashboard</a> |
            <a href="add_location.php">Add Location</a> |
            <a href="dashboard.php">User Dashboard</a> |
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Users</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['user_id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['type']) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['user_id'] ?>">Edit</a> |
                                <a href="delete_user.php?id=<?= $user['user_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <section>
            <h2>Charging Locations</h2>
            <a href="add_location.php">Add New Location</a>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Description</th>
                        <th>Stations</th>
                        <th>Cost/Hour</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($locations as $location): ?>
                        <tr>
                            <td><?= htmlspecialchars($location['location_id']) ?></td>
                            <td><?= htmlspecialchars($location['description']) ?></td>
                            <td><?= htmlspecialchars($location['number_of_stations']) ?></td>
                            <td><?= htmlspecialchars($location['cost_per_hour']) ?></td>
                            <td>
                                <a href="edit_location.php?id=<?= $location['location_id'] ?>">Edit</a> |
                                <a href="delete_location.php?id=<?= $location['location_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <section>
            <h2>User Charging Activity</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>User</th>
                        <th>Location</th>
                        <th>Check-In</th>
                        <th>Check-Out</th>
                        <th>Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td><?= htmlspecialchars($session['session_id']) ?></td>
                            <td><?= htmlspecialchars($session['user_name']) ?></td>
                            <td><?= htmlspecialchars($session['location']) ?></td>
                            <td><?= htmlspecialchars($session['check_in_time']) ?></td>
                            <td><?= htmlspecialchars($session['check_out_time']) ?></td>
                            <td><?= htmlspecialchars($session['total_cost']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
