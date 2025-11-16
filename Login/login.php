
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;600&display=swap" rel="stylesheet">


    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    


</head>
<body>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Login</h4>

                    </div>

                    <div class="card-body">
                        <form method="POST" action="" id="loginForm" novalidate>
                            <div class="mb-3">
                                <label for="customer_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="mb-3">
                                <label for="customer_pass" class="form-label">Password</label>
                                <input type="password" class="form-control" id="customer_pass" name="customer_pass" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div id="msg" class="mt-3"></div>

                            <div class="d-grid">
                                <button type="submit" id="submitBtn" class="btn btn-primary">Login</button>
                            </div>

                            <div class="text-center mt-3">
                                <p>Don't have an account? <a href="register.php">Register here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/login.js"></script>

    
</body>
</html>
