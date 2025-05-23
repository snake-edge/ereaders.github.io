<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - E-Readers Library</title>
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
                <h2>Reset Your Password</h2>
                <p>Enter your email to receive a password reset link.</p>
            </div>

            <div id="error-message">
                <?php if (isset($_SESSION['error'])): ?>
                    <p class="error"> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?> </p>
                <?php endif; ?>
            </div>
            
            <form id="forgotPasswordForm" class="auth-form" action="process_forgot_password.php" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="your@email.com" required autocomplete="email">
                    <span id="emailError" class="error-message"></span>
                </div>
                
                <button type="submit" id="resetButton" class="btn btn-primary btn-block">Send Reset Link</button>
            </form>
            
            <div class="auth-footer">
                <p>Remembered your password? <a href="login.php">Sign in</a></p>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forgotPasswordForm = document.getElementById('forgotPasswordForm');
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const resetButton = document.getElementById('resetButton');
            
            function validateEmail(email) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }
            
            emailInput.addEventListener('blur', function() {
                if (!emailInput.value) {
                    emailError.textContent = 'Email is required';
                    emailInput.classList.add('error');
                } else if (!validateEmail(emailInput.value)) {
                    emailError.textContent = 'Please enter a valid email';
                    emailInput.classList.add('error');
                } else {
                    emailError.textContent = '';
                    emailInput.classList.remove('error');
                }
            });
            
            forgotPasswordForm.addEventListener('submit', function(e) {
                if (!emailInput.value || !validateEmail(emailInput.value)) {
                    e.preventDefault();
                    emailError.textContent = 'Please enter a valid email';
                    emailInput.classList.add('error');
                } else {
                    resetButton.textContent = 'Sending...';
                    resetButton.disabled = true;
                }
            });
        });
    </script>
</body>
</html>
