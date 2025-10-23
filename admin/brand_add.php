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
<title>Add Brand</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Add Brand</h2>
    <p>Enter a new Brand name below:</p>

    <div id="addMsg"></div>
    <form id="addBrandForm">
        <div class="mb-3">
            <label class="form-label">Brand Name</label>
            <input type="text" class="form-control" name="brand_name" required>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary">Add Brand</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/brand.js"></script>
</body>
</html>
