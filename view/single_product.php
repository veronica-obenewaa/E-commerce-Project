<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';

$product_id = intval($_GET['id'] ?? 0);
$productCtrl = new product_controller();

// Fetch single product details (public view)
$productData = $productCtrl->fetch_single_product_public_ctr($product_id)['data'] ?? null;

if (!$productData) {
  echo "<div style='padding:50px;text-align:center;color:red;'>Product not found.</div>";
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($productData['product_title']) ?> - E-Pharmacy</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f7f9fc; font-family:Inter,system-ui,Arial,sans-serif; }
    .hero-img { max-height:420px; object-fit:cover; width:100%; border-radius:10px; }
    .price { color:#0b6623; font-weight:700; font-size:1.5rem; }
    .badge-cat { background:rgba(11,102,35,0.08); color:#0b6623; border-radius:6px; padding:4px 10px; font-size:.8rem; }
  </style>
</head>
<body>
<nav class="navbar navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">E-Pharmacy</a>
  </div>
</nav>

<div class="container py-5">
  <div class="row g-4 align-items-start">
    <div class="col-md-6">
      <img class="hero-img border" 
           src="<?= UPLOAD_BASE_URL . htmlspecialchars($productData['product_image'] ?: 'placeholder.png') ?>" 
           alt="<?= htmlspecialchars($productData['product_title']) ?>">
    </div>
    <div class="col-md-6">
      <h2 class="fw-bold mb-2"><?= htmlspecialchars($productData['product_title']) ?></h2>
      <div class="mb-3">
        <span class="badge-cat me-2"><?= htmlspecialchars($productData['cat_name'] ?? 'Uncategorized') ?></span>
        <span class="badge bg-light text-dark"><?= htmlspecialchars($productData['brand_name'] ?? '') ?></span>
      </div>
      <p class="price mb-3">GHS <?= number_format($productData['product_price'], 2) ?></p>
      <p class="text-muted mb-3"><?= nl2br(htmlspecialchars($productData['product_desc'])) ?></p>
      <p><strong>Keywords:</strong> <?= htmlspecialchars($productData['product_keywords']) ?></p>

      <div class="mt-4">
        <div id="cartMsg" class="mb-2"></div>
  <button class="btn btn-outline-success w-100 btn-add-to-cart" data-product-id="<?= $productData['product_id'] ?>">Add to Cart</button>
        <a href="all_product.php" class="btn btn-outline-secondary btn-lg ms-2">Back to Products</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/view_product.js"></script>
<script src="../js/cart.js"></script>


</body>
</html>
