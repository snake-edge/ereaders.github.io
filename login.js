document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileNav = document.querySelector('.mobile-nav');

    mobileMenuBtn.addEventListener('click', function() {
        mobileNav.classList.toggle('active');
    });

    // Set current year in footer
    const yearElement = document.getElementById('current-year');
    yearElement.textContent = new Date().getFullYear().toString();

    // Password visibility toggle
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.querySelector('.toggle-password');
    const passwordToggleIcon = document.getElementById('passwordToggleIcon');

    passwordToggle.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordToggleIcon.classList.remove('fa-eye');
            passwordToggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordToggleIcon.classList.remove('fa-eye-slash');
            passwordToggleIcon.classList.add('fa-eye');
        }
    });

    // Form validation
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const loginButton = document.getElementById('loginButton');

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

    passwordInput.addEventListener('blur', function() {
        if (!passwordInput.value) {
            passwordError.textContent = 'Password is required';
            passwordInput.classList.add('error');
        } else {
            passwordError.textContent = '';
            passwordInput.classList.remove('error');
        }
    });

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset errors
        emailError.textContent = '';
        passwordError.textContent = '';
        emailInput.classList.remove('error');
        passwordInput.classList.remove('error');
        
        let hasError = false;
        
        if (!emailInput.value) {
            emailError.textContent = 'Email is required';
            emailInput.classList.add('error');
            hasError = true;
        } else if (!validateEmail(emailInput.value)) {
            emailError.textContent = 'Please enter a valid email';
            emailInput.classList.add('error');
            hasError = true;
        }
        
        if (!passwordInput.value) {
            passwordError.textContent = 'Password is required';
            passwordInput.classList.add('error');
            hasError = true;
        }
        
        if (!hasError) {
            // Show loading state
            loginButton.textContent = 'Signing in...';
            loginButton.disabled = true;
            
            // Simulate API call
            setTimeout(function() {
                // In a real app, this would be where you send the login data to your server
                console.log('Login attempted with:', {
                    email: emailInput.value,
                    password: passwordInput.value
                });
                
                // For demo purposes, just show a success alert
                alert('Login successful!');
                loginButton.textContent = 'Sign in';
                loginButton.disabled = false;
                
                // Optionally redirect
                // window.location.href = 'index.html';
            }, 1500);
        }
    });
});