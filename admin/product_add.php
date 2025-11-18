<?php
// admin/product_add.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// Allow admin users and pharmaceutical companies (role 3) to add products
if (!isLoggedIn() || !(isAdmin() || getUserRole() == 3)) {
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
<title>Add Product Page</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2>Add Product</h2>
    <div id="addMsg"></div>

    <form id="addProductForm" method="POST" action="../actions/add_product_action.php" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Category</label>
                <input type="text" name="product_cat" class="form-control" placeholder="e.g., Pain Relief" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Brand</label>
                <input type="text" name="product_brand" class="form-control" placeholder="e.g., Aspirin" required>
            </div>
        </div>

        <div class="mb-3"><label>Title</label>
            <input type="text" name="product_title" class="form-control" required>
        </div>

        <div class="mb-3"><label>Price</label>
            <input type="number" step="0.01" name="product_price" class="form-control" required>
        </div>

        <div class="mb-3"><label>Description</label>
            <textarea name="product_desc" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3"><label>Keywords (comma separated)</label>
            <input type="text" name="product_keywords" class="form-control">
        </div>

        <div class="mb-3"><label>Image</label>
            <input type="file" name="product_image" accept="image/*" class="form-control">
        </div>

        <div class="d-grid">
            <button class="btn btn-primary">Add Product</button>
        </div>
    </form>

    <hr>

    <h4>Bulk Upload</h4>
    <p>Download the Excel template, fill it (include image filenames), zip the .xlsx + images and upload below.</p>
    <div class="mb-3">
        <a class="btn btn-outline-secondary" href="../actions/download_product_template_action.php">Download Excel Template (.xlsx)</a>
    </div>

    <form id="bulkUploadForm" method="POST" action="../actions/bulk_upload_product_action.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Upload ZIP (template + images)</label>
            <input type="file" name="zipfile" accept=".zip" class="form-control" required>
        </div>
        <div id="bulkMsg" class="mb-3"></div>
        <div><button class="btn btn-secondary">Upload ZIP</button></div>
    </form>
</div>

<script src="../js/product.js"></script>
</body>
</html>