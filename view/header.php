<?php require_once __DIR__ . '/../settings/core.php'; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">Med-ePharmacy</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-center">
        <?php if (!isLoggedIn()): ?>
          <li class="nav-item"><a class="nav-link" href="../Login/register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="../Login/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/view/cart.php">Cart</a></li>
        <?php else: ?>
          <li class="nav-item">
            <form method="post" action="../Login/logout.php" class="d-inline">
              <button class="btn btn-link nav-link" type="submit">Logout</button>
            </form>
          </li>
          <?php if (isAdmin()): ?>
            <li class="nav-item"><a class="nav-link" href="../admin/category.php">Category</a></li>
            <li class="nav-item"><a class="nav-link" href="../admin/brand.php">Brand</a></li>
            <li class="nav-item">
              <a class="nav-link btn btn-sm btn-success text-white ms-2" href="../admin/product_add.php">
                Add Product
              </a>
            </li>
          <?php endif; ?>
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
