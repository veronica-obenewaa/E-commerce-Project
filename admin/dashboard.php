<?php
require_once __DIR__ . '/../settings/core.php';

// Only allow logged in physicians (role_id = 3 expected)
if (!isLoggedIn() || getUserRole() != 3) {
    header('Location: ../Login/login.php');
    exit;
}

$physician_name = $_SESSION['customer_name'] ?? 'Physician';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Physician Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    body { background-color: #f5f5f5; }
    .sidebar { background: white; border-right: 1px solid #ddd; }
    .sidebar .nav-link { color: #333; border-left: 3px solid transparent; padding: 12px 20px; }
    .sidebar .nav-link:hover { background-color: #f8f9fa; }
    .sidebar .nav-link.active { background-color: #e8f5e9; border-left-color: #0b6623; color: #0b6623; font-weight: 600; }
    .card { border: none; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .card-header { background-color: #f8f9fa; border-bottom: 1px solid #dee2e6; }
    .stat-card { text-align: center; padding: 20px; }
    .stat-number { font-size: 2rem; font-weight: 700; color: #0b6623; }
    .stat-label { color: #666; font-size: 0.9rem; }
    .btn-primary { background-color: #0b6623; border-color: #0b6623; }
    .btn-primary:hover { background-color: #09531d; border-color: #09531d; }
  </style>
</head>
<body>

<?php include __DIR__ . '/../view/admin_header.php'; ?>

<div class="container-fluid">
  <div class="row" style="min-height: 90vh;">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 p-3 sidebar" style="background:white; border-right:1px solid #ddd;">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a class="nav-link active" href="#" onclick="showSection('dashboard')">
            <i class="fas fa-chart-line"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="showSection('profile')">
            <i class="fas fa-user-md"></i> Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="showSection('bookings')">
            <i class="fas fa-calendar-check"></i> My Bookings
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 p-4">

      <!-- Dashboard Section -->
      <div id="dashboard" class="section">
        <div class="row mb-4">
          <div class="col-12">
            <h2 class="mb-4"><i class="fas fa-chart-line"></i> Dashboard</h2>
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">Profile Summary</h5>
              </div>
              <div class="card-body">
                <p><strong>Name:</strong> <span id="summary_name"><?php echo htmlspecialchars($physician_name); ?></span></p>
                <p><strong>Hospital:</strong> <span id="summary_hospital">-</span></p>
                <p><strong>Registration #:</strong> <span id="summary_hospital_reg">-</span></p>
                <p><strong>Contact:</strong> <span id="summary_contact">-</span></p>
                <a href="#" onclick="showSection('profile')" class="btn btn-outline-primary btn-sm">Edit Profile</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-12 mb-3">
                <div class="card stat-card text-center p-3">
                  <div class="stat-number" id="appt-count">0</div>
                  <div class="stat-label">Appointments</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header"><h5 class="mb-0">Quick Actions</h5></div>
              <div class="card-body">
                <a href="#" onclick="showSection('bookings')" class="btn btn-primary me-2"><i class="fas fa-list"></i> View Bookings</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Profile Section -->
      <div id="profile" class="section" style="display:none;">
        <h2 class="mb-4"><i class="fas fa-user-md"></i> Profile</h2>
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <form id="physProfileForm">
                  <div class="mb-3">
                    <label for="customer_name" class="form-label">Full name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                  </div>
                  <div class="mb-3">
                    <label for="hospital_name" class="form-label">Hospital Name</label>
                    <input type="text" class="form-control" id="hospital_name" name="hospital_name" required>
                  </div>
                  <div class="mb-3">
                    <label for="hospital_registration_number" class="form-label">Hospital Registration Number</label>
                    <input type="text" class="form-control" id="hospital_registration_number" name="hospital_registration_number" required>
                  </div>
                  <div class="mb-3">
                    <label for="customer_country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="customer_country" name="customer_country" required>
                  </div>
                  <div class="mb-3">
                    <label for="customer_city" class="form-label">City</label>
                    <input type="text" class="form-control" id="customer_city" name="customer_city" required>
                  </div>
                  <div class="mb-3">
                    <label for="customer_contact" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="customer_contact" name="customer_contact" required>
                  </div>
                  <div id="profileMsg"></div>
                  <button class="btn btn-primary" id="saveProfileBtn">Update Profile</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bookings Section -->
      <div id="bookings" class="section" style="display:none;">
        <h2 class="mb-4"><i class="fas fa-calendar-check"></i> My Bookings</h2>
        <div id="bookingsMsg"></div>
        <div id="bookingsList" class="list-group"></div>
      </div>

    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/physician_dashboard.js"></script>
</body>
</html>
