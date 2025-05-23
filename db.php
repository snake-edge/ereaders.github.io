<?php
// Database connection settings
$host = "localhost";      // Your database host (usually localhost)
$user = "root";           // Your database username
$pass = "";               // Your database password (empty if not set)
$db = "library";          // The database you want to connect to

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
