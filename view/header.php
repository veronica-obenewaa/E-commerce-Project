<?php require_once __DIR__ . '/../settings/core.php'; ?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<style>
  .modern-navbar {
    background: #0b6623;
    padding: 1rem 0;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
  }
  
  .modern-navbar .navbar-brand {
    color: #ffffff;
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: -0.5px;
  }
  
  .modern-navbar .nav-link {
    color: rgba(255, 255, 255, 0.85);
    font-weight: 500;
    padding: 0.5rem 1.25rem;
    margin: 0 0.25rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .modern-navbar .nav-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-1px);
  }
  
  .modern-navbar .nav-link.active {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
  }
  
  .modern-navbar .nav-icon {
    font-size: 1.1rem;
  }
  
  .modern-search-wrapper {
    position: relative;
    margin-left: 2rem;
  }
  
  .modern-search-toggle {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.85);
    font-size: 1.2rem;
    padding: 0.5rem 1rem;
    cursor: pointer;
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  
  .modern-search-toggle:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }
  
  .modern-search-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    padding: 0.6rem 2.75rem 0.6rem 1rem;
    border-radius: 25px;
    width: 0;
    opacity: 0;
    transition: all 0.4s ease;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
  }
  
  .modern-search-input.expanded {
    width: 280px;
    opacity: 1;
  }
  
  .modern-search-input:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    color: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
  }
  
  .modern-search-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
  }
  
  .modern-search-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.6);
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
  }
  
  .modern-search-input.expanded ~ .modern-search-icon {
    opacity: 1;
  }
  
  .modern-cta-btn {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
    color: #ffffff;
    padding: 0.6rem 1.5rem;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    margin-left: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .modern-cta-btn:hover {
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
  }
  
  .modern-navbar .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.3);
  }
  
  .modern-navbar .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.85)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  }
  
  .trust-badge {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.875rem;
    margin-left: 1.5rem;
  }
  
  @media (max-width: 991px) {
    .modern-search-wrapper {
      margin-left: 0;
      margin-top: 1rem;
      width: 100%;
      display: flex;
      justify-content: flex-start;
    }
    
    .modern-search-toggle {
      margin-left: 1rem;
    }
    
    .modern-search-input.expanded {
      width: calc(100% - 60px);
      position: relative;
      right: auto;
      transform: none;
      margin-left: 0.5rem;
    }
    
    .modern-cta-btn {
      margin-left: 0;
      margin-top: 0.5rem;
      width: 100%;
      justify-content: center;
    }
  }
</style>


<nav class="navbar navbar-expand-lg modern-navbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="../index.php">
      <i class="fas fa-pills" style="margin-right: 0.5rem;"></i>
      Med-ePharmacy
    </a>
    
    <span class="trust-badge d-none d-lg-inline">
      Trusted by 500+ medical practices
    </span>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link" href="all_product.php">
            <i class="fas fa-boxes nav-icon"></i> All Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="book_consultation.php">
            <i class="fas fa-user-md nav-icon"></i> Contact Physician
          </a>
        </li>
        
        <?php if (!isLoggedIn()): ?>
          <li class="nav-item">
            <a class="nav-link" href="../Login/register.php">
              <i class="fas fa-user-plus nav-icon"></i> Register
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../Login/login.php">
              <i class="fas fa-sign-in-alt nav-icon"></i> Login
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="cart.php">
              <i class="fas fa-shopping-cart nav-icon"></i> Cart
            </a>
          </li>
        <?php else: ?>
          <?php if (isCustomer() || getUserRole() == 2): ?>
            <li class="nav-item">
              <a class="nav-link" href="user_dashboard.php">
                <i class="fas fa-tachometer-alt nav-icon"></i> My Dashboard
              </a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link" href="cart.php">
              <i class="fas fa-shopping-cart nav-icon"></i> Cart
            </a>
          </li>
          <li class="nav-item">
            <form method="post" action="../Login/logout.php" class="d-inline">
              <button class="modern-cta-btn" type="submit">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </form>
          </li>
        <?php endif; ?>
      </ul>
      
      <div class="modern-search-wrapper">
        <button class="modern-search-toggle" id="searchToggle" type="button">
          <i class="fas fa-search"></i>
        </button>
        <input 
          id="navSearchInput" 
          class="modern-search-input" 
          placeholder="Search medicines..." 
          type="search" 
          aria-label="Search">
        <i class="fas fa-search modern-search-icon"></i>
      </div>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchToggle = document.getElementById('searchToggle');
  const searchInput = document.getElementById('navSearchInput');
  
  if (searchToggle && searchInput) {
    // Toggle search bar expansion
    searchToggle.addEventListener('click', () => {
      const isExpanded = searchInput.classList.contains('expanded');
      
      if (isExpanded) {
        searchInput.classList.remove('expanded');
        searchInput.blur();
      } else {
        searchInput.classList.add('expanded');
        searchInput.focus();
      }
    });
    
    // Close search bar when clicking outside
    document.addEventListener('click', (e) => {
      if (!e.target.closest('.modern-search-wrapper')) {
        searchInput.classList.remove('expanded');
      }
    });
    
    // Search on Enter key
    searchInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        const query = searchInput.value.trim();
        if (query) {
          window.location.href = 'product_search_result.php?q=' + encodeURIComponent(query);
        }
      }
    });
    
    // Add active state to current page nav link
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav-link').forEach(link => {
      const href = link.getAttribute('href');
      if (href && href.includes(currentPage)) {
        link.classList.add('active');
      }
    });
  }
});
</script>
