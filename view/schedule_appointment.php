<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/customer_class.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../Login/login.php');
    exit;
}

// Get physician ID from URL
$physician_id = isset($_GET['physician_id']) ? intval($_GET['physician_id']) : 0;

if (!$physician_id) {
    header('Location: select_doctor.php');
    exit;
}

// Fetch physician details
$customerModel = new customer_class();
$physician = $customerModel->getPhysicianById($physician_id);

if (!$physician) {
    header('Location: select_doctor.php');
    exit;
}

// Get health conditions and notes from session
$health_conditions = $_SESSION['booking_health_conditions'] ?? '';
$additional_notes = $_SESSION['booking_additional_notes'] ?? '';

$specializations = $physician['specializations'] ?? 'General Practice';
$location = $physician['customer_city'] . ', ' . $physician['customer_country'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Your Appointment | Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .appointment-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 2.5rem;
            margin-top: 2rem;
        }
        .physician-info-card {
            background: linear-gradient(135deg, #e6fff2 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .physician-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0b6623;
            margin-bottom: 0.5rem;
        }
        .page-title {
            color: #0b6623;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            background-color: #0b6623;
            border-color: #0b6623;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #09531d;
            border-color: #09531d;
        }
        .info-item {
            color: #666;
            margin-bottom: 0.5rem;
        }
        .info-item i {
            color: #0b6623;
            width: 20px;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<!-- Main Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="appointment-card">
                <h2 class="page-title"><i class="fas fa-calendar-alt"></i> Schedule Your Appointment</h2>
                
                <!-- Physician Information -->
                <div class="physician-info-card">
                    <div class="physician-name">
                        <i class="fas fa-user-doctor"></i> <?php echo htmlspecialchars($physician['customer_name']); ?>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-stethoscope"></i> 
                        <strong>Speciality:</strong> <?php echo htmlspecialchars($specializations); ?>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-hospital"></i> 
                        <strong>Hospital:</strong> <?php echo htmlspecialchars($physician['hospital_name'] ?? 'N/A'); ?>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i> 
                        <strong>Location:</strong> <?php echo htmlspecialchars($location); ?>
                    </div>
                </div>

                <form id="appointmentForm" method="POST" action="../actions/create_appointment_action.php">
                    <input type="hidden" name="physician_id" value="<?php echo $physician_id; ?>">
                    <input type="hidden" name="health_conditions" value="<?php echo htmlspecialchars($health_conditions); ?>">
                    <input type="hidden" name="additional_notes" value="<?php echo htmlspecialchars($additional_notes); ?>">

                    <div class="mb-4">
                        <label for="appointment_date" class="form-label">
                            <i class="fas fa-calendar"></i> Select Date
                        </label>
                        <input 
                            type="date" 
                            class="form-control" 
                            id="appointment_date" 
                            name="appointment_date" 
                            required
                            min="<?php echo date('Y-m-d'); ?>"
                        >
                        <small class="form-text text-muted">Select a date for your appointment</small>
                    </div>

                    <div class="mb-4">
                        <label for="appointment_time" class="form-label">
                            <i class="fas fa-clock"></i> Select Time
                        </label>
                        <select class="form-select" id="appointment_time" name="appointment_time" required>
                            <option value="">Choose a time</option>
                            <?php
                            // Generate time slots (9 AM to 5 PM, 1-hour intervals)
                            for ($hour = 9; $hour <= 17; $hour++) {
                                $time12 = ($hour > 12) ? $hour - 12 : $hour;
                                $ampm = ($hour >= 12) ? 'PM' : 'AM';
                                if ($hour == 12) $ampm = 'PM';
                                $time24 = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
                                $time12_display = $time12 . ':00 ' . $ampm;
                                echo '<option value="' . $time24 . '">' . $time12_display . '</option>';
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Select a time slot for your appointment</small>
                    </div>

                    <div id="formMessage" class="mb-3"></div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="select_doctor.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="fas fa-check"></i> Confirm Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const date = document.getElementById('appointment_date').value;
    const time = document.getElementById('appointment_time').value;
    
    if (!date || !time) {
        document.getElementById('formMessage').innerHTML = 
            '<div class="alert alert-warning">Please select both date and time.</div>';
        return false;
    }
    
    // Disable submit button
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    // Submit form via AJAX
    const formData = new FormData(this);
    
    fetch('../actions/create_appointment_action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('formMessage').innerHTML = 
                '<div class="alert alert-success">' + data.message + '</div>';
            // Redirect after 1 second
            setTimeout(() => {
                window.location.href = '../' + (data.redirect || 'view/user_dashboard.php');
            }, 1000);
        } else {
            document.getElementById('formMessage').innerHTML = 
                '<div class="alert alert-danger">' + (data.message || 'Failed to book appointment') + '</div>';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Confirm Appointment';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('formMessage').innerHTML = 
            '<div class="alert alert-danger">An error occurred. Please try again.</div>';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check"></i> Confirm Appointment';
    });
    
    return false;
});

// Set minimum date to today
document.getElementById('appointment_date').setAttribute('min', new Date().toISOString().split('T')[0]);
</script>

</body>
</html>

