<?php    
session_start();
include 'db_connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input fields
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION['error'] = "Please enter both email and password!";
        header("Location: login.php");
        exit();
    }

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: login.php");
        exit();
    }

    // Check if database connection is valid
    if (!$conn) {
        $_SESSION['error'] = "Database connection failed!";
        header("Location: login.php");
        exit();
    }

    // Prepare SQL query
    $sql = "SELECT id, full_name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['error'] = "Database query failed: " . $conn->error;
        header("Location: login.php");
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); // Use this for compatibility with older MySQLi versions

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $full_name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $full_name;

            // Check if the logged-in user is the admin
            if ($email === "admin.ereaders@gmail.com") {
                // Redirect to admin page
                header("Location: admin.php");
            } else {
                // Redirect to regular user dashboard
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Incorrect password!";
        }
    } else {
        $_SESSION['error'] = "Email not found!";
    }

    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}
