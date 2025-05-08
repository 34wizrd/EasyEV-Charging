<?php
class UserController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function register($name, $phone, $email, $password) {
        // Validate input
        if (empty($name) || empty($phone) || empty($email) || empty($password)) {
            return "All fields are required.";
        }

        // Check if user already exists (by email or phone)
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "User with this email or phone already exists.";
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $this->db->prepare("INSERT INTO users (name, phone, email, password, type) VALUES (?, ?, ?, ?, 'user')");
        $stmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);
        if ($stmt->execute()) {
            return "Registration successful.";
        } else {
            return "Registration failed.";
        }
    }

    public function login($email, $password) {
        // Validate input
        if (empty($email) || empty($password)) {
            return "Email and password are required.";
        }

        // Check if user exists
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return "User not found.";
        }

        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session and set user data
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['type'];
            return "Login successful.";
        } else {
            return "Invalid password.";
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        return "Logout successful.";
    }
}
?>