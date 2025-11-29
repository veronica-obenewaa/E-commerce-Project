/**
 * Pharmaceutical Company Registration Handler
 * Handles form validation and submission for company registration
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('companyRegisterForm');
    
    if (form) {
        form.addEventListener('submit', handleCompanyRegistration);
    }
});

function handleCompanyRegistration(event) {
    event.preventDefault();
    
    // Validate form
    if (!validateCompanyForm()) {
        return;
    }

    const form = document.getElementById('companyRegisterForm');
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;

    // Disable submit button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    // Prepare form data
    const formData = new FormData(form);

    // Submit via AJAX
    fetch(form.getAttribute('action'), {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showSuccessMessage('Registration successful! Redirecting to login...');
            
            // Redirect to login page after 1.4 seconds
            setTimeout(() => {
                window.location.href = '../Login/login.php';
            }, 1400);
        } else {
            showErrorMessage(data.message || 'Registration failed. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('An error occurred. Please try again.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function validateCompanyForm() {
    const companyName = document.getElementById('company_name').value.trim();
    const pharmaRegNumber = document.getElementById('pharmaceutical_registration_number').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirm_password').value.trim();

    // Clear previous error messages
    clearErrorMessages();

    // Validate company name
    if (!companyName) {
        showFieldError('company_name', 'Company name is required');
        return false;
    }

    if (companyName.length < 3) {
        showFieldError('company_name', 'Company name must be at least 3 characters');
        return false;
    }

    // Validate pharmaceutical registration number
    if (!pharmaRegNumber) {
        showFieldError('pharmaceutical_registration_number', 'Pharmaceutical registration number is required');
        return false;
    }

    // Validate email
    if (!email) {
        showFieldError('email', 'Email is required');
        return false;
    }

    if (!isValidEmail(email)) {
        showFieldError('email', 'Please enter a valid email address');
        return false;
    }

    // Validate password
    if (!password) {
        showFieldError('password', 'Password is required');
        return false;
    }

    if (password.length < 8) {
        showFieldError('password', 'Password must be at least 8 characters');
        return false;
    }

    // Validate password confirmation
    if (!confirmPassword) {
        showFieldError('confirm_password', 'Please confirm your password');
        return false;
    }

    if (password !== confirmPassword) {
        showFieldError('confirm_password', 'Passwords do not match');
        return false;
    }

    return true;
}

function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }
}

function clearErrorMessages() {
    const errorFields = document.querySelectorAll('.is-invalid');
    errorFields.forEach(field => {
        field.classList.remove('is-invalid');
        const errorMsg = field.parentNode.querySelector('.invalid-feedback');
        if (errorMsg) {
            errorMsg.remove();
        }
    });
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const form = document.getElementById('companyRegisterForm');
    form.parentNode.insertBefore(alertDiv, form);
}

function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const form = document.getElementById('companyRegisterForm');
    form.parentNode.insertBefore(alertDiv, form);
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}
