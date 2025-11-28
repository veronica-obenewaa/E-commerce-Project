<?php
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
  <title>All Products - Med-ePharmacy</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body { 
      font-family: 'Poppins', 'Inter', system-ui, Arial, sans-serif; 
      background:#f7f9fc; 
    }

    /* Carousel Slideshow Styles */
    .carousel-section {
      margin-bottom: 48px;
    }

    .carousel-wrapper {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 16px 48px rgba(11, 102, 35, 0.15);
      max-height: 500px;
    }

    .carousel-item {
      height: 500px;
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
      overflow: hidden;
    }

    .carousel-item img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }

    .carousel-overlay {
      position: absolute;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(11, 102, 35, 0.4) 0%, rgba(20, 168, 81, 0.3) 100%);
      z-index: 2;
    }

    .carousel-content {
      position: relative;
      z-index: 3;
      text-align: center;
      color: white;
      max-width: 600px;
      padding: 40px;
      animation: slideInUp 0.8s ease-out;
    }

    .carousel-content h2 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 16px;
      text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      letter-spacing: -1px;
    }

    .carousel-content p {
      font-size: 1.25rem;
      margin-bottom: 24px;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      font-weight: 500;
    }

    .carousel-btn {
      background: linear-gradient(135deg, #0b6623 0%, #14a851 100%);
      color: white;
      padding: 12px 32px;
      border: none;
      border-radius: 8px;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(11, 102, 35, 0.3);
      text-decoration: none;
      display: inline-block;
    }

    .carousel-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(11, 102, 35, 0.4);
      background: linear-gradient(135deg, #14a851 0%, #1cd765 100%);
      color: white;
    }

    .carousel-indicators-custom {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 10px;
      z-index: 4;
    }

    .carousel-indicator-dot {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      transition: all 0.3s ease;
      border: 2px solid white;
    }

    .carousel-indicator-dot.active {
      background: white;
      width: 32px;
      border-radius: 6px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .carousel-nav-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255, 255, 255, 0.2);
      color: white;
      border: none;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 4;
      transition: all 0.3s ease;
      font-size: 1.5rem;
    }

    .carousel-nav-btn:hover {
      background: rgba(255, 255, 255, 0.4);
      transform: translateY(-50%) scale(1.1);
    }

    .carousel-nav-btn.prev {
      left: 20px;
    }

    .carousel-nav-btn.next {
      right: 20px;
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Filter Section */
    .filter-section {
      background: white;
      padding: 32px 0;
      margin-bottom: 32px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .filter-title {
      font-size: 1.25rem;
      font-weight: 700;
      margin-bottom: 24px;
      color: #0b6623;
    }

    .filter-control {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      align-items: center;
    }

    .filter-group {
      flex: 1;
      min-width: 250px;
    }

    .filter-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
      font-size: 0.9rem;
    }

    .filter-group select,
    .filter-group input {
      width: 100%;
      padding: 10px 14px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      font-family: inherit;
    }

    .filter-group select:focus,
    .filter-group input:focus {
      outline: none;
      border-color: #0b6623;
      box-shadow: 0 0 0 3px rgba(11, 102, 35, 0.1);
    }

    .product-card { 
      transition: transform .12s ease, box-shadow .12s ease; 
      border: none; 
      border-radius: 10px; 
      background: white; 
    }

    .product-card:hover { 
      transform: translateY(-4px); 
      box-shadow: 0 10px 25px rgba(15, 15, 15, .08); 
    }

    .price { 
      font-weight: 700; 
      color: #0b6623; 
      font-size: 1.25rem;
    }

    .badge-cat { 
      background: rgba(11, 102, 35, 0.06); 
      color: #0b6623; 
      border-radius: 6px; 
      padding: 4px 8px; 
      font-size: .75rem; 
      font-weight: 600;
    }

    .search-input { 
      max-width: 640px; 
    }

    .results-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      padding: 0 12px;
    }

    .results-count {
      font-weight: 600;
      color: #0b6623;
    }

    @media (max-width: 768px) {
      .carousel-item {
        height: 300px;
      }

      .carousel-content h2 {
        font-size: 1.8rem;
      }

      .carousel-content p {
        font-size: 1rem;
      }

      .filter-control {
        flex-direction: column;
      }

      .filter-group {
        min-width: 100%;
      }

      .carousel-nav-btn {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
      }

      .carousel-nav-btn.prev {
        left: 10px;
      }

      .carousel-nav-btn.next {
        right: 10px;
      }
    }
  </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<!-- Carousel Slideshow Section -->
