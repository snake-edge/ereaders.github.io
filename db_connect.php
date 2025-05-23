<?php  
$servername = "localhost"; // XAMPP default server
$username = "root"; // Default username in XAMPP
$password = ""; // Default password is empty
$database = "library"; // Your database name

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>