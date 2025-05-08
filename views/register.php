<?php
// This file contains the user registration form.

session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to CSS file -->
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form id="registerForm" action="../controllers/UserController.php?action=register" method="POST" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
    <script>
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const phonePattern = /^\d{10,15}$/;
        const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
        let message = '';
        if (name.length < 2) {
            message += 'Name must be at least 2 characters.\n';
        }
        if (!phonePattern.test(phone)) {
            message += 'Phone must be 10-15 digits.\n';
        }
        if (!emailPattern.test(email)) {
            message += 'Invalid email format.\n';
        }
        if (password.length < 6) {
            message += 'Password must be at least 6 characters.\n';
        }
        if (message) {
            alert(message);
            return false;
        }
        return true;
    }
    </script>
</body>
</html>