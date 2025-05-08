<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once '../config/config.php';

// Fetch user data
$user = null;
if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found.";
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
    <title>Edit User</title>
</head>
<body>
    <nav>
        <a href="admin.php">Admin Dashboard</a> |
        <a href="add_location.php">Add Location</a> |
        <a href="dashboard.php">User Dashboard</a> |
        <a href="logout.php">Logout</a>
    </nav>
    <h1>Edit User</h1>
    <form action="../controllers/UserController.php?action=edit&id=<?= $user['user_id'] ?>" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        <br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <br>
        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="user" <?= $user['type'] === 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['type'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <br>
        <input type="submit" value="Update User">
    </form>
</body>
</html>