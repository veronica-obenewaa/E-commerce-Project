document.addEventListener('DOMContentLoaded', function () {
    const registerForm = document.getElementById('registerForm');

    function ajaxPost(formData, onSuccess, onError) {
        fetch('../actions/register_customer_action.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(onSuccess)
            .catch(onError || function (e) { console.error(e); });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearMessages();

            if (!validateForm()) return;

            const submitBtn = document.getElementById('submitBtn');
            const origText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registering...';
            submitBtn.disabled = true;

            const formData = new FormData(registerForm);
            ajaxPost(formData, function (data) {
                if (data.status === 'success') {
                    showGlobalMessage('success', data.message || 'Registration successful');
                    setTimeout(() => window.location.href = '../Login/login.php', 1400);
                } else {
                    showGlobalMessage('danger', data.message || 'Registration failed');
                    submitBtn.innerHTML = origText;
                    submitBtn.disabled = false;
                }
            }, function () {
                showGlobalMessage('danger', 'Server error. Please try again later.');
                submitBtn.innerHTML = origText;
                submitBtn.disabled = false;
            });
        });
    }

    function clearMessages() {
        document.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        const msg = document.getElementById('msg'); if (msg) msg.innerHTML = '';
    }

    function showGlobalMessage(type, text) {
        const msg = document.getElementById('msg');
        if (!msg) return;
        msg.innerHTML = `<div class="alert alert-${type}">${text}</div>`;
    }

    // Validation shared functions
    function validateForm() {
        let ok = true;
        clearMessages();

        const name = document.getElementById('customer_name');
        if (!name || !name.value.trim()) { showError(name, 'Full name is required'); ok = false; }

        const email = document.getElementById('customer_email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !email.value.trim() || !emailRegex.test(email.value)) { showError(email, 'Valid email is required'); ok = false; }

        const password = document.getElementById('customer_pass');
        const pcheck = validatePassword(password ? password.value : '');
        if (!pcheck.isValid) { showError(password, pcheck.message); ok = false; }

        const country = document.getElementById('customer_country');
        if (!country || !country.value) { showError(country, 'Please select a country'); ok = false; }

        const city = document.getElementById('customer_city');
        if (!city || !city.value.trim()) { showError(city, 'City is required'); ok = false; }

        const contact = document.getElementById('customer_contact');
        if (!contact || !contact.value.trim()) { showError(contact, 'Contact number is required'); ok = false; }

        // Role-specific checks: the register pages include hidden user_role input
        const roleInput = document.querySelector('input[name="user_role"]');
        const role = roleInput ? parseInt(roleInput.value, 10) : 2;

        if (role === 1) { // pharmaceutical company
            const companyName = document.getElementById('company_name');
            const pharmaReg = document.getElementById('pharmaceutical_registration_number');
            if (!companyName || !companyName.value.trim()) { showError(companyName, 'Company name is required'); ok = false; }
            if (!pharmaReg || !pharmaReg.value.trim()) { showError(pharmaReg, 'Registration number is required'); ok = false; }
        }

        if (role === 3) { // physician
            const hospitalName = document.getElementById('hospital_name');
            const hospitalReg = document.getElementById('hospital_registration_number');
            const specs = document.getElementById('medical_specializations');
            if (!hospitalName || !hospitalName.value.trim()) { showError(hospitalName, 'Hospital name is required'); ok = false; }
            if (!hospitalReg || !hospitalReg.value.trim()) { showError(hospitalReg, 'Hospital registration number is required'); ok = false; }
            if (specs && specs.options.length && Array.from(specs.selectedOptions).length === 0) { showError(specs, 'Select at least one specialization'); ok = false; }
        }

        return ok;
    }

    function validatePassword(password) {
        if (!password || password.length < 8) return { isValid: false, message: 'Password must be at least 8 characters' };
        if (!/[A-Z]/.test(password)) return { isValid: false, message: 'Password must contain at least one uppercase letter' };
        if (!/[a-z]/.test(password)) return { isValid: false, message: 'Password must contain at least one lowercase letter' };
        if (!/[0-9]/.test(password)) return { isValid: false, message: 'Password must contain at least one number' };
        if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) return { isValid: false, message: 'Password must contain at least one special character' };
        return { isValid: true, message: 'Password valid' };
    }

    function showError(input, message) {
        if (!input) return;
        input.classList.add('is-invalid');
        const err = input.parentElement.querySelector('.invalid-feedback');
        if (err) { err.textContent = message; err.style.display = 'block'; }
    }

    // Email blur checks (exists)
    const emailInput = document.getElementById('customer_email');
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            const val = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!val || !emailRegex.test(val)) return;
            fetch('../actions/register_customer_action.php?check_email=' + encodeURIComponent(val))
                .then(r => r.json()).then(d => {
                    if (d.exists) showError(emailInput, 'Email already exists');
                }).catch(e => console.error(e));
        });
    }

    // Pharma reg blur check
    const pharmaInput = document.getElementById('pharmaceutical_registration_number');
    if (pharmaInput) {
        pharmaInput.addEventListener('blur', function () {
            const val = this.value.trim(); if (!val) return;
            fetch('../actions/register_customer_action.php?check_pharma=' + encodeURIComponent(val))
                .then(r => r.json()).then(d => { if (d.exists) showError(pharmaInput, 'Registration number already in use'); })
                .catch(e => console.error(e));
        });
    }

    // Hospital reg blur check
    const hospInput = document.getElementById('hospital_registration_number');
    if (hospInput) {
        hospInput.addEventListener('blur', function () {
            const val = this.value.trim(); if (!val) return;
            fetch('../actions/register_customer_action.php?check_hospital=' + encodeURIComponent(val))
                .then(r => r.json()).then(d => { if (d.exists) showError(hospInput, 'Registration number already in use'); })
                .catch(e => console.error(e));
        });
    }

});
