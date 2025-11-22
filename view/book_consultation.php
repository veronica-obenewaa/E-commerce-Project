<?php
require_once __DIR__ . '/../settings/core.php';

// Check if user is logged in
if (!isLoggedIn()) {
    $_SESSION['redirect_after_login'] = 'view/book_consultation.php';
    header('Location: ../Login/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Consultation | Med-ePharma</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .consultation-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 2.5rem;
            margin-top: 2rem;
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
        .page-title {
            color: #0b6623;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .page-subtitle {
            color: #666;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

<!-- Main Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="consultation-card">
                <h2 class="page-title"><i class="fas fa-calendar-check"></i> Book Your Consultation</h2>
                <p class="page-subtitle">Please provide your health information to help us connect you with the right physician.</p>

                <form id="consultationForm" method="POST" action="select_doctor.php">
                    <div class="mb-4">
                        <label for="health_conditions" class="form-label">
                            <i class="fas fa-heartbeat"></i> Existing Health Conditions
                        </label>
                        <textarea 
                            class="form-control" 
                            id="health_conditions" 
                            name="health_conditions" 
                            rows="4" 
                            placeholder="Please list any existing health conditions, chronic illnesses, or ongoing treatments..."
                            required
                        ></textarea>
                        <small class="form-text text-muted">This information helps physicians provide better care.</small>
                    </div>

                    <div class="mb-4">
                        <label for="additional_notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Additional Notes
                        </label>
                        <textarea 
                            class="form-control" 
                            id="additional_notes" 
                            name="additional_notes" 
                            rows="5" 
                            placeholder="Please describe your current symptoms, specific concerns, questions, or any other information you'd like the physician to know..."
                            required
                        ></textarea>
                        <small class="form-text text-muted">Current symptoms, specific concerns, or questions for the physician.</small>
                    </div>

                    <div id="formMessage" class="mb-3"></div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="../index.php" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Continue to Select Doctor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('consultationForm').addEventListener('submit', function(e) {
    const healthConditions = document.getElementById('health_conditions').value.trim();
    const additionalNotes = document.getElementById('additional_notes').value.trim();
    
    if (!healthConditions || !additionalNotes) {
        e.preventDefault();
        document.getElementById('formMessage').innerHTML = 
            '<div class="alert alert-warning">Please fill in all required fields.</div>';
        return false;
    }
    
    // Form will submit normally if validation passes
    return true;
});
</script>

</body>
</html>

