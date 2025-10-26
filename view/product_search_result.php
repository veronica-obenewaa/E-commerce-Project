<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

// Instantiate controllers
$productCtrl  = new product_controller();
$categoryCtrl = new CategoryController();
$brandCtrl    = new BrandController();

// Get filters and search query from URL
$q        = trim($_GET['q'] ?? '');
$cat_id   = intval($_GET['cat_id'] ?? 0);
$brand_id = intval($_GET['brand_id'] ?? 0);
$page     = max(1, intval($_GET['page'] ?? 1));
$pageSize = 10;

// Fetch filter options
$categories = $categoryCtrl->fetch_categories_ctr(getUserId())['data'] ?? [];
$brands     = $brandCtrl->fetch_brand_ctr(getUserId())['data'] ?? [];

// Fetch products matching search/filter
$productData = $productCtrl->search_products_ctr($q, $cat_id, $brand_id, $page, $pageSize);
$products = $productData['data'] ?? [];
$total = $productData['total'] ?? 0;
$pages = ceil($total / $pageSize);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Search Results - E-Pharmacy</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f7f9fc; font-family:Inter,system-ui,Arial,sans-serif; }
    .product-card { transition:transform .15s ease,box-shadow .15s ease; border:none; border-radius:10px; }
    .product-card:hover { transform:translateY(-4px); box-shadow:0 10px 25px rgba(15,15,15,.08); }
    .price { font-weight:700; color:#0b6623; }
    .badge-cat { background:rgba(11,102,35,.07); color:#0b6623; border-radius:6px; padding:4px 8px; font-size:.75rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">E-Pharmacy</a>
    <form class="d-flex ms-auto" method="get" action="product_search_result.php">
      <input class="form-control me-2" type="search" name="q" placeholder="Search medications..." value="<?= htmlspecialchars($q) ?>">
      <button class="btn btn-outline-success">Search</button>
    </form>
  </div>
</nav>

<div class="container py-4">
  <div class="row mb-3">
    <div class="col-md-3">
      <select class="form-select" onchange="location=this.value">
        <option value="?q=<?= urlencode($q) ?>">All Categories</option>
        <?php foreach ($categories as $c): ?>
          <option value="?q=<?= urlencode($q) ?>&cat_id=<?= $c['cat_id'] ?>"
            <?= $cat_id == $c['cat_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['cat_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select class="form-select" onchange="location=this.value">
        <option value="?q=<?= urlencode($q) ?>">All Brands</option>
        <?php foreach ($brands as $b): ?>
          <option value="?q=<?= urlencode($q) ?>&brand_id=<?= $b['brand_id'] ?>"
            <?= $brand_id == $b['brand_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($b['brand_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-6 text-end">
      <p class="text-muted small mb-0"><?= $total ?> result(s) found</p>
    </div>
  </div>

  <div class="row g-3">
    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $p): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="card product-card h-100">
            <img src="<?= UPLOAD_BASE_URL . htmlspecialchars($p['product_image'] ?: 'placeholder.png') ?>" 
                 class="card-img-top" style="height:180px;object-fit:cover"
                 alt="<?= htmlspecialchars($p['product_title']) ?>">
            <div class="card-body d-flex flex-column">
              <span class="badge-cat mb-1"><?= htmlspecialchars($p['cat_name'] ?? 'Uncategorized') ?></span>
              <h6 class="fw-semibold mb-1"><?= htmlspecialchars($p['product_title']) ?></h6>
              <div class="text-muted small mb-2"><?= htmlspecialchars($p['brand_name'] ?? '') ?></div>
              <div class="price mb-3">GHS <?= number_format($p['product_price'],2) ?></div>
              <a href="single_product.php?id=<?= $p['product_id'] ?>" class="btn btn-outline-primary btn-sm mb-2">View</a>
              <button class="btn btn-success btn-sm" disabled>Add to Cart</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12 text-center text-muted py-5">No products found for "<?= htmlspecialchars($q) ?>".</div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($pages > 1): ?>
    <nav class="mt-4">
      <ul class="pagination justify-content-center">
        <?php for ($i=1; $i <= $pages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?q=<?= urlencode($q) ?>&cat_id=<?= $cat_id ?>&brand_id=<?= $brand_id ?>&page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