<section class="carousel-section">
  <div class="container-fluid px-0">
    <div class="carousel-wrapper">
      <div id="productsCarousel" class="carousel" style="position: relative;">
        <!-- Slide 1 - Capsules -->
        <div class="carousel-item active">
          <img src="http://169.239.251.102:442/~veronica.obenewaa/uploads/gel-capsules-206150_1280.jpg" alt="Quality Medicines" style="width: 100%; height: 100%; object-fit: cover;">
          <div class="carousel-overlay"></div>
          <div class="carousel-content">
            <h2>Your Health, Our Priority</h2>
            <p>Access quality medications from trusted Ghanaian pharmaceutical partners</p>
            <a href="all_product.php" class="carousel-btn">Shop Now</a>
          </div>
        </div>

        <!-- Slide 2 - Thermometer/Blood Sugar -->
        <div class="carousel-item">
          <img src="http://169.239.251.102:442/~veronica.obenewaa/uploads/blood-sugar-meter-diabetes-1000x778.jpg" alt="Health Monitoring" style="width: 100%; height: 100%; object-fit: cover;">
          <div class="carousel-overlay"></div>
          <div class="carousel-content">
            <h2>Expert Physician Consultation</h2>
            <p>Connect with licensed healthcare professionals online anytime, anywhere</p>
            <a href="book_consultation.php" class="carousel-btn">Book Appointment</a>
          </div>
        </div>

        <!-- Slide 3 - Vitamins/Supplements -->
        <div class="carousel-item">
          <img src="http://169.239.251.102:442/~veronica.obenewaa/uploads/vitamin-tablets-and-bottles-on-a-white-surface.jpg" alt="Vitamins & Supplements" style="width: 100%; height: 100%; object-fit: cover;">
          <div class="carousel-overlay"></div>
          <div class="carousel-content">
            <h2>Convenient Delivery to Your Door</h2>
            <p>Same-day delivery available in selected areas. Safe and discreet packaging</p>
            <a href="all_product.php" class="carousel-btn">Browse Products</a>
          </div>
        </div>

        <!-- Slide 4 - Medicine Syringe -->
        <div class="carousel-item">
          <img src="http://169.239.251.102:442/~veronica.obenewaa/uploads/medical-thermometer-and-pills-on-white-background.jpg" alt="Healthcare Solutions" style="width: 100%; height: 100%; object-fit: cover;">
          <div class="carousel-overlay"></div>
          <div class="carousel-content">
            <h2>Healthcare That Fits Your Budget</h2>
            <p>Competitive pricing on all medications with special discounts for regular customers</p>
            <a href="all_product.php" class="carousel-btn">Start Saving</a>
          </div>
        </div>

        <!-- Slide 5 - Global Pills -->
        <div class="carousel-item">
          <img src="http://169.239.251.102:442/~veronica.obenewaa/uploads/medicin-world-map-1920x1440.jpg" alt="Global Healthcare" style="width: 100%; height: 100%; object-fit: cover;">
          <div class="carousel-overlay"></div>
          <div class="carousel-content">
            <h2>Quality Medications Worldwide</h2>
            <p>Partner with the most trusted pharmaceutical suppliers across Africa</p>
            <a href="all_product.php" class="carousel-btn">Explore More</a>
          </div>
        </div>

        <!-- Navigation Buttons -->
        <button class="carousel-nav-btn prev" onclick="changeSlide(-1)">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-nav-btn next" onclick="changeSlide(1)">
          <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Indicators -->
        <div class="carousel-indicators-custom">
          <span class="carousel-indicator-dot active" onclick="currentSlide(0)"></span>
          <span class="carousel-indicator-dot" onclick="currentSlide(1)"></span>
          <span class="carousel-indicator-dot" onclick="currentSlide(2)"></span>
          <span class="carousel-indicator-dot" onclick="currentSlide(3)"></span>
          <span class="carousel-indicator-dot" onclick="currentSlide(4)"></span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Filter Section -->
