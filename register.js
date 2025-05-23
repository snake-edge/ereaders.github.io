document.addEventListener('DOMContentLoaded', function() { 
    // Mobile menu toggle
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileNav = document.querySelector('.mobile-nav');

    mobileMenuBtn.addEventListener('click', function() {
        mobileNav.classList.toggle('active');
    });

    // Set current year in footer
    const yearElement = document.getElementById('current-year');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear().toString();
    }

    // Password visibility toggle for both password and confirm password fields
    function setupPasswordToggle(passwordFieldId, toggleIconId) {
        const passwordInput = document.getElementById(passwordFieldId);
        const passwordToggleIcon = document.getElementById(toggleIconId);

        if (passwordInput && passwordToggleIcon) {
            passwordInput.addEventListener('input', function() {
                passwordToggleIcon.style.display = this.value.length > 0 ? 'inline' : 'none';
            });

            passwordToggleIcon.addEventListener('click', function() {
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
        }
    }

    setupPasswordToggle('password', 'passwordToggleIcon');
    setupPasswordToggle('confirmPassword', 'confirmPasswordToggleIcon');

    // Form validation
    const registerForm = document.getElementById('registerForm');
    const fullNameInput = document.getElementById('fullName');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('terms');
    const registerButton = document.getElementById('registerButton');

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function validatePassword(password) {
        return password.length >= 8 && /[A-Z]/.test(password) && /\d/.test(password);
    }

    function showError(inputElement, errorMessage) {
        const errorElement = document.getElementById(inputElement.id + 'Error');
        if (errorElement) {
            errorElement.textContent = errorMessage;
            inputElement.classList.add('error');
        }
    }

    function clearError(inputElement) {
        const errorElement = document.getElementById(inputElement.id + 'Error');
        if (errorElement) {
            errorElement.textContent = '';
            inputElement.classList.remove('error');
        }
    }

    fullNameInput.addEventListener('blur', function() {
        fullNameInput.value.trim() ? clearError(fullNameInput) : showError(fullNameInput, 'Full Name is required');
    });

    emailInput.addEventListener('blur', function() {
        if (!emailInput.value.trim()) {
            showError(emailInput, 'Email is required');
        } else if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Please enter a valid email');
        } else {
            clearError(emailInput);
        }
    });

    passwordInput.addEventListener('blur', function() {
        if (!passwordInput.value) {
            showError(passwordInput, 'Password is required');
        } else if (!validatePassword(passwordInput.value)) {
            showError(passwordInput, 'Password must be at least 8 characters long, contain an uppercase letter and a number');
        } else {
            clearError(passwordInput);
        }
    });

    confirmPasswordInput.addEventListener('blur', function() {
        if (!confirmPasswordInput.value) {
            showError(confirmPasswordInput, 'Please confirm your password');
        } else if (confirmPasswordInput.value !== passwordInput.value) {
            showError(confirmPasswordInput, 'Passwords do not match');
        } else {
            clearError(confirmPasswordInput);
        }
    });

    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();

        let hasError = false;
        clearError(fullNameInput);
        clearError(emailInput);
        clearError(passwordInput);
        clearError(confirmPasswordInput);

        if (!fullNameInput.value.trim()) {
            showError(fullNameInput, 'Full Name is required');
            hasError = true;
        }

        if (!emailInput.value.trim()) {
            showError(emailInput, 'Email is required');
            hasError = true;
        } else if (!validateEmail(emailInput.value)) {
            showError(emailInput, 'Please enter a valid email');
            hasError = true;
        }

        if (!passwordInput.value) {
            showError(passwordInput, 'Password is required');
            hasError = true;
        } else if (!validatePassword(passwordInput.value)) {
            showError(passwordInput, 'Password must be at least 8 characters long, contain an uppercase letter and a number');
            hasError = true;
        }

        if (!confirmPasswordInput.value) {
            showError(confirmPasswordInput, 'Please confirm your password');
            hasError = true;
        } else if (confirmPasswordInput.value !== passwordInput.value) {
            showError(confirmPasswordInput, 'Passwords do not match');
            hasError = true;
        }

        if (!termsCheckbox.checked) {
            showError(termsCheckbox, 'You must agree to the terms and conditions');
            hasError = true;
        } else {
            clearError(termsCheckbox);
        }

        if (!hasError) {
            registerButton.textContent = 'Creating Account...';
            registerButton.disabled = true;

            setTimeout(() => {
                alert('Registration successful!');
                registerButton.textContent = 'Create Account';
                registerButton.disabled = false;
                registerForm.submit();
            }, 1500);
        }
    });
});