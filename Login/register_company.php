<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;600&display=swap" rel="stylesheet">


    <title>Pharmaceutical Company Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" rel="stylesheet">


</head>
<body>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Pharmaceutical Company Registration</h4>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="../actions/register_customer_action.php" id="registerForm">
                            <input type="hidden" name="user_role" value="1">

                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                <div class="invalid-feedback">Enter your full name</div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                <div class="invalid-feedback">Enter a valid email</div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_pass" class="form-label">Password</label>
                                <input type="password" class="form-control" id="customer_pass" name="customer_pass" required>
                                <div class="invalid-feedback">Enter a correct password</div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_country" class="form-label">Country</label>
                                <select id="customer_country" name="customer_country" class="form-select selectpicker" data-live-search="true" required>
                                    <option value="">Choose country</option>
                                </select>
                                <div class="invalid-feedback">Select a country</div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_city" class="form-label">City</label>
                                <input type="text" class="form-control" id="customer_city" name="customer_city" required>
                                <div class="invalid-feedback">Enter your city</div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_contact" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
                                <div class="invalid-feedback">Enter contact number</div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" required>
                                <div class="invalid-feedback">Enter company name</div>
                            </div>

                            <div class="mb-3">
                                <label for="pharmaceutical_registration_number" class="form-label">Pharmaceutical Registration Number</label>
                                <input type="text" class="form-control" id="pharmaceutical_registration_number" name="pharmaceutical_registration_number" required>
                                <div class="invalid-feedback">Enter registration number</div>
                            </div>

                            <div id="msg" class="mt-3"></div>

                            <div class="d-grid">
                                <button type="submit" id="submitBtn" class="btn btn-primary">Register</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="login.php">Already have an account? Login</a>
                            </div>
                        </form>
                    </div>
                </div>
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
            // Load countries
            fetch('https://restcountries.com/v2/all?fields=name,alpha2Code')
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data)) return;
                    const countrySelect = document.getElementById('customer_country');
                    data.sort((a, b) => a.name.localeCompare(b.name));
                    data.forEach(customer_country => {
                        const option = document.createElement('option');
                        option.value = customer_country.alpha2Code;
                        option.textContent = customer_country.name;
                        option.setAttribute('data-tokens', customer_country.name);
                        countrySelect.appendChild(option);
                    });
                    if ($('.selectpicker').length) $('.selectpicker').selectpicker('refresh');
                })
                .catch(err => console.error('Error loading countries', err));
        });
    </script>
</body>
</html>