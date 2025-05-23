document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');
    const resetButton = document.getElementById('resetButton');
    
    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
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
