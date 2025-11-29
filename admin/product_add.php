<?php
// admin/product_add.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// Allow admin/pharmaceutical company users (role 1) to add products
// With new role mapping: 1=pharmaceutical company, 2=customer, 3=physician
if (!isLoggedIn() || !isAdmin()) {
    // If not authorized, redirect to login and include return URL so user can be taken back after login
    $return = urlencode('../admin/product_add.php');
    header('Location: ../Login/login.php?redirect=' . $return);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Product</title>
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
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 20px;
        }
        
        .page-header {
            margin-bottom: 30px;
            padding: 30px 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 12px;
        }
        
        .page-header h2 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 28px;
        }
        
        .page-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 20px;
            padding-bottom: 15px;
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 3px solid var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #025d0aff 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(2, 93, 10, 0.3);
            color: white;
        }
        
        .btn-back {
            display: inline-block;
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 20px;
        }
        
        .btn-back:hover {
            color: var(--primary-color);
            transform: translateX(-5px);
        }
        
        .required {
            color: var(--accent-color);
        }
        
        .file-upload-area {
            border: 2px dashed var(--secondary-color);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .file-upload-area:hover {
            background-color: rgba(2, 93, 10, 0.1);
            border-color: #025d0aff;
        }
        
        .file-upload-area i {
            font-size: 32px;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-lg">
        <div class="page-header">
            <h2>
                <i class="fas fa-pills"></i>
                Add New Medication
            </h2>
            <p>Create a new medication product or bulk upload multiple products</p>
        </div>

        <div id="addMsg"></div>

        <!-- Single Product Form -->
        <div class="form-card">
            <div class="section-title">
                <i class="fas fa-plus"></i>
                Single Medication Entry
            </div>

            <form id="addProductForm" method="POST" action="../actions/add_product_action.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-folder"></i>
                                Category
                                <span class="required">*</span>
                            </label>
                            <select name="product_cat" class="form-select" id="product_cat" required></select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Brand
                                <span class="required">*</span>
                            </label>
                            <select name="product_brand" class="form-select" id="product_brand" required></select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-heading"></i>
                        Medication Title
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="product_title" class="form-control" placeholder="e.g., Paracetamol 500mg Tablets" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-money-bill"></i>
                                Price
                                <span class="required">*</span>
                            </label>
                            <input type="number" step="0.01" name="product_price" class="form-control" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-image"></i>
                                Product Image
                            </label>
                            <input type="file" name="product_image" accept="image/*" class="form-control">
                            <small class="text-muted">JPG, PNG, GIF (Max 5MB)</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-left"></i>
                        Description
                    </label>
                    <textarea name="product_desc" class="form-control" rows="4" placeholder="Enter product description"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-key"></i>
                        Keywords
                    </label>
                    <input type="text" name="product_keywords" class="form-control" placeholder="e.g., pain relief, fever, headache (comma separated)">
                </div>

                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-save"></i>
                    Add Medication
                </button>
            </form>
        </div>

        <!-- Bulk Upload Section -->
        <div class="form-card">
            <div class="section-title">
                <i class="fas fa-upload"></i>
                Bulk Upload
            </div>

            <p class="text-muted">Upload multiple medications at once using an Excel file. Download the template to get started.</p>

            <div class="row mb-3">
                <div class="col-md-6">
                    <a class="btn btn-outline-secondary" href="../actions/download_product_template_action.php">
                        <i class="fas fa-download"></i>
                        Download Excel Template
                    </a>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Fill in the Excel template with your medication details and images, then zip the file with images and upload below.
            </div>

            <form id="bulkUploadForm" method="POST" action="../actions/bulk_upload_product_action.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-archive"></i>
                        Upload ZIP File
                        <span class="required">*</span>
                    </label>
                    <div class="file-upload-area">
                        <input type="file" name="zipfile" accept=".zip" class="form-control" id="zipfile" required style="display:none;">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to select ZIP file or drag and drop</p>
                        <small class="text-muted">Maximum file size: 50MB</small>
                    </div>
                </div>
                <div id="bulkMsg"></div>
                <button type="submit" class="btn btn-submit">
                    <i class="fas fa-upload"></i>
                    Upload ZIP
                </button>
            </form>
        </div>

        <a href="product.php" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Back to Medications
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/product.js"></script>
</body>
</html>