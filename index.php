<?php
require_once __DIR__ . '/settings/core.php';
require_once __DIR__ . '/controllers/category_controller.php';
require_once __DIR__ . '/controllers/brand_controller.php';



$categoryCtrl = new CategoryController();
$brandCtrl = new BrandController();

$categories = $categoryCtrl->fetch_categories_ctr(getUserId())['data'] ?? [];
$brands = $brandCtrl->fetch_brand_ctr(getUserId())['data'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>E-Pharmacy Ghana | Trusted Medications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Inter', system-ui, sans-serif; background-color: #f7f9fc; }
    .navbar-brand { font-weight: 700; color: #0b6623 !important; }
    .hero {
      background: linear-gradient(135deg, #e6fff2 0%, #ffffff 100%);
      padding: 80px 0;
    }
    .hero h1 { font-size: 2.5rem; font-weight: 700; color: #0b6623; }
    .hero p { color: #555; font-size: 1.1rem; }
    .search-box { max-width: 500px; }
    .filter-bar select { min-width: 180px; }
  </style>
</head>
<body>

<!-- NAVIGATION -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">E-Pharmacy</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="view/all_product.php">All Products</a></li>
      </ul>

	 
      <form class="d-flex" action="view/product_search_result.php" method="get">
        <input class="form-control me-2" name="q" type="search" placeholder="Search medicines..." aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>

      <div class="ms-3 d-flex align-items-center">
        <?php if (!isLoggedIn()): ?>
          <a href="Login/register.php" class="btn btn-outline-primary me-2">Register</a>
          <a href="Login/login.php" class="btn btn-primary">Login</a>
        <?php else: ?>
          <form method="post" action="Login/logout.php" class="d-inline">
            <button class="btn btn-outline-danger me-2">Logout</button>
          </form>
          <?php if (isAdmin()): ?>
            <a href="admin/category_add.php" class="btn btn-secondary me-2">Category</a>
            <a href="admin/brand_add.php" class="btn btn-secondary me-2">Brand</a>
            <a href="admin/product_add.php" class="btn btn-success me-2">Add Product</a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
  <div class="container">
    <h1>Welcome to E-Pharmacy Ghana</h1>
    <p class="mt-3 mb-4">Your trusted platform for genuine medications and health products from verified pharmaceutical suppliers.</p>
    <a href="view/all_product.php" class="btn btn-success btn-lg px-4">Browse All Products</a>
  </div>
</section>

<!-- FILTERS -->
<div class="container py-4">
  <h4 class="mb-3 text-success">Filter Products</h4>
  <form class="row g-3 filter-bar" action="view/product_search_result.php" method="get">
    <div class="col-md-4">
      <select class="form-select" name="cat_id">
        <option value="0">All Categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <select class="form-select" name="brand_id">
        <option value="0">All Brands</option>
        <?php foreach ($brands as $b): ?>
          <option value="<?= $b['brand_id'] ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4 d-grid">
      <button type="submit" class="btn btn-outline-success">Apply Filters</button>
    </div>
  </form>
</div>

<!-- FOOTER -->
<footer class="bg-white text-center py-3 mt-5 border-top">
  <small class="text-muted">&copy; <?= date('Y') ?> E-Pharmacy Ghana. All Rights Reserved.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
