<?php
require_once __DIR__ . '/../settings/core.php';

// protect route
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../Login/login.php');
    exit();
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Brand Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2>Manage Brands</h2>
    <p>Here are your Brand Names. You can edit or delete them.</p>
    <a href="brand_add.php" class="btn btn-success mb-3">+ Add Brand</a>

    <div id="brandList"></div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="updateBrandForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Brand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="updateMsg"></div>
        <input type="hidden" name="brand_id" id="update_brand_id">
        <div class="mb-3">
            <label class="form-label">Brand Name</label>
            <input type="text" class="form-control" name="brand_name" id="update_brand_name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">Save changes</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/brand.js"></script>
</body>
</html>
