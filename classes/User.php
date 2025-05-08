<?php
class User {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function register($name, $phone, $email, $password) {
        // Validate user input
        if ($this->isEmailExists($email)) {
            return "Email already exists.";
        }
        if ($this->isPhoneExists($phone)) {
            return "Phone number already exists.";
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL statement
        $stmt = $this->db->prepare("INSERT INTO users (name, phone, email, password, type) VALUES (?, ?, ?, ?, 'user')");
        $stmt->bind_param("ssss", $name, $phone, $email, $hashedPassword);
        return $stmt->execute() ? "Registration successful." : "Registration failed.";
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($hashedPassword);
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                return "Login successful.";
            } else {
                return "Invalid password.";
            }
        } else {
            return "Email not found.";
        }
    }

    public function logout() {
        // Destroy the session to log out the user
        session_start();
        session_destroy();
        return "Logout successful.";
    }

    private function isEmailExists($email) {
        $stmt = $this->db->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function isPhoneExists($phone) {
        $stmt = $this->db->prepare("SELECT phone FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
?>