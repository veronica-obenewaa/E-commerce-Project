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
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="../index.php">Med-ePharma</a>
    <div class="d-flex">
      <div class="me-3">Welcome, <?php echo htmlspecialchars($physician_name); ?></div>
      <form method="post" action="../Login/logout.php"><button class="btn btn-outline-secondary btn-sm">Logout</button></form>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mb-3" id="physTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="bookings-tab" data-bs-toggle="tab" data-bs-target="#bookings" type="button" role="tab">Bookings</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile</button>
              </li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane fade show active" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
                <h5>Bookings</h5>
                <p class="text-muted">Below are upcoming and past bookings. Use the reason field for a short clinical note.</p>
                <div id="bookingsMsg"></div>
                <div id="bookingsList" class="list-group"></div>
              </div>

              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <h5 class="mt-3">Profile</h5>
                <div id="profileMsg"></div>
                <form id="physProfileForm" class="mt-3">
                  <div class="mb-3">
                    <label class="form-label">Full name</label>
                    <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Hospital Name</label>
                    <input type="text" name="hospital_name" id="hospital_name" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Hospital Registration Number</label>
                    <input type="text" name="hospital_registration_number" id="hospital_registration_number" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="customer_country" id="customer_country" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="customer_city" id="customer_city" class="form-control" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="customer_contact" id="customer_contact" class="form-control" required>
                  </div>
                  <button class="btn btn-primary" id="saveProfileBtn">Save Profile</button>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/physician_dashboard.js"></script>
</body>
</html>
