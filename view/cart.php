<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
if (!isLoggedIn()) {
  header('Location: ../Login/login.php'); exit();
}
$cartCtrl = new cart_controller();
$summary = $cartCtrl->get_cart_summary_ctr(getUserId());
$items = $summary['items'] ?? [];
$total = $summary['total_amount'] ?? 0.0;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Your Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3>Your Cart</h3>
  <div id="cartMsg"></div>
  <?php if (count($items) === 0): ?>
    <p class="text-muted">Your cart is empty. <a href="/view/all_product.php">Continue shopping</a></p>
  <?php else: ?>
    <table class="table">
      <thead><tr><th>Image</th><th>Product</th><th>Price</th><th width="120">Qty</th><th>Subtotal</th><th></th></tr></thead>
      <tbody>
      <?php foreach($items as $it): 
        $subtotal = floatval($it['qty']) * floatval($it['product_price']);
      ?>
        <tr data-product-id="<?= $it['product_id'] ?>">
          <!--<td><img src="/<//?= htmlspecialchars($it['product_image'] ?: 'uploads/placeholder.png') ?>" style="width:80px;height:60px;object-fit:cover"></td>-->
          <td><img src="<?= UPLOAD_BASE_URL . htmlspecialchars($p['product_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['product_title']) ?>" style="width:80px;height:60px;object-fit:cover"></td>
          <td><?= htmlspecialchars($it['product_title']) ?></td>
          <td>GHS <?= number_format($it['product_price'],2) ?></td>
          <td>
            <input type="number" min="1" value="<?= intval($it['qty']) ?>" class="form-control qtyInput" style="width:90px">
          </td>
          <td class="subtotal">GHS <?= number_format($subtotal,2) ?></td>
          <td>
            <button class="btn btn-sm btn-danger btnRemove">Remove</button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center">
      <div>
        <button id="emptyCartBtn" class="btn btn-outline-danger">Empty Cart</button>
        <a href="/view/all_product.php" class="btn btn-secondary ms-2">Continue Shopping</a>
      </div>
      <div>
        <strong>Total: GHS <?= number_format($total,2) ?></strong>
        <a href="/view/checkout.php" class="btn btn-success ms-3">Proceed to Checkout</a>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="../js/cart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // wire quantity changes and remove buttons
  document.querySelectorAll('.qtyInput').forEach(input => {
    input.addEventListener('change', (e) => {
      const row = input.closest('tr');
      const pid = row.dataset.productId;
      const qty = parseInt(input.value);
      updateCartQty(pid, qty, (res) => {
        if (res.status === 'success') {
          // refresh page for simplicity (or update DOM)
          location.reload();
        } else {
          alert(res.message || 'Failed');
        }
      });
    });
  });

  document.querySelectorAll('.btnRemove').forEach(btn => {
    btn.addEventListener('click', () => {
      const row = btn.closest('tr');
      const pid = row.dataset.productId;
      if (!confirm('Remove item?')) return;
      removeCartItem(pid, (res) => {
        if (res.status === 'success') location.reload();
        else alert(res.message || 'Failed');
      });
    });
  });

  const emptyBtn = document.getElementById('emptyCartBtn');
  if (emptyBtn) emptyBtn.addEventListener('click', () => {
    if (!confirm('Empty cart?')) return;
    emptyCart((res)=> {
      if (res.status === 'success') location.reload();
      else alert(res.message || 'Failed');
    });
  });
});
</script>
</body>
</html>
