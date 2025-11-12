<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
if (!isLoggedIn()) { header('Location: ../Login/login.php'); exit(); }
$cartCtrl = new cart_controller();
$summary = $cartCtrl->get_cart_summary_ctr(getUserId());
$items = $summary['items'] ?? [];
$total = $summary['total_amount'] ?? 0.0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Checkout (Simulated)</h3>
  <div id="checkoutMsg"></div>

  <?php if (count($items) === 0): ?>
    <p class="text-muted">No items in cart. <a href="/view/all_product.php">Continue shopping</a></p>
  <?php else: ?>
    <div class="card mb-3">
      <div class="card-body">
        <?php foreach($items as $it): ?>
          <div class="d-flex align-items-center mb-2">
            <img src="/<?= htmlspecialchars($it['product_image'] ?: 'uploads/placeholder.png') ?>" style="width:80px;height:60px;object-fit:cover" class="me-3">
            <div>
              <div><?= htmlspecialchars($it['product_title']) ?></div>
              <div class="text-muted">Qty: <?= intval($it['qty']) ?> × GHS <?= number_format($it['product_price'],2) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
        <hr>
        <h5>Total: GHS <?= number_format($total,2) ?></h5>
        <p class="text-muted">Click "Simulate Payment" to complete checkout (this only simulates payment).</p>
        <button id="confirmPaymentBtn" class="btn btn-success">Confirm Payment (Simulate)</button>
        <a href="/view/cart.php" class="btn btn-outline-secondary ms-2">Back to Cart</a>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="../js/checkout.js"></script>
</body>
</html>
