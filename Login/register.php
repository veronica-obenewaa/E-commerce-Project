
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Customer Registration - Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0b6623 0%, #14a851 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 550px;
        }

        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(11, 102, 35, 0.25);
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            background: linear-gradient(135deg, #0b6623 0%, #14a851 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .auth-header h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .auth-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        .auth-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row-full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0b6623;
            background: white;
            box-shadow: 0 0 0 3px rgba(11, 102, 35, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .form-divider {
            border-top: 2px solid #f0f0f0;
            margin: 28px 0;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #0b6623 0%, #14a851 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(11, 102, 35, 0.25);
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(11, 102, 35, 0.35);
        }

        .auth-footer {
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #f0f0f0;
        }

        .auth-footer p {
            margin: 0;
            font-size: 0.95rem;
            color: #666;
        }

        .auth-footer a {
            color: #0b6623;
            text-decoration: none;
            font-weight: 700;
        }

        .auth-footer a:hover {
            color: #14a851;
            text-decoration: underline;
        }

        .role-selector {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .role-option {
            flex: 1;
            min-width: 150px;
            text-align: center;
            padding: 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .role-option:hover {
            border-color: #0b6623;
        }

        .role-option a {
            text-decoration: none;
            color: #333;
            display: block;
            font-weight: 600;
        }

        .role-option i {
            display: block;
            font-size: 1.5rem;
            color: #0b6623;
            margin-bottom: 8px;
        }

        @media (max-width: 550px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .auth-body {
                padding: 30px 20px;
            }

            .auth-header {
                padding: 30px 20px;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }

            .role-selector {
                flex-direction: column;
            }

            .role-option {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-heart-pulse"></i> Med-ePharma</h1>
                <p>Create Your Account</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="" id="registerForm">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label for="customer_name"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Your full name" required>
                            <div class="invalid-feedback">Enter full name</div>
                        </div>

                        <div class="form-group">
                            <label for="customer_email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="your@email.com" required>
                            <div class="invalid-feedback">Enter a valid email</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer_pass"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="customer_pass" name="customer_pass" placeholder="Create a strong password" required>
                        <div class="invalid-feedback">Enter a correct password</div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label for="customer_country"><i class="fas fa-globe"></i> Country</label>
                            <select class="selectpicker" id="customer_country" name="customer_country" data-live-search="true" required>
                                <option value="">Select Country</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label for="customer_city"><i class="fas fa-map-pin"></i> City</label>
                            <input type="text" class="form-control" id="customer_city" name="customer_city" placeholder="Your city" required>
                            <div class="invalid-feedback">Enter your city</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="customer_contact"><i class="fas fa-phone"></i> Contact Number</label>
                        <input type="tel" class="form-control" id="customer_contact" name="customer_contact" placeholder="+233 XXX XXX XXX" required>
                        <div class="invalid-feedback">Enter a valid phone number</div>
                    </div>

                    <button type="submit" id="submitBtn" class="submit-btn">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/libphonenumber-js/1.9.6/libphonenumber-js.min.js"></script>
    <script src="../js/register.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('https://restcountries.com/v2/all?fields=name,alpha2Code')
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data)) {
                        console.error("Unexpected response:", data);
                        return;
                    }

                    const countrySelect = document.getElementById('customer_country');
                    data.sort((a, b) => a.name.localeCompare(b.name));

                    data.forEach(customer_country => {
                        const option = document.createElement('option');
                        option.value = customer_country.alpha2Code;
                        option.textContent = customer_country.name;
                        option.setAttribute('data-tokens', customer_country.name);
                        countrySelect.appendChild(option);
                    });

                    if ($('.selectpicker').length) {
                        $('.selectpicker').selectpicker('refresh');
                    }
                })
                .catch(error => {
                    console.error("Error loading countries:", error);
                });
        });
    </script>
</body>
</html>
