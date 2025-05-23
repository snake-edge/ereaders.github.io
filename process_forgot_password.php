<?php
session_start();
require 'db_connect.php'; // Ensure this file connects to your phpMyAdmin database
require 'email_config.php'; // Configuration for sending emails

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email address';
        header('Location: forgot_password.php');
        exit();
    }

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = 'No account found with that email';
        header('Location: forgot_password.php');
        exit();
    }

    $user = $result->fetch_assoc();
    $token = bin2hex(random_bytes(32)); // Generate secure token
    $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

    // Store token in the database
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, token_expires = ? WHERE id = ?");
    $stmt->bind_param("ssi", $token, $expires, $user['id']);
    $stmt->execute();

    // Create reset link
    $resetLink = "http://localhost/library-system/reset_password.php?token=$token";

    // Send reset email using PHPMailer
    $subject = "Password Reset Request";
    $message = "<p>Click the link below to reset your password:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>This link will expire in 1 hour.</p>";

    if (sendResetEmail($email, $subject, $message)) {
        $_SESSION['success'] = 'Password reset link sent to your email';
    } else {
        $_SESSION['error'] = 'Failed to send email. Please try again later';
    }

    header('Location: forgot_password.php');
    exit();
} else {
    header('Location: forgot_password.php');
    exit();
}
?>
