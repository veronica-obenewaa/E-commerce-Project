document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (validateForm()) {
                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registering...';
                submitBtn.disabled = true;

                const formData = new FormData(registerForm);
                
                //AJAX request
                fetch('../actions/register_customer_action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = '../Login/login.php';
                    } else {
                        alert('Error: ' + data.message);
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            }
        });
    }

    //form validation
    function validateForm() {
        let isValid = true;

        //Reset errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        //Full name
        const name = document.getElementById('customer_name');
        if (!name.value.trim()) {
            showError(name, 'Full name is required');
            isValid = false;
        }

        //Email
        const email = document.getElementById('customer_email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim() || !emailRegex.test(email.value)) {
            showError(email, 'Valid email is required');
            isValid = false;
        }

        //Password
        const password = document.getElementById('customer_pass');
        const passwordCheck = validatePassword(password.value);
        if (!passwordCheck.isValid) {
            showError(password, passwordCheck.message);
            isValid = false;
        }

        //Country
        const country = document.getElementById('customer_country');
        if (!country.value) {
            showError(country, 'Please select a country');
            isValid = false;
        }

        //City
        const city = document.getElementById('customer_city');
        if (!city.value.trim()) {
            showError(city, 'City is required');
            isValid = false;
        }

        //Contact
        const contact = document.getElementById('customer_contact');
        if (!contact.value.trim()) {
            showError(contact, 'Contact number is required');
            isValid = false;
        } else if (window.libphonenumber && window.libphonenumber.parsePhoneNumberFromString) {
            try {
                const phoneNumber = window.libphonenumber.parsePhoneNumberFromString(contact.value, country.value);
                if (!phoneNumber || !phoneNumber.isValid()) {
                    showError(contact, 'Please enter a valid phone number for the selected country');
                    isValid = false;
                }
            } catch (e) {
                console.error('Phone number validation error:', e);
            }
        }

        return isValid;
    }

    //password validation
    function validatePassword(password) {
        if (!password || password.length < 8) {
            return { isValid: false, message: 'Password must be at least 8 characters' };
        }
        if (!/[A-Z]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one uppercase letter' };
        }
        if (!/[a-z]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one lowercase letter' };
        }
        if (!/[0-9]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one number' };
        }
        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
            return { isValid: false, message: 'Password must contain at least one special character' };
        }
        return { isValid: true, message: 'Password is valid' };
    }

    //error check
    function showError(input, message) {
        input.classList.add('is-invalid');
        const errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    //email check
    const emailInput = document.getElementById('customer_email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && emailRegex.test(email)) {
                fetch('../actions/register_customer_action.php?check_email=' + encodeURIComponent(email))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        showError(emailInput, 'Email already exists');
                    }
                })
                .catch(err => console.error('Email check error:', err));
            }
        });
    }
});
