<?php require_once __DIR__ . '/../settings/core.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">Med-ePharmacy</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="all_product.php">All Products</a></li>
        <li class="nav-item"><a class="nav-link" href="book_consultation.php">Contact Physician</a></li>
        <?php if (!isLoggedIn()): ?>
          <li class="nav-item"><a class="nav-link" href="../Login/register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="../Login/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
        <?php else: ?>
          <?php if (isCustomer() || getUserRole() == 2): ?>
            <li class="nav-item"><a class="nav-link" href="user_dashboard.php">My Dashboard</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
          <li class="nav-item">
            <form method="post" action="../Login/logout.php" class="d-inline">
              <button class="btn btn-link nav-link" type="submit">Logout</button>
            </form>
          </li>
        <?php endif; ?>
      </ul>

      <form id="navSearchForm" class="d-flex ms-3" onsubmit="return false;">
        <input id="navSearchInput" class="form-control me-2" placeholder="Search medicines, e.g. Paracetamol" type="search" aria-label="Search">
        <button id="navSearchBtn" class="btn btn-outline-primary" type="button">Search</button>
      </form>
    </div>
  </div>
</nav>

<script>
// Attach event listener safely after DOM is ready
document.addEventListener('DOMContentLoaded', function() {
  const searchBtn = document.getElementById('navSearchBtn');
  const searchInput = document.getElementById('navSearchInput');
  if (searchBtn && searchInput) {
    searchBtn.addEventListener('click', () => {
      const query = searchInput.value.trim();
      if (!query) return;
      // Redirect to search results page
      window.location.href = 'product_search_result.php?q=' + encodeURIComponent(query);
    });
  }
});
</script>
