<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
if (!isLoggedIn()) {
  header('Location: ../Login/login.php'); exit();
}
$cartCtrl = new cart_controller();
// get items for the logged-in user
$items = $cartCtrl->get_user_cart_ctr(getUserId());
if (!is_array($items)) $items = [];
// compute total amount from items
$total = 0.0;
foreach ($items as $it_calc) {
  $total += (floatval($it_calc['qty']) * floatval($it_calc['product_price']));
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Shopping Cart - Med-ePharma</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
    }
    
    .cart-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem 0;
    }
    
    .cart-header {
      font-size: 2rem;
      font-weight: 800;
      color: #1a3a3a;
      margin-bottom: 2rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .cart-content {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 2rem;
    }
    
    /* Cart Items */
    .cart-items {
      background: #ffffff;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }
    
    .cart-item {
      display: flex;
      gap: 1.5rem;
      padding: 1.5rem;
      border-bottom: 1px solid #e5e7eb;
      align-items: center;
      transition: all 0.3s ease;
    }
    
    .cart-item:last-child {
      border-bottom: none;
    }
    
    .cart-item:hover {
      background: #f9fafb;
    }
    
    .item-image {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: cover;
      background: #f0f9ff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    
    .item-details {
      flex: 1;
    }
    
    .item-title {
      font-weight: 700;
      color: #1a3a3a;
      font-size: 1rem;
      margin-bottom: 0.5rem;
    }
    
    .item-meta {
      display: flex;
      gap: 1rem;
      font-size: 0.9rem;
      color: #4b7c7c;
      margin-bottom: 0.5rem;
    }
    
    .item-price {
      font-weight: 700;
      color: #059669;
      font-size: 1.1rem;
    }
    
    .item-actions {
      display: flex;
      gap: 0.75rem;
      align-items: center;
    }
    
    .btn-remove {
      background: #fee2e2;
      color: #991b1b;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.9rem;
    }
    
    .btn-remove:hover {
      background: #fca5a5;
      color: #7f1d1d;
    }
    
    /* Delivery Section */
    .delivery-section {
      background: #ffffff;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 2rem;
    }
    
    .section-title {
      font-weight: 700;
      color: #1a3a3a;
      margin-bottom: 1.5rem;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    
    .section-title i {
      color: #059669;
      font-size: 1.3rem;
    }
    
    .delivery-options {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    
    .delivery-option {
      background: #f9fafb;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      padding: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .delivery-option:hover {
      border-color: #059669;
      background: #f0fdf4;
    }
    
    .delivery-option input[type="radio"] {
      width: 20px;
      height: 20px;
      cursor: pointer;
      accent-color: #059669;
    }
    
    .delivery-info {
      flex: 1;
    }
    
    .delivery-name {
      font-weight: 700;
      color: #1a3a3a;
      font-size: 0.95rem;
      margin-bottom: 0.25rem;
    }
    
    .delivery-desc {
      font-size: 0.85rem;
      color: #4b7c7c;
    }
    
    /* Order Summary */
    .order-summary {
      background: #ffffff;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      position: sticky;
      top: 2rem;
    }
    
    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
      color: #4b7c7c;
      font-weight: 500;
    }
    
    .summary-item.total {
      border-top: 2px solid #e5e7eb;
      padding-top: 1rem;
      color: #1a3a3a;
      font-weight: 700;
      font-size: 1.2rem;
    }
    
    .summary-item.total span {
      color: #059669;
    }
    
    .btn-checkout {
      width: 100%;
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      color: white;
      border: none;
      padding: 1rem;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 1.5rem;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
    }
    
    .btn-checkout:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
    }
    
    .btn-checkout:disabled {
      background: #d1d5db;
      cursor: not-allowed;
      transform: none;
    }
    
    .empty-cart {
      background: #ffffff;
      border-radius: 15px;
      padding: 3rem;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .empty-cart i {
      font-size: 3rem;
      color: #d1d5db;
      margin-bottom: 1rem;
    }
    
    .empty-cart p {
      color: #4b7c7c;
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }
    
    .btn-continue {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      border-radius: 12px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }
    
    .btn-continue:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
      color: white;
    }
    
    @media (max-width: 768px) {
      .cart-content {
        grid-template-columns: 1fr;
      }
      
      .order-summary {
        position: relative;
        top: auto;
      }
      
      .cart-item {
        flex-direction: column;
        text-align: center;
      }
      
      .item-actions {
        justify-content: center;
        width: 100%;
      }
    }
  </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="container-fluid cart-container">
  <div class="cart-header">
    <i class="fas fa-shopping-cart"></i>
    Your Shopping Cart
  </div>

  <?php if (count($items) === 0): ?>
    <div class="empty-cart">
      <i class="fas fa-inbox"></i>
      <p>Your cart is empty</p>
      <a href="all_product.php" class="btn-continue">
        <i class="fas fa-pills"></i> Continue Shopping
      </a>
    </div>
  <?php else: ?>
    <div class="cart-content">
      <!-- Cart Items Column -->
      <div>
        <div class="cart-items">
          <?php foreach($items as $it):
            $subtotal = floatval($it['qty']) * floatval($it['product_price']);
          ?>
            <div class="cart-item" data-product-id="<?= $it['p_id'] ?>">
              <img src="<?= UPLOAD_BASE_URL . htmlspecialchars($it['product_image'] ?: 'placeholder.png') ?>" 
                   alt="<?= htmlspecialchars($it['product_title']) ?>" 
                   class="item-image">
              
              <div class="item-details">
                <div class="item-title"><?= htmlspecialchars($it['product_title']) ?></div>
                <div class="item-meta">
                  <span><i class="fas fa-box"></i> Qty: <?= intval($it['qty']) ?></span>
                </div>
                <div class="item-price">₵<?= number_format($subtotal, 2) ?></div>
              </div>
              
              <div class="item-actions">
                <button class="btn-remove btnRemove" data-product-id="<?= $it['p_id'] ?>">
                  <i class="fas fa-trash"></i> Remove
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- Delivery Section -->
        <div class="delivery-section" style="margin-top: 2rem;">
          <div class="section-title">
            <i class="fas fa-truck"></i> Delivery Service
          </div>
          
          <form id="deliveryForm">
            <div class="delivery-options">
              <label class="delivery-option">
                <input type="radio" name="delivery" value="bolt" onclick="selectDelivery(this)">
                <div class="delivery-info">
                  <div class="delivery-name">🚗 Bolt Rides</div>
                  <div class="delivery-desc">Fast and reliable delivery service</div>
                </div>
              </label>
              
              <label class="delivery-option">
                <input type="radio" name="delivery" value="uber" onclick="selectDelivery(this)">
                <div class="delivery-info">
                  <div class="delivery-name">🚙 Uber Rides</div>
                  <div class="delivery-desc">Premium delivery experience</div>
                </div>
              </label>
              
              <label class="delivery-option">
                <input type="radio" name="delivery" value="yango" onclick="selectDelivery(this)">
                <div class="delivery-info">
                  <div class="delivery-name">🚕 Yango Rides</div>
                  <div class="delivery-desc">Affordable and convenient delivery</div>
                </div>
              </label>
              
              <label class="delivery-option">
                <input type="radio" name="delivery" value="pickup" onclick="selectDelivery(this)">
                <div class="delivery-info">
                  <div class="delivery-name">📍 Personal Pickup</div>
                  <div class="delivery-desc">Collect your order yourself</div>
                </div>
              </label>
            </div>
          </form>
        </div>
      </div>

      <!-- Order Summary Sidebar -->
      <div>
        <div class="order-summary">
          <h3 style="font-weight: 700; margin-bottom: 1.5rem; color: #1a3a3a;">Order Summary</h3>
          
          <div class="summary-item">
            <span>Subtotal</span>
            <span>₵<?= number_format($total, 2) ?></span>
          </div>
          
          <div class="summary-item">
            <span>Delivery Fee</span>
            <span id="deliveryFee">₵0.00</span>
          </div>
          
          <div class="summary-item total">
            <span>Total:</span>
            <span>₵<span id="totalAmount"><?= number_format($total, 2) ?></span></span>
          </div>
          
          <button class="btn-checkout" id="checkoutBtn" disabled onclick="proceedToCheckout()">
            <i class="fas fa-lock"></i> Proceed to Checkout
          </button>
          
          <a href="all_product.php" style="text-align: center; display: block; margin-top: 1rem; color: #059669; text-decoration: none; font-weight: 600;">
            <i class="fas fa-arrow-left"></i> Continue Shopping
          </a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/cart.js"></script>

<script>
  const subtotal = <?= $total ?>;
  const deliveryFees = {
    'bolt': 15.00,
    'uber': 20.00,
    'yango': 12.00,
    'pickup': 0.00
  };

  function selectDelivery(radio) {
    const selectedService = radio.value;
    const fee = deliveryFees[selectedService];
    const total = subtotal + fee;
    
    document.getElementById('deliveryFee').textContent = '₵' + fee.toFixed(2);
    document.getElementById('totalAmount').textContent = total.toFixed(2);
    document.getElementById('checkoutBtn').disabled = false;
    
    // Store selected delivery service
    sessionStorage.setItem('selectedDelivery', selectedService);
  }

  function proceedToCheckout() {
    const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
    
    if (!selectedDelivery) {
      alert('Please select a delivery service');
      return;
    }
    
    const deliveryService = selectedDelivery.value;
    
    // Simulate redirect based on delivery service
    const urls = {
      'bolt': 'https://www.bolt.eu/',
      'uber': 'https://www.uber.com/',
      'yango': 'https://yango.com/',
      'pickup': 'check_out.php'
    };
    
    window.location.href = urls[deliveryService];
  }

  // Remove cart item
  document.querySelectorAll('.btnRemove').forEach(btn => {
    btn.addEventListener('click', () => {
      const pid = btn.getAttribute('data-product-id');
      if (!confirm('Remove item from cart?')) return;
      removeCartItem(pid, (res) => {
        if (res.status === 'success') {
          location.reload();
        } else {
          alert(res.message || 'Failed to remove item');
        }
      });
    });
  });
</script>

</body>
</html>

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
