<?php
// company/dashboard.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/customer_controller.php';

// Only allow pharmaceutical companies (role_id = 3)
if (!isLoggedIn() || getUserRole() != 3) {
    header('Location: ../Login/login.php');
    exit;
}

$customer_id = getUserId();
$customerCtrl = new CustomerController();

// Fetch company profile
$companyData = $customerCtrl->get_company_profile($customer_id);
if (!$companyData || $companyData['status'] !== 'success') {
    $company = null;
} else {
    $company = $companyData['data'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmaceutical Company Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; }
        .navbar { background: linear-gradient(135deg, #0b6623 0%, #14a851 100%); }
        .navbar-brand { font-weight: 700; color: white !important; }
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

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <i class="fas fa-pills"></i> Med-ePharma
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link text-white">Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'Company'); ?></span>
                </li>
                <li class="nav-item">
                    <form method="post" action="../Login/logout.php" class="d-inline">
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row" style="min-height: 90vh;">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="showSection('dashboard')">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showSection('profile')">
                        <i class="fas fa-user"></i> Company Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/product_add.php" onclick="showSection('add-medication')">
                        <i class="fas fa-pills"></i> Add Medications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/product.php" onclick="showSection('medications')">
                        <i class="fas fa-list"></i> My Medications
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

                <?php if ($company): ?>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Company Information</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Company Name:</strong> <?php echo htmlspecialchars($company['company_name']); ?></p>
                                    <p><strong>Registration Number:</strong> <?php echo htmlspecialchars($company['pharmaceutical_registration_number']); ?></p>
                                    <p><strong>Location:</strong> <?php echo htmlspecialchars($company['customer_city'] . ', ' . $company['customer_country']); ?></p>
                                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($company['customer_contact']); ?></p>
                                    <a href="#" onclick="showSection('profile')" class="btn btn-outline-primary btn-sm">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="card stat-card">
                                        <div class="stat-number" id="med-count">0</div>
                                        <div class="stat-label">Medications Listed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <a href="../admin/product_add.php" onclick="showSection('add-medication')" class="btn btn-primary me-2">
                                        <i class="fas fa-plus"></i> Add New Medication
                                    </a>
                                    <a href="../admin/product.php" onclick="showSection('medications')" class="btn btn-outline-primary">
                                        <i class="fas fa-list"></i> View All Medications
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Unable to load company profile. Please try again later.</div>
                <?php endif; ?>
            </div>

            <!-- Profile Section -->
            <div id="profile" class="section" style="display: none;">
                <h2 class="mb-4"><i class="fas fa-user"></i> Company Profile</h2>
                <?php if ($company): ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <form id="profileForm" method="POST" action="../actions/update_company_profile_action.php">
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label">Contact Person Name</label>
                                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($company['customer_name']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo htmlspecialchars($company['company_name']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="pharmaceutical_registration_number" class="form-label">Pharmaceutical Registration Number</label>
                                            <input type="text" class="form-control" id="pharmaceutical_registration_number" name="pharmaceutical_registration_number" value="<?php echo htmlspecialchars($company['pharmaceutical_registration_number']); ?>" required readonly>
                                            <small class="text-muted">Registration number cannot be changed</small>
                                        </div>

                                        <div class="mb-3">
                                            <label for="customer_country" class="form-label">Country</label>
                                            <input type="text" class="form-control" id="customer_country" name="customer_country" value="<?php echo htmlspecialchars($company['customer_country']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="customer_city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="customer_city" name="customer_city" value="<?php echo htmlspecialchars($company['customer_city']); ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="customer_contact" class="form-label">Contact Number</label>
                                            <input type="text" class="form-control" id="customer_contact" name="customer_contact" value="<?php echo htmlspecialchars($company['customer_contact']); ?>" required>
                                        </div>

                                        <div id="msg" class="mb-3"></div>

                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                        <a href="#" onclick="showSection('dashboard')" class="btn btn-outline-secondary">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">Unable to load company profile.</div>
                <?php endif; ?>
            </div>

            <!-- Add Medication Section -->
            <div id="add-medication" class="section" style="display: none;">
                <h2 class="mb-4"><i class="fas fa-plus-circle"></i> Add Medication</h2>
                <iframe src="../admin/product_add.php" style="width: 100%; height: 800px; border: none;"></iframe>
            </div>

            <!-- My Medications Section -->
            <div id="medications" class="section" style="display: none;">
                <h2 class="mb-4"><i class="fas fa-list"></i> My Medications</h2>
                <iframe src="../admin/product.php" style="width: 100%; height: 800px; border: none;"></iframe>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(el => el.style.display = 'none');
        // Show selected section
        document.getElementById(sectionId).style.display = 'block';
        
        // Update sidebar active state
        document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
        event.target.closest('.nav-link').classList.add('active');

        // Fetch medication count
        if (sectionId === 'dashboard') {
            fetchMedicationCount();
        }

        return false;
    }

    function fetchMedicationCount() {
        fetch('../actions/fetch_product_action.php')
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success' && data.data) {
                    document.getElementById('med-count').textContent = data.data.length;
                }
            })
            .catch(err => console.error(err));
    }

    // Handle profile form submission
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(profileForm);
                fetch('../actions/update_company_profile_action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(data => {
                    const msg = document.getElementById('msg');
                    if (data.status === 'success') {
                        msg.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        setTimeout(() => showSection('dashboard'), 1500);
                    } else {
                        msg.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(err => {
                    document.getElementById('msg').innerHTML = `<div class="alert alert-danger">Error updating profile</div>`;
                });
            });
        }

        fetchMedicationCount();
    });
</script>

</body>
</html>
