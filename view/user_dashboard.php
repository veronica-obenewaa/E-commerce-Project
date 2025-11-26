<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/booking_class.php';
require_once __DIR__ . '/../classes/customer_class.php';
require_once __DIR__ . '/../classes/order_class.php';

// Only allow logged in customers (role 2)
if (!isLoggedIn() || (!isCustomer() && getUserRole() != 2)) {
    header('Location: ../Login/login.php');
    exit;
}

$customer_id = getUserId();
$customer_name = $_SESSION['customer_name'] ?? 'User';
$customer_email = $_SESSION['customer_email'] ?? 'N/A';
$customer_city = $_SESSION['customer_city'] ?? 'N/A';
$customer_country = $_SESSION['customer_country'] ?? 'N/A';
$customer_contact = $_SESSION['customer_contact'] ?? 'N/A';

// Fetch user appointments
$bookingClass = new booking_class();
$appointments = $bookingClass->getBookingsByPatient($customer_id);

// Count appointments
$total_appointments = count($appointments);
$upcoming_appointments = array_filter($appointments, function($apt) {
    $status = $apt['status'] ?? 'scheduled';
    $datetime = $apt['appointment_datetime'] ?? null;
    return ($status === 'scheduled' && $datetime && strtotime($datetime) >= time());
});
$upcoming_count = count($upcoming_appointments);

