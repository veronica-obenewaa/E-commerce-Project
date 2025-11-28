
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Login - Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #eeeeeeff 0%, #54a174ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
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
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .form-group input:focus {
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
            font-size: 0.85rem;
            margin-top: 6px;
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
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(11, 102, 35, 0.35);
        }

        .submit-btn:active {
            transform: translateY(0);
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
            transition: all 0.3s ease;
        }

        .auth-footer a:hover {
            color: #14a851;
            text-decoration: underline;
        }

        #msg {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        @media (max-width: 480px) {
            .auth-body {
                padding: 30px 20px;
            }

            .auth-header {
                padding: 30px 20px;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }

            .auth-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1><i class="fas fa-heart-pulse"></i> Med-ePharma</h1>
                <p>Welcome Back</p>
            </div>

            <div class="auth-body">
                <form method="POST" action="" id="loginForm" novalidate>
                    <div class="form-group">
                        <label for="customer_email"><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Enter your email" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label for="customer_pass"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="customer_pass" name="customer_pass" placeholder="Enter your password" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div id="msg" class="mt-3"></div>

                    <?php if (!empty($_GET['redirect'])): ?>
                        <input type="hidden" id="redirect" name="redirect" value="<?= htmlspecialchars($_GET['redirect']) ?>">
                    <?php else: ?>
                        <input type="hidden" id="redirect" name="redirect" value="">
                    <?php endif; ?>

                    <button type="submit" id="submitBtn" class="submit-btn">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
            </div>

            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/login.js"></script>
</body>
</html>