<div class="container">
  <div class="filter-section">
    <h3 class="filter-title">
      <i class="fas fa-filter"></i> Filter Products
    </h3>
    <div class="filter-control">
      <div class="filter-group">
        <label for="filterCategory">Category</label>
        <select id="filterCategory" class="form-select">
          <option value="0">All Categories</option>
          <?php foreach($categories as $cat): ?>
            <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-group">
        <label for="filterBrand">Brand</label>
        <select id="filterBrand" class="form-select">
          <option value="0">All Brands</option>
          <?php foreach($brands as $b): ?>
            <option value="<?= $b['brand_id'] ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-group">
        <label for="globalSearch"><i class="fas fa-search"></i> Search Products</label>
        <input id="globalSearch" class="form-control" placeholder="Search by name, symptom or keyword..." />
      </div>
      <div class="filter-group">
        <label for="sortBy">Sort By</label>
        <select id="sortBy" class="form-select">
          <option value="newest">Newest First</option>
          <option value="price_asc">Price: Low to High</option>
          <option value="price_desc">Price: High to Low</option>
          <option value="popular">Most Popular</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Results Info -->
  <div class="results-info">
    <span class="results-count"><i class="fas fa-box"></i> <span id="resultsInfo">Loading...</span></span>
  </div>

  <!-- Products Grid -->
  <div id="productsGrid" class="row g-4 mb-5">
    <?php if(count($products) > 0): ?>
      <?php foreach($products as $p): ?>
        <div class="col-md-3 col-sm-6">
          <div class="card product-card h-100"> 
            <img src="<?= UPLOAD_BASE_URL . htmlspecialchars($p['product_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['product_title']) ?>" style="height: 250px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <span class="badge-cat mb-2"><?= htmlspecialchars($p['cat_name'] ?? 'Uncategorized') ?></span>
              <h6 class="card-title" style="flex-grow: 1;"><?= htmlspecialchars($p['product_title']) ?></h6>
              <p class="price mb-1">GHS <?= number_format($p['product_price'],2) ?></p>
              <p class="text-muted small mb-3"><?= htmlspecialchars($p['brand_name'] ?? '') ?></p>
              <div id="cartMsg-<?= $p['product_id'] ?>" class="mb-2"></div>
              <button class="btn btn-outline-success w-100 btn-add-to-cart mt-auto" data-product-id="<?= $p['product_id'] ?>" data-qty="1">
                <i class="fas fa-cart-plus"></i> Add to Cart
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center py-5" role="alert">
          <i class="fas fa-info-circle"></i> No products available yet. Check back soon!
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Carousel JavaScript -->
<script>
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.carousel-item');
const totalSlides = slides.length;
let autoSlideInterval;

function showSlide(n) {
  slides.forEach(slide => slide.classList.remove('active'));
  const dots = document.querySelectorAll('.carousel-indicator-dot');
  dots.forEach(dot => dot.classList.remove('active'));
  
  if (n >= totalSlides) currentSlideIndex = 0;
  if (n < 0) currentSlideIndex = totalSlides - 1;
  
  slides[currentSlideIndex].classList.add('active');
  dots[currentSlideIndex].classList.add('active');
}

function changeSlide(n) {
  currentSlideIndex += n;
  showSlide(currentSlideIndex);
  resetAutoSlide();
}

function currentSlide(n) {
  currentSlideIndex = n;
  showSlide(currentSlideIndex);
  resetAutoSlide();
}

function autoSlide() {
  currentSlideIndex++;
  showSlide(currentSlideIndex);
}

function resetAutoSlide() {
  clearInterval(autoSlideInterval);
  autoSlideInterval = setInterval(autoSlide, 6000);
}

// Initialize carousel
showSlide(currentSlideIndex);
autoSlideInterval = setInterval(autoSlide, 6000);
</script>

<script src="../js/view_product.js"></script>
<script src="../js/cart.js"></script>

</body>
</html>
