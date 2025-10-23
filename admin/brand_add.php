<?php
require_once __DIR__ . '/../settings/core.php';

// protect route
if (!isLoggedIn() || !isAdmin()) {
    header('Location: /Login/login.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Brand</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Add Brand</h2>
        <a href="/admin/brand.php" class="btn btn-outline-secondary">View Brands</a>
    </div>

    <div id="addMsg"></div>

    <form id="addBrandForm">
        <div class="mb-3">
            <label for="brand_name" class="form-label">Brand Name</label>
            <input type="text" name="brand_name" id="brand_name" class="form-control" required>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary">Add Brand</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/brand.js"></script>
</body>
</html>
