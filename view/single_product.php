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
  <title><?= htmlspecialchars($productData['product_title']) ?> - Med-ePharma</title>
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
    
    .product-hero {
      background: #ffffff;
      padding: 4rem;
      padding-left: 2rem;
      margin-top: 2rem;
      margin-bottom: 3rem;
    }
    
   /* .product-image-container {
      background: linear-gradient(135deg, #ebf8f9ff 0%, #e9e9eaff 100%);
      border-radius: 20px;
      padding: 4rem;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 450px;
      box-shadow: 0 5px 10px rgba(11, 102, 35, 0.15);
      transform: rotate(5deg);
    }*/
    
    .product-image {
      max-width: 100%;
      max-height: 400px;
      object-fit: contain;
      border-radius: 12px;
      /*transform: rotate(5deg);*/
    }
    
    .product-title {
      font-size: 2.5rem;
      font-weight: 800;
      color: #1a3a3a;
      margin-bottom: 1rem;
      font-family: 'Poppins', sans-serif;
    }
    
    .product-price {
      font-size: 2.2rem;
      font-weight: 800;
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1.5rem;
    }
    
    .product-badge {
      display: inline-block;
      background: rgba(5, 150, 105, 0.1);
      color: #059669;
      padding: 0.6rem 1.2rem;
      border-radius: 25px;
      font-size: 0.9rem;
      font-weight: 600;
      margin-right: 0.75rem;
      margin-bottom: 1rem;
      border: 1.5px solid rgba(5, 150, 105, 0.2);
    }
    
    .info-section {
      background: #ffffff;
      padding: 1.5rem;
      border-radius: 15px;
      margin-bottom: 1.5rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .info-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.2rem;
      padding-bottom: 1.2rem;
      border-bottom: 1px solid #e5e7eb;
    }
    
    .info-item:last-child {
      margin-bottom: 0;
      padding-bottom: 0;
      border-bottom: none;
    }
    
    .info-icon {
      font-size: 1.3rem;
      color: #059669;
      margin-right: 1rem;
      min-width: 30px;
    }
    
    .info-label {
      font-weight: 700;
      color: #4b7c7c;
      font-size: 0.95rem;
      min-width: 100px;
    }
    
    .info-value {
      color: #1a3a3a;
      font-weight: 500;
      font-size: 0.95rem;
    }
    
    .description-section {
      background: #ffffff;
      padding: 1.5rem;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      display: none;
    }
    
    .description-title {
      font-weight: 700;
      color: #1a3a3a;
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }
    
    .description-text {
      color: #4b7c7c;
      line-height: 1.6;
      font-size: 0.95rem;
    }
    
    .quantity-control {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin: 2rem 0;
    }
    
    .quantity-btn {
      background: #ffffff;
      border: 2px solid #e5e7eb;
      color: #059669;
      width: 45px;
      height: 45px;
      border-radius: 10px;
      font-size: 1.3rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .quantity-btn:hover {
      background: #059669;
      color: white;
      border-color: #059669;
    }
    
    .quantity-input {
      width: 70px;
      height: 45px;
      border: 2px solid #e5e7eb;
      border-radius: 10px;
      text-align: center;
      font-size: 1.1rem;
      font-weight: 700;
      color: #1a3a3a;
    }
    
    .quantity-input:focus {
      outline: none;
      border-color: #059669;
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    
    .action-buttons {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
    }
    
    .btn-add-to-cart {
      flex: 1;
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      color: white;
      border: none;
      padding: 1rem 2rem;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.05rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.3);
    }
    
    .btn-add-to-cart:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(5, 150, 105, 0.4);
      color: white;
    }
    
    .btn-ai-chat {
      background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
      color: white;
      border: none;
      padding: 1rem 1.5rem;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.05rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.75rem;
      white-space: nowrap;
      box-shadow: 0 8px 25px rgba(6, 182, 212, 0.3);
    }
    
    .btn-ai-chat:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(6, 182, 212, 0.4);
      color: white;
    }
    
    .msg-box {
      padding: 1rem;
      border-radius: 10px;
      margin-bottom: 1rem;
      font-weight: 600;
      display: none;
    }
    
    .msg-success {
      background: #d1fae5;
      color: #065f46;
      display: block;
    }
    
    .msg-error {
      background: #fee2e2;
      color: #991b1b;
      display: block;
    }
    
    @media (max-width: 768px) {
      .product-title {
        font-size: 1.8rem;
      }
      
      .product-price {
        font-size: 1.8rem;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .btn-ai-chat {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="container py-5">
  <div class="product-hero">
    <div class="row g-5 align-items-start">
      <!-- Product Image -->
      <div class="col-lg-5">
        <div class="product-image-container">
          <img class="product-image" 
               src="<?= UPLOAD_BASE_URL . htmlspecialchars($productData['product_image'] ?: 'placeholder.png') ?>" 
               alt="<?= htmlspecialchars($productData['product_title']) ?>">
        </div>
      </div>
      
      <!-- Product Details -->
      <div class="col-lg-7">
        <h1 class="product-title"><?= htmlspecialchars($productData['product_title']) ?></h1>
        
        <div class="product-price">₵<?= number_format($productData['product_price'], 2) ?></div>
        
        <!-- Badges -->
        <div class="mb-4">
          <span class="product-badge">
            <i class="fas fa-tag"></i> <?= htmlspecialchars($productData['cat_name'] ?? 'Uncategorized') ?>
          </span>
          <span class="product-badge">
            <i class="fas fa-trademark"></i> <?= htmlspecialchars($productData['brand_name'] ?? 'Generic') ?>
          </span>
        </div>
        
        <!-- All Info in One Card -->
        <div class="info-section">
          <!-- Info Items -->
          <div class="info-item">
            <div class="info-icon"><i class="fas fa-building"></i></div>
            <div>
              <div class="info-label">Company</div>
              <div class="info-value"><?= htmlspecialchars($productData['company_name'] ?? 'Not specified') ?></div>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon"><i class="fas fa-folder-open"></i></div>
            <div>
              <div class="info-label">Category</div>
              <div class="info-value"><?= htmlspecialchars($productData['cat_name'] ?? 'Uncategorized') ?></div>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <div class="info-label">Location</div>
              <div class="info-value"><?= htmlspecialchars($productData['location'] ?? 'Not specified') ?></div>
            </div>
          </div>
          
          <div class="info-item">
            <div class="info-icon"><i class="fas fa-barcode"></i></div>
            <div>
              <div class="info-label">Brand</div>
              <div class="info-value"><?= htmlspecialchars($productData['brand_name'] ?? 'Generic') ?></div>
            </div>
          </div>
          
          <!-- Description Inside Same Card -->
          <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <div class="description-title" style="margin-bottom: 0.75rem;">About this product</div>
            <div class="description-text">
              <?= nl2br(htmlspecialchars($productData['product_desc'])) ?>
            </div>
            <?php if (!empty($productData['product_keywords'])): ?>
              <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <strong style="color: #4b7c7c;">Keywords:</strong>
                <div style="color: #1a3a3a; margin-top: 0.5rem;">
                  <?= htmlspecialchars($productData['product_keywords']) ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        
        <!-- Quantity Control -->
        <div class="quantity-control">
          <button class="quantity-btn" onclick="decreaseQty()"><i class="fas fa-minus"></i></button>
          <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="100">
          <button class="quantity-btn" onclick="increaseQty()"><i class="fas fa-plus"></i></button>
        </div>
        
        <!-- Message Box -->
        <div id="cartMsg" class="msg-box"></div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
          <button class="btn-add-to-cart" data-product-id="<?= $productData['p_id'] ?>" onclick="addToCart(this)">
            <i class="fas fa-shopping-cart"></i> Add to Cart
          </button>
          <button class="btn-ai-chat">
            <i class="fas fa-comments"></i> Chat with AI
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function increaseQty() {
    const input = document.getElementById('quantity');
    input.value = Math.min(100, parseInt(input.value || 1) + 1);
  }
  
  function decreaseQty() {
    const input = document.getElementById('quantity');
    input.value = Math.max(1, parseInt(input.value || 1) - 1);
  }
  
  function addToCart(btn) {
    const productId = btn.getAttribute('data-product-id');
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    const msgBox = document.getElementById('cartMsg');
    
    fetch('../actions/add_to_cart_action.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'p_id=' + encodeURIComponent(productId) + '&qty=' + encodeURIComponent(quantity)
    })
    .then(res => res.json())
    .then(data => {
      msgBox.className = 'msg-box ' + (data.status === 'success' ? 'msg-success' : 'msg-error');
      msgBox.innerHTML = data.message;
      msgBox.style.display = 'block';
      
      setTimeout(() => {
        msgBox.style.display = 'none';
      }, 3000);
    })
    .catch(err => {
      msgBox.className = 'msg-box msg-error';
      msgBox.innerHTML = 'Error adding to cart';
      msgBox.style.display = 'block';
    });
  }
</script>

</body>
</html>
