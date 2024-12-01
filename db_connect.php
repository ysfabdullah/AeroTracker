<?php
$host = 'localhost';  // Server name
$username = 'root';   // Default username for XAMPP
$password = '';       // Leave blank for XAMPP
$database = 'aerotracker'; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
