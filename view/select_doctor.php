<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/customer_class.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../Login/login.php');
    exit;
}

// Only allow registered customers (role 2) to book appointments
if (!isCustomer() && getUserRole() != 2) {
    // Redirect based on user role
    $userRole = getUserRole();
    if ($userRole == 1) {
        // Pharmaceutical company
        header('Location: ../view/dashboard.php');
    } elseif ($userRole == 3) {
        // Physician
        header('Location: ../admin/dashboard.php');
    } else {
        // Unknown role, redirect to home
        header('Location: ../index.php');
    }
    exit;
}

// Get health conditions and notes from previous page
$health_conditions = $_POST['health_conditions'] ?? '';
$additional_notes = $_POST['additional_notes'] ?? '';

// Store in session for later use
if (!empty($health_conditions)) {
    $_SESSION['booking_health_conditions'] = $health_conditions;
}
if (!empty($additional_notes)) {
    $_SESSION['booking_additional_notes'] = $additional_notes;
}

// Fetch all physicians
$customerModel = new customer_class();
$physicians = $customerModel->getAllPhysicians();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Doctor | Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .page-header {
            background: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .page-title {
            color: #0b6623;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .physician-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            height: 100%;
        }
        .physician-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .physician-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0b6623 0%, #14a851 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 auto 1rem;
        }
        .physician-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .physician-specialty {
            color: #0b6623;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .physician-info {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        .physician-info i {
            color: #0b6623;
            width: 20px;
        }
        .consultation-fee {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0b6623;
            margin-top: 1rem;
        }
        .btn-primary {
            background-color: #0b6623;
            border-color: #0b6623;
        }
        .btn-primary:hover {
            background-color: #09531d;
            border-color: #09531d;
        }
        .no-physicians {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h2 class="page-title"><i class="fas fa-user-doctor"></i> Select Your Doctor</h2>
        <p class="text-muted mb-0">Choose a physician that best matches your needs</p>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <?php if (empty($physicians)): ?>
        <div class="no-physicians">
            <i class="fas fa-user-doctor fa-3x mb-3 text-muted"></i>
            <h4>No Physicians Available</h4>
            <p>There are currently no physicians available. Please check back later.</p>
            <a href="../index.php" class="btn btn-primary">Return Home</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($physicians as $physician): 
                $initials = strtoupper(substr($physician['customer_name'], 0, 2));
                $location = $physician['customer_city'] . ', ' . $physician['customer_country'];
                $specializations = $physician['specializations'] ?? 'General Practice';
                $consultation_fee = '50.00'; // Default fee - you can add this to database later
                $years_experience = '5+'; // Default - you can add this to database later
            ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="physician-card" onclick="selectPhysician(<?php echo $physician['customer_id']; ?>)">
                        <div class="physician-avatar">
                            <?php echo $initials; ?>
                        </div>
                        <div class="text-center">
                            <div class="physician-name"><?php echo htmlspecialchars($physician['customer_name']); ?></div>
                            <div class="physician-specialty">
                                <i class="fas fa-stethoscope"></i> <?php echo htmlspecialchars($specializations); ?>
                            </div>
                        </div>
                        <div class="physician-info mt-3">
                            <div class="mb-2">
                                <i class="fas fa-hospital"></i> 
                                <strong>Hospital:</strong> <?php echo htmlspecialchars($physician['hospital_name'] ?? 'N/A'); ?>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-map-marker-alt"></i> 
                                <strong>Location:</strong> <?php echo htmlspecialchars($location); ?>
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-certificate"></i> 
                                <strong>Experience:</strong> <?php echo htmlspecialchars($years_experience); ?> years
                            </div>
                        </div>
                        <div class="text-center consultation-fee">
                            GHS <?php echo htmlspecialchars($consultation_fee); ?>
                            <small class="d-block text-muted" style="font-size: 0.75rem;">Per Consultation</small>
                        </div>
                        <button class="btn btn-primary w-100 mt-3" onclick="event.stopPropagation(); selectPhysician(<?php echo $physician['customer_id']; ?>);">
                            Select This Doctor
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function selectPhysician(physicianId) {
    // Redirect to schedule appointment page with physician ID
    window.location.href = 'schedule_appointment.php?physician_id=' + physicianId;
}
</script>

</body>
</html>

