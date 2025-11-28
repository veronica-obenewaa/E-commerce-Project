<?php require_once __DIR__ . '/../settings/core.php'; ?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #0b6623 0%, #14a851 100%);">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../index.php">
      <i class="fas fa-user-md"></i> Med-ePharma
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link text-white" href="../index.php">
            <i class="fas fa-home"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <?php
          $userRole = getUserRole();
          if ($userRole == 3): // Physician
            $dashboardLink = '../admin/dashboard.php';
            $dashboardText = 'Dashboard';
            $dashboardIcon = 'fa-stethoscope';
          elseif ($userRole == 1): // Pharmaceutical Company
            $dashboardLink = '../view/dashboard.php';
            $dashboardText = 'Dashboard';
            $dashboardIcon = 'fa-chart-line';
          else:
            $dashboardLink = '../index.php';
            $dashboardText = 'Dashboard';
            $dashboardIcon = 'fa-gauge';
          endif;
          ?>
          <a class="nav-link text-white" href="<?php echo $dashboardLink; ?>">
            <i class="fas <?php echo $dashboardIcon; ?>"></i> <?php echo $dashboardText; ?>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../admin/category_add.php">
            <i class="fas fa-tags"></i> Category
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../admin/brand_add.php">
            <i class="fas fa-certificate"></i> Brand
          </a>
        </li>
        <li class="nav-item">
          <span class="nav-link text-white">
            Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?>
          </span>
        </li>
        <li class="nav-item">
          <form method="post" action="../Login/logout.php" class="d-inline">
            <button class="btn btn-outline-light btn-sm" type="submit">
              <i class="fas fa-sign-out-alt"></i> Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>

