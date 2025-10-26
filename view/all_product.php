<?php
require_once __DIR__ . '/../view/header.php';
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

$productCtrl = new product_controller();
$categoryCtrl = new CategoryController();
$brandCtrl = new BrandController();

$categories = $categoryCtrl->fetch_categories_ctr(getUserId())['data'] ?? [];
$brands = $brandCtrl->fetch_brand_ctr(getUserId())['data'] ?? [];
$products = $productCtrl->fetch_products_public_ctr()['data'] ?? [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>All Products - E-Pharmacy</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Inter, system-ui, Arial, sans-serif; background:#f7f9fc; }
    .hero { background: linear-gradient(90deg,#e6fff7,#fff); padding:36px 0; margin-bottom:18px; }
    .product-card { transition: transform .12s ease, box-shadow .12s ease; border:none; border-radius:10px; background:white; }
    .product-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(15,15,15,.08); }
    .price { font-weight:700; color:#0b6623; }
    .badge-cat { background: rgba(11,102,35,0.06); color:#0b6623; border-radius:6px; padding:4px 8px; font-size:.75rem; }
    .search-input { max-width:640px; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">E-Pharmacy</a>
    <div class="ms-auto d-flex align-items-center gap-2">
      <a class="btn btn-outline-primary me-2" href="../Login/register.php">Register</a>
      <a class="btn btn-primary" href="../Login/login.php">Login</a>
    </div>
  </div>
</nav>

<section class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1 class="mb-2">Trusted Medicines from Ghanaian Pharmaceutical Partners</h1>
        <p class="text-muted">Browse, search and filter medications. Prescription info displayed where available.</p>
      </div>
      <div class="col-lg-5 text-lg-end">
        <input id="globalSearch" class="form-control search-input d-inline-block" placeholder="Search meds, symptoms or keywords..." />
      </div>
    </div>
  </div>
</section>

<div class="container">
  <div class="row mb-3">
    <div class="col-md-3">
      <select id="filterCategory" class="form-select">
        <option value="0">All Categories</option>
        <?php foreach($categories as $cat): ?>
          <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select id="filterBrand" class="form-select">
        <option value="0">All Brands</option>
        <?php foreach($brands as $b): ?>
          <option value="<?= $b['brand_id'] ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select id="sortBy" class="form-select">
        <option value="newest">Newest</option>
        <option value="price_asc">Price: Low → High</option>
        <option value="price_desc">Price: High → Low</option>
      </select>
    </div>
    <div class="col-md-3 text-end">
      <small id="resultsInfo" class="text-muted"></small>
    </div>
  </div>

  <div id="productsGrid" class="row g-3">
    <?php if(count($products) > 0): ?>
      <?php foreach($products as $p): ?>
        <div class="col-md-3">
          <div class="card product-card h-100"> 
            <img src="/../uploads/<?= htmlspecialchars($p['product_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['product_title']) ?>">
            <div class="card-body">
              <span class="badge-cat"><?= htmlspecialchars($p['cat_name'] ?? 'Uncategorized') ?></span>
              <h6 class="mt-2"><?= htmlspecialchars($p['product_title']) ?></h6>
              <p class="price mb-1">GHS <?= number_format($p['product_price'],2) ?></p>
              <p class="text-muted small mb-2"><?= htmlspecialchars($p['brand_name'] ?? '') ?></p>
              <button class="btn btn-outline-success w-100" disabled>Add to Cart</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-muted py-5">No products available yet.</p>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
