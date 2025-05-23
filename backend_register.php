<?php
// Enable error reporting (for development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

// Check if form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validate all fields
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: register.php");
        exit();
    }

    // Hash password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists in the database
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if ($check_email === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $check_email->bind_param("s", $email);
    if (!$check_email->execute()) {
        die("Execute failed: " . $check_email->error);
    }
    
    $check_email->store_result();
    if ($check_email->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: register.php");
        exit();
    }
    $check_email->close();

    // Insert new user into the database
    $insert_user = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    if ($insert_user === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    $insert_user->bind_param("sss", $name, $email, $hashed_password);
    
    if ($insert_user->execute()) {
        $_SESSION['success'] = "Registration successful! You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: register.php");
        exit();
    }
}

$conn->close();
?>