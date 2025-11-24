<?php require_once __DIR__ . '/../settings/core.php'; ?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

<style>
  .premium-navbar {
    background: linear-gradient(135deg, #0b6623 0%, #0d7a2f 100%);
    padding: 0.75rem 0;
    box-shadow: 0 4px 25px rgba(11, 102, 35, 0.25);
    border-bottom: 2px solid #ffffffff;
  }
  
  .premium-navbar .navbar-brand {
    font-family: 'Poppins', sans-serif;
    color: #ffffff;
    font-size: 1.45rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
  }
  
  .premium-navbar .navbar-brand:hover {
    transform: scale(1.05);
  }
  
  .brand-icon {
    font-size: 1.8rem;
    color: #ffffff;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  }
  
  .premium-navbar .nav-link {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    font-size: 0.95rem;
    padding: 0.6rem 0.9rem;
    margin: 0 0.2rem;
    border-radius: 6px;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    white-space: nowrap;
  }
  
  .premium-navbar .nav-link:hover {
    color: #ffffff;
    /*background: rgba(255, 255, 255, 0.15);*/
    transform: translateY(-2px);
  }
  
  .premium-navbar .nav-link.active {
    /*background: rgba(79, 70, 229, 0.3);*/
    color: #ffffff;
    /*border-bottom: 2px solid #4f46e5;*/
  }
  
  .premium-navbar .nav-icon {
    font-size: 1rem;
  }
  
  .search-wrapper {
    position: relative;
    margin-left: 1.5rem;
  }
  
  .search-toggle {
    background: transparent;
    border: none;
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    padding: 0.6rem 1rem;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.3s ease;
    z-index: 1000;
    position: relative;
  }
  
  .search-toggle:hover {
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
  }
  
  .search-input {
    background: rgba(255, 255, 255, 0.12);
    border: 1.5px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    padding: 0.6rem 2.5rem 0.6rem 1rem;
    border-radius: 25px;
    width: 0;
    opacity: 0;
    transition: all 0.4s ease;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
  }
  
  .search-input.expanded {
    width: 250px;
    opacity: 1;
  }
  
  .search-input:focus {
    background: rgba(255, 255, 255, 0.18);
    border-color: rgba(79, 70, 229, 0.6);
    color: #ffffff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
  }
  
  .search-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
  }
  
  .search-icon {
    position: absolute;
    right: 0.8rem;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 255, 255, 0.6);
    pointer-events: none;
    font-size: 0.9rem;
  }
  
  .logout-btn {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #ffffff;
    padding: 0.6rem 1.3rem;
    border-radius: 25px;
    border: none;
    font-weight: 600;
    font-size: 0.95rem;
    margin-left: 0.5rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
  }
  
  .logout-btn:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
  }
  
  .premium-navbar .navbar-toggler {
    border-color: rgba(255, 255, 255, 0.3);
    padding: 0.25rem 0.75rem;
  }
  
  .premium-navbar .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  }
  
  @media (max-width: 991px) {
    .premium-navbar .navbar-brand {
      font-size: 1.3rem;
    }
    
    .premium-navbar .nav-link {
      font-size: 0.9rem;
      padding: 0.5rem 0.8rem;
    }
    
    .search-wrapper {
      margin-left: 0;
      margin-top: 0.75rem;
      width: 100%;
      display: flex;
      justify-content: flex-start;
    }
    
    .search-input.expanded {
      width: calc(100% - 50px);
      position: relative;
      right: auto;
      transform: none;
    }
    
    .logout-btn {
      margin-left: 0;
      margin-top: 0.5rem;
      width: 100%;
      justify-content: center;
    }
  }
</style>

<nav class="navbar navbar-expand-lg premium-navbar">
  <div class="container">
    <a class="navbar-brand" href="../index.php">
      <i class="fas fa-heart-pulse brand-icon"></i>
      <span>Med-ePharma</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item">
          <a class="nav-link" href="../index.php">
            <i class="fas fa-home nav-icon"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="all_product.php">
            <i class="fas fa-pills nav-icon"></i> All Products
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="book_consultation.php">
            <i class="fas fa-user-doctor nav-icon"></i> Contact Physician
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about">
            <i class="fas fa-circle-info nav-icon"></i> About
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact">
            <i class="fas fa-envelope nav-icon"></i> Contact Us
          </a>
        </li>
        
        <?php if (!isLoggedIn()): ?>
          <li class="nav-item">
            <a class="nav-link" href="../Login/register.php">
              <i class="fas fa-user-plus nav-icon"></i> Register
            </a>
          </li>
        <?php else: ?>
          <?php if (isCustomer() || getUserRole() == 2): ?>
            <li class="nav-item">
              <a class="nav-link" href="user_dashboard.php">
                <i class="fas fa-gauge nav-icon"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="cart.php">
                <i class="fas fa-shopping-cart nav-icon"></i> Cart
              </a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <form method="post" action="../Login/logout.php" class="d-inline">
              <button class="logout-btn" type="submit">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </form>
          </li>
        <?php endif; ?>
      </ul>
      
      <div class="search-wrapper">
        <button class="search-toggle" id="searchToggle" type="button">
          <i class="fas fa-search"></i>
        </button>
        <input 
          id="navSearchInput" 
          class="search-input" 
          placeholder="Search medicines..." 
          type="search" 
          aria-label="Search">
        <i class="fas fa-search search-icon"></i>
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
      if (!e.target.closest('.search-wrapper')) {
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