// Fetch medication records (orders) - if order_class exists
$medication_records = [];
try {
    if (class_exists('order_class')) {
        $orderClass = new order_class();
        // Add method to get customer orders if it doesn't exist
        // For now, we'll just show empty array
    }
} catch (Exception $e) {
    // Order class might not have customer orders method yet
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .sidebar {
            background: white;
            border-right: 1px solid #ddd;
            min-height: calc(100vh - 56px);
        }
        .sidebar .nav-link {
            color: #333;
            border-left: 3px solid transparent;
            padding: 12px 20px;
        }
        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
        }
        .sidebar .nav-link.active {
            background-color: #e8f5e9;
            border-left-color: #0b6623;
            color: #0b6623;
            font-weight: 600;
        }
        .stat-card {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #0b6623;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .appointment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .appointment-header {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .doctor-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0b6623;
            margin-bottom: 0.5rem;
        }
        .info-item {
            color: #666;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .info-item i {
            color: #0b6623;
            width: 20px;
        }
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 600;
        }
        .btn-primary {
            background-color: #0b6623;
            border-color: #0b6623;
        }
        .btn-primary:hover {
            background-color: #09531d;
            border-color: #09531d;
        }
        .profile-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .section-title {
            color: #0b6623;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .zoom-link-section {
            background: linear-gradient(135deg, #e6f7ff 0%, #f0f8ff 100%);
            border-left: 4px solid #0066cc;
            padding: 1rem;
            border-radius: 6px;
            margin-top: 1rem;
        }
        .zoom-button {
            background: linear-gradient(135deg, #0066cc 0%, #004499 100%);
            border: none;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .zoom-button:hover {
            background: linear-gradient(135deg, #004499 0%, #003366 100%);
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 102, 204, 0.3);
        }
        .profile-row {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .profile-row:last-child {
            border-bottom: none;
        }
        .profile-label {
            font-weight: 600;
            color: #0b6623;
            width: 40%;
        }
        .profile-value {
            color: #333;
        }
        .modal-header {
            background: linear-gradient(135deg, #0b6623 0%, #09531d 100%);
        }
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<div class="container-fluid">
    <div class="row" style="min-height: calc(100vh - 56px);">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0 sidebar">
            <ul class="nav flex-column pt-3">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="book_consultation.php">
                        <i class="fas fa-calendar-check"></i> Book Consultation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="all_product.php">
                        <i class="fas fa-pills"></i> Medications
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4"><i class="fas fa-user"></i> User Dashboard</h2>

            <!-- Profile Section -->
            <div class="profile-section">
                <h5 class="section-title"><i class="fas fa-user-circle"></i> Profile Information</h5>
                <div class="profile-row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="profile-row"><span class="profile-label">Name:</span> <span class="profile-value"><?php echo htmlspecialchars($customer_name); ?></span></p>
                            <p class="profile-row"><span class="profile-label">Email:</span> <span class="profile-value"><?php echo htmlspecialchars($customer_email); ?></span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="profile-row"><span class="profile-label">Phone:</span> <span class="profile-value"><?php echo htmlspecialchars($customer_contact); ?></span></p>
                            <p class="profile-row"><span class="profile-label">City:</span> <span class="profile-value"><?php echo htmlspecialchars($customer_city); ?></span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="profile-row"><span class="profile-label">Country:</span> <span class="profile-value"><?php echo htmlspecialchars($customer_country); ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <a href="../view/all_product.php" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-shopping-cart"></i> View Medications
                    </a>
                    <a href="book_consultation.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-calendar-plus"></i> Book New Consultation
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_appointments; ?></div>
                        <div class="stat-label">Total Appointments</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $upcoming_count; ?></div>
                        <div class="stat-label">Upcoming Appointments</div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($medication_records); ?></div>
                        <div class="stat-label">Medication Records</div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="mb-4">
                <h5 class="section-title mb-3"><i class="fas fa-calendar-check"></i> Upcoming Appointments</h5>
                <?php if (empty($upcoming_appointments)): ?>
                    <div class="appointment-card text-center">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No upcoming appointments</p>
                        <a href="book_consultation.php" class="btn btn-primary">Book Your First Consultation</a>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($upcoming_appointments, 0, 5) as $appointment): 
                        $datetime = new DateTime($appointment['appointment_datetime']);
                        $date = $datetime->format('F j, Y');
                        $time = $datetime->format('g:i A');
                        $location = ($appointment['customer_city'] ?? '') . ', ' . ($appointment['customer_country'] ?? '');
                        $health_conditions = $appointment['health_conditions'] ?? '';
                        // Extract health conditions from reason_text if health_conditions column doesn't exist
                        if (empty($health_conditions) && !empty($appointment['reason_text'])) {
                            $parts = explode("Health Conditions:", $appointment['reason_text']);
                            if (count($parts) > 1) {
                                $health_conditions = trim(explode("\n\n", $parts[1])[0]);
                            }
                        }
                    ?>
                        <div class="appointment-card">
                            <div class="appointment-header">
                                <div class="doctor-name">
                                    <i class="fas fa-user-doctor"></i> Dr. <?php echo htmlspecialchars($appointment['physician_name'] ?? 'N/A'); ?>
                                </div>
                                <span class="badge bg-success"><?php echo ucfirst($appointment['status'] ?? 'scheduled'); ?></span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <i class="fas fa-hospital"></i> 
                                        <strong>Hospital:</strong> <?php echo htmlspecialchars($appointment['hospital_name'] ?? 'N/A'); ?>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <strong>Location:</strong> <?php echo htmlspecialchars($location); ?>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-calendar"></i> 
                                        <strong>Date:</strong> <?php echo $date; ?>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-clock"></i> 
                                        <strong>Time:</strong> <?php echo $time; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if (!empty($health_conditions)): ?>
                                        <div class="info-item">
                                            <i class="fas fa-heartbeat"></i> 
                                            <strong>Health Conditions:</strong>
                                            <p class="mt-2" style="font-size: 0.85rem; color: #555;">
                                                <?php echo nl2br(htmlspecialchars($health_conditions)); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Zoom Link Section -->
                            <?php if (!empty($appointment['zoom_join_url']) && ($appointment['zoom_status'] ?? 'pending') === 'created'): ?>
                                <div class="zoom-link-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-video"></i>
                                            <strong>Zoom Meeting Ready</strong>
                                            <?php if (!empty($appointment['zoom_password'])): ?>
                                                <p style="font-size: 0.85rem; margin: 0.5rem 0 0 0; color: #666;">
                                                    Password: <code><?php echo htmlspecialchars($appointment['zoom_password']); ?></code>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?php echo htmlspecialchars($appointment['zoom_join_url']); ?>" 
                                           target="_blank" 
                                           class="zoom-button" 
                                           title="Join Zoom meeting">
                                            <i class="fas fa-video"></i> Join Meeting
                                        </a>
                                    </div>
                                </div>
                            <?php elseif (($appointment['zoom_status'] ?? 'pending') === 'pending'): ?>
                                <div class="zoom-link-section">
                                    <i class="fas fa-hourglass-half"></i>
                                    <strong>Zoom Meeting Link</strong> - Pending creation
                                    <small class="d-block mt-2" style="color: #666;">The meeting link will be available shortly before your appointment.</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Medication Records Section -->
            <?php if (!empty($medication_records)): ?>
                <div class="mb-4">
                    <h5 class="section-title mb-3"><i class="fas fa-pills"></i> Medication Records</h5>
                    <div class="appointment-card text-center">
                        <p class="text-muted">Medication records feature coming soon...</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileLabel" style="color: white;">
                    <i class="fas fa-edit"></i> Edit Your Profile
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProfileForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="editName" name="customer_name" 
                               value="<?php echo htmlspecialchars($customer_name); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email (Read-only)</label>
                        <input type="email" class="form-control" id="editEmail" 
                               value="<?php echo htmlspecialchars($customer_email); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="editPhone" name="customer_contact" 
                               value="<?php echo htmlspecialchars($customer_contact); ?>" 
                               placeholder="+234 800 000 0000" required>
                        <small class="form-text text-muted">Include country code (e.g., +234, +1)</small>
                    </div>
                    <div class="mb-3">
                        <label for="editCity" class="form-label">City</label>
                        <input type="text" class="form-control" id="editCity" name="customer_city" 
                               value="<?php echo htmlspecialchars($customer_city); ?>" 
                               placeholder="Lagos, New York, etc.">
                    </div>
                    <div class="mb-3">
                        <label for="editCountry" class="form-label">Country</label>
                        <input type="text" class="form-control" id="editCountry" name="customer_country" 
                               value="<?php echo htmlspecialchars($customer_country); ?>" 
                               placeholder="Nigeria, USA, etc.">
                    </div>
                    <div id="editMessage" class="mb-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    
    const formData = new FormData(this);
    
    fetch('../actions/update_customer_profile_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const messageDiv = document.getElementById('editMessage');
        
        if (data.status === 'success') {
            messageDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' + data.message + '</div>';
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            messageDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</div>';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        }
    })
    .catch(error => {
        document.getElementById('editMessage').innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.</div>';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
        console.error('Error:', error);
    });
});
</script>
</body>
</html>

