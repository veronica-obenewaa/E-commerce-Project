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
  <title>Brand Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Brands</h2>
        <a href="/admin/brand_add.php" class="btn btn-primary">Add New Brand</a>
    </div>

    <p class="text-muted">Below are the brands you have added to the system.</p>
    <div id="brandList"></div>
</div>

<!-- Edit Modal -->
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
          <input type="text" name="brand_name" id="update_brand_name" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/brand.js"></script>
</body>
</html>
