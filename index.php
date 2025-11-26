<?php
require_once __DIR__ . '/settings/core.php';
require_once __DIR__ . '/controllers/category_controller.php';
require_once __DIR__ . '/controllers/brand_controller.php';
include __DIR__ . '/view/header.php';



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
  <title>Med-ePharma | Medication & Teleconsultation Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap');
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body { 
      font-family: 'Inter', system-ui, sans-serif; 
      background: #000;
      overflow-x: hidden;
    }
    
    /* Hero Section with Background Image */
    .hero {
      min-height: 100vh;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Background Image */
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: url('http://169.239.251.102:442/~veronica.obenewaa/uploads/gel-capsules-206150_1280.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      filter: brightness(0.7);
      z-index: 1;
    }
    
    /* Gradient Overlay */
    .hero::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, 
        /*rgba(5, 150, 105, 0.23) 0%, 
        rgba(16, 185, 129, 0.50) 35%,
        rgba(52, 211, 153, 0.37) 70%,
        rgba(110, 231, 183, 0.42) 100%);*/

        rgba(5, 150, 105, 0.23) 0%, 
        rgba(1, 17, 12, 0.5) 35%,
        rgba(77, 97, 90, 0.37) 70%,
        rgba(32, 56, 47, 0.42) 100%);
      z-index: 2;
    }
    
    /* Additional gradient layers for depth */
    .gradient-layer {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 3;
      background: 
        radial-gradient(circle at 15% 20%, rgba(16, 185, 129, 0.4) 0%, transparent 40%),
        radial-gradient(circle at 85% 80%, rgba(52, 211, 153, 0.4) 0%, transparent 40%),
        radial-gradient(circle at 50% 50%, rgba(5, 150, 105, 0.3) 0%, transparent 50%);
      pointer-events: none;
    }
    
    .hero-content {
      position: relative;
      z-index: 10;
      text-align: center;
      color: white;
      padding: 2rem;
    }
    
    /* Top Left Icon */
    .top-icon {
      position: absolute;
      top: 3rem;
      left: 3rem;
      z-index: 10;
      font-size: 3rem;
      color: rgba(255, 255, 255, 0.3);
    }
    
    /* Brand Title */
    .brand-title {
      font-size: 3rem;
      font-weight: 500;
      margin-bottom: 1rem;
      background: linear-gradient(to right, #ffffff 0%, rgba(255, 255, 255, 0.9) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      text-shadow: 0 0 40px rgba(255, 255, 255, 0.3);
    }
    
    .brand-separator {
      display: inline-block;
      margin: 0 1rem;
      opacity: 0.7;
    }
    
    .tagline {
      font-size: 2.5rem;
      font-weight: 400;
      line-height: 1.2;
      margin-bottom: 1.5rem;
    }
    
    .tagline-green {
      background: linear-gradient(135deg, #6ee7b7 0%, #34d399 50%, #10b981 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .tagline-yellow {
      background: linear-gradient(135deg, #fde047 0%, #facc15 50%, #eab308 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .hero-subtitle {
      font-size: 1.5rem;
      font-weight: 500;
      margin-bottom: 3rem;
      opacity: 0.95;
    }
    
    /* Statistics */
    .stats-container {
      display: flex;
      justify-content: center;
      gap: 4rem;
      margin-bottom: 3rem;
      flex-wrap: wrap;
    }
    
    .stat-item {
      text-align: center;
    }
    
    .stat-number {
      font-size: 2rem;
      font-weight: 900;
      display: block;
      margin-bottom: 0.25rem;
      background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.9) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .stat-label {
      font-size: 1rem;
      opacity: 0.9;
      font-weight: 500;
    }
    
    /* Buttons */
    .hero-buttons {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      flex-wrap: wrap;
      margin-bottom: 3rem;
    }
    
    .btn-hero {
      padding: 1.25rem 2.5rem;
      font-size: 1.1rem;
      font-weight: 700;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      border: none;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .btn-primary-hero {
      background: linear-gradient(135deg, #6ee7b7 0%, #10b981 100%);
      color: #064e3b;
    }
    
    .btn-primary-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(16, 185, 129, 0.4);
      color: #064e3b;
    }
    
    .btn-secondary-hero {
      background: rgba(255, 255, 255, 0.15);
      color: white;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .btn-secondary-hero:hover {
      background: rgba(255, 255, 255, 0.25);
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(255, 255, 255, 0.2);
      color: white;
    }
    
    /* Features */
    .features-container {
      display: flex;
      justify-content: center;
      gap: 1.5rem;
      flex-wrap: wrap;
    }
    
    .feature-badge {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      color: white;
      padding: 0.875rem 1.75rem;
      border-radius: 50px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      border: 1px solid rgba(255, 255, 255, 0.3);
      font-size: 1.05rem;
    }
    
    .feature-badge i {
      font-size: 1.3rem;
      color: #fde047;
    }
    
    /* Filter Section */
    .filter-section {
      background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%);
      padding: 4rem 0;
      position: relative;
      z-index: 5;
    }
    
    .filter-title {
      font-size: 2rem;
      font-weight: 800;
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 2.5rem;
    }
    
    .form-select {
      border-radius: 12px;
      border: 2px solid #e5e7eb;
      padding: 1rem 1.25rem;
      font-size: 1.05rem;
      font-weight: 500;
      transition: all 0.3s ease;
      background: white;
    }
    
    .form-select:focus {
      border-color: #10b981;
      box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    
    .btn-filter {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%);
      color: white;
      border: none;
      padding: 1rem 2.5rem;
      font-weight: 700;
      border-radius: 12px;
      font-size: 1.05rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);
    }
    
    .btn-filter:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(5, 150, 105, 0.4);
    }
    
    /* Footer */
    footer {
      background: linear-gradient(135deg, #f8fafb 0%, #ffffff 100%);
      padding: 2rem 0;
      border-top: 1px solid #e5e7eb;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .brand-title {
        font-size: 2.5rem;
      }
      
      .tagline {
        font-size: 2rem;
      }
      
      .hero-subtitle {
        font-size: 1.1rem;
      }
      
      .stat-number {
        font-size: 2rem;
      }
      
      .stats-container {
        gap: 2rem;
      }
      
      .btn-hero {
        width: 100%;
        justify-content: center;
      }
      
      .top-icon {
        top: 1.5rem;
        left: 1.5rem;
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
<!-- Hero Section -->
<section class="hero">
  <!-- Background capsule image will be added via inline style -->
  <div class="gradient-layer"></div>
  
  <!-- Top Left Icon -->
  <div class="top-icon">
    <i class="fas fa-notes-medical"></i>
  </div>
  
  <div class="hero-content">
    <!-- Statistics -->
    <div class="stats-container">
      <div class="stat-item">
        <span class="stat-number" data-target="10000">0</span>
        <span class="stat-label">• Physicians</span>
      </div>
      <div class="stat-item">
        <span class="stat-number" data-target="50000">0</span>
        <span class="stat-label">• Medications</span>
      </div>
      <div class="stat-item">
        <span class="stat-number" data-target="1000">0</span>
        <span class="stat-label">• Pharmaceutical Companies</span>
      </div>
    </div>
    
    <!-- Main Title -->
    <h1 class="brand-title">
      Med-ePharma Ghana <span class="brand-separator">—</span>
    </h1>
    <h2 class="tagline">
      <span class="tagline-green">Trusted, Genuine & Verified</span><br>
      <!--<span class="tagline-yellow">Verified</span>-->
    </h2>
    
    <p class="hero-subtitle">Your Gateway to Quality Healthcare & Medication</p>
    
    <!-- Main Action Buttons -->
    <div class="hero-buttons">
      <a href="view/all_product.php" class="btn-hero btn-primary-hero">
        <i class="fas fa-th-large"></i>
        Browse Medications
      </a>
      <a href="Login/register_physician.php" class="btn-hero btn-secondary-hero">
        <i class="fas fa-user-md"></i>
        Physician
      </a>
      <a href="Login/register_company.php" class="btn-hero btn-secondary-hero">
        <i class="fas fa-building"></i>
        Pharmaceutical Company
      </a>
    </div>
    
    <!-- Feature Badges -->
    <div class="features-container">
      <div class="feature-badge">
        <i class="fas fa-check-circle"></i>
        Verified Meds
      </div>
      <div class="feature-badge">
        <i class="fas fa-stethoscope"></i>
        Experienced Doctors
      </div>
      <div class="feature-badge">
        <i class="fas fa-tag"></i>
        Affordable Meds
      </div>
    </div>
  </div>
</section>

<!-- About Us & Contact Us Section -->
<div class="filter-section">
  <div class="container">
    <h4 class="filter-title text-center mb-5">Explore Med-ePharma</h4>
    <div class="row g-4">
      <!-- About Us Card -->
      <div class="col-md-6">
        <div style="background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.07); height: 100%; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.07)'; this.style.transform='translateY(0)'">
          <div style="color: #10b981; font-size: 2.5rem; margin-bottom: 1rem;">
            <i class="fas fa-info-circle"></i>
          </div>
          <h5 style="color: #1f2937; font-weight: 800; margin-bottom: 1rem; font-size: 1.5rem;">About Med-ePharma</h5>
          <p style="color: #6b7280; line-height: 1.6; margin-bottom: 1.5rem;">
            Learn about our mission to revolutionize healthcare access in Ghana. Discover how Med-ePharma is connecting patients with certified pharmaceutical suppliers and licensed physicians.
          </p>
          <a href="view/about_us.php" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 0.875rem 1.75rem; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(5, 150, 105, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(5, 150, 105, 0.3)'">
            <i class="fas fa-arrow-right"></i> Learn More
          </a>
        </div>
      </div>

      <!-- Contact Us Card -->
      <div class="col-md-6">
        <div style="background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.07); height: 100%; transition: all 0.3s ease;" onmouseover="this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 6px rgba(0,0,0,0.07)'; this.style.transform='translateY(0)'">
          <div style="color: #10b981; font-size: 2.5rem; margin-bottom: 1rem;">
            <i class="fas fa-envelope"></i>
          </div>
          <h5 style="color: #1f2937; font-weight: 800; margin-bottom: 1rem; font-size: 1.5rem;">Get In Touch</h5>
          <p style="color: #6b7280; line-height: 1.6; margin-bottom: 1.5rem;">
            Have questions or need support? Our friendly team is here to help. Reach out to us and we'll respond as quickly as possible.
          </p>
          <a href="view/contact_us.php" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 0.875rem 1.75rem; border-radius: 8px; text-decoration: none; font-weight: 700; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(5, 150, 105, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(5, 150, 105, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(5, 150, 105, 0.3)'">
            <i class="fas fa-arrow-right"></i> Contact Us
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center">
  <div class="container">
    <small class="text-muted">&copy; <span id="year"></span> Med-ePharma. All Rights Reserved.</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Animated counter for statistics
  function animateCounter(element) {
    const target = parseInt(element.getAttribute('data-target'));
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        element.textContent = target.toLocaleString() + '+';
        clearInterval(timer);
      } else {
        element.textContent = Math.floor(current).toLocaleString() + '+';
      }
    }, 16);
  }
  
  // Trigger animation on page load
  window.addEventListener('load', () => {
    document.querySelectorAll('.stat-number').forEach(el => {
      animateCounter(el);
    });
  });
  
  // Set current year
  document.getElementById('year').textContent = new Date().getFullYear();
</script>
</body>
</html>