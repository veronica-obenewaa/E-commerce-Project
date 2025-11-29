<?php
require_once __DIR__ . '/../settings/core.php';

// Protect route
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../Login/login.php');
    exit();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Brand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #025d0aff;
            --secondary-color: #27ae60;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --light-bg: #f8f9fa;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            margin: 0 auto;
            margin-top: 40px;
        }
        
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .page-header h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-header p {
            color: #7f8c8d;
            margin: 8px 0 0 0;
            font-size: 14px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #27ae60 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 15px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78, 235, 58, 0.3);
            color: white;
        }
        
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            color: var(--primary-color);
            transform: translateX(-5px);
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .required {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="page-header">
            <h2>
                <i class="fas fa-plus-circle"></i>
                Add New Brand
            </h2>
            <p>Create a new brand that will be available to all pharmaceutical companies</p>
        </div>

        <div id="addMsg"></div>
        
        <form id="addBrandForm">
            <div class="mb-3">
                <label class="form-label">
                    <i class="fas fa-tag"></i>
                    Brand Name
                    <span class="required">*</span>
                </label>
                <input type="text" class="form-control" name="brand_name" placeholder="e.g., Paracetamol, Aspirin" required>
                <small class="text-muted">Enter the brand name</small>
            </div>

            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                Add Brand
            </button>
        </form>

        <a href="brand.php" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Back to Brands
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/brand.js"></script>
</body>
</html>
