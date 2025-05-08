<?php
// Database configuration settings
$host = 'localhost'; // Database host
$username = 'root'; // Database username
$password = ''; // Database password
$database = 'easy_ev_charging'; // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>