<?php
session_start();
require 'db_connect.php'; // Include database connection

if (!isset($_GET['token'])) {
    $_SESSION['error'] = "Invalid password reset request.";
    header("Location: login.php");
    exit();
}

$token = $_GET['token'];
$current_time = time();

// Fetch user based on the token from the users table
$stmt = $conn->prepare("SELECT email, reset_token, token_expires FROM users WHERE reset_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = "Invalid or expired reset token.";
    header("Location: forgot_password.php");
    exit();
}

$row = $result->fetch_assoc();
$email = $row['email'];
$expires_at = strtotime($row['token_expires']); // Assuming token_expires is stored as a DATETIME string

// Check if the token is expired
if ($current_time > $expires_at) {
    $_SESSION['error'] = "Your password reset link has expired.";
    header("Location: forgot_password.php");
    exit();
}

// Handle form submission for password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password
    if (strlen($new_password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters.";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
    } else {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expires = NULL WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Your password has been reset. You can now log in.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - E-Readers Library</title>
    <link rel="stylesheet" href="shared.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <div class="logo">E-Readers</div>
        <nav class="desktop-nav">
            <a href="index.php">Home</a>
            <a href="index.php#about">About Us</a>
            <a href="index.php#contact">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        </nav>
    </header>

    <main class="login-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Set New Password</h2>
                <p>Enter your new password below.</p>
            </div>

            <div id="error-message">
                <?php if (isset($_SESSION['error'])): ?>
                    <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
                <?php endif; ?>
            </div>

            <form class="auth-form" action="" method="POST">
                <div class="form-group">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" id="new_password" class="form-input" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>

            <div class="auth-footer">
                <p>Remembered your password? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </main>
</body>
</html>
