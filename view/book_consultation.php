<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/notification_class.php';
include __DIR__ . '/header.php';

// Check if user is logged in
// if (!isLoggedIn()) {
//     // Pass redirect as GET parameter (relative to Login/login.php)
//     header('Location: ../Login/login.php?redirect=book_consultation.php');
//     exit;
// }

// // Only allow registered customers (role 2) to book appointments
// if (!isCustomer() && getUserRole() != 2) {
//     // Redirect based on user role
//     $userRole = getUserRole();
//     if ($userRole == 1) {
//         // Pharmaceutical company
//         header('Location:dashboard.php');
//     } elseif ($userRole == 3) {
//         // Physician
//         header('Location: ../admin/dashboard.php');
//     } else {
//         // Unknown role, redirect to home
//         header('Location: ../index.php');
//     }
//     exit;
// }

// Get unread notifications for logged-in user
$unread_notifications = [];
if (isLoggedIn() && isCustomer()) {
    $customer_id = getUserId();
    $notificationClass = new notification_class();
    $unread_notifications = $notificationClass->getUnreadNotifications($customer_id);
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
        .notification-alert {
            border-left: 4px solid #dc3545;
            background-color: #fff5f5;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease-out;
        }
        .notification-alert.info {
            border-left-color: #0066cc;
            background-color: #e6f7ff;
        }
        .notification-alert.warning {
            border-left-color: #ff9800;
            background-color: #fff8e1;
        }
        .notification-alert.success {
            border-left-color: #4caf50;
            background-color: #f1f8e9;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .alert-close-btn {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .alert-close-btn:hover {
            opacity: 1;
        }
        .emergency-section {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-radius: 12px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        .emergency-section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .emergency-section p {
            margin-bottom: 1.5rem;
            opacity: 0.95;
            font-size: 0.95rem;
        }
        .emergency-contact-number {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            font-family: 'Courier New', monospace;
        }
        .emergency-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-emergency {
            background: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 140px;
            justify-content: center;
        }
        .btn-call {
            color: #dc3545;
        }
        .btn-call:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .btn-message {
            color: #dc3545;
        }
        .btn-message:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>



<!-- Main Content -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Emergency Contact Section -->
            <div class="emergency-section">
                <h3>
                    <i class="fas fa-exclamation-triangle"></i>
                    Need Immediate Medical Attention?
                </h3>
                <p>If you're experiencing a medical emergency, please contact our emergency hotline immediately.</p>
                <div class="emergency-contact-number">
                    +233 268 376 848
                </div>
                <div class="emergency-actions">
                    <button type="button" class="btn-emergency btn-call" onclick="callEmergency()">
                        <i class="fas fa-phone"></i>
                        Call Now
                    </button>
                    <button type="button" class="btn-emergency btn-message" onclick="messageEmergency()">
                        <i class="fas fa-sms"></i>
                        Send Message
                    </button>
                </div>
            </div>

            <!-- Display Cancellation Alerts -->
            <?php if (!empty($unread_notifications)): ?>
                <?php foreach ($unread_notifications as $notification): 
                    if ($notification['notification_type'] === 'cancellation'):
                ?>
                        <div class="notification-alert" id="notification-<?php echo $notification['notification_id']; ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #d32f2f; margin-bottom: 0.5rem;">
                                        <i class="fas fa-ban"></i> Appointment Cancelled
                                    </div>
                                    <div style="color: #555; font-size: 0.95rem;">
                                        <?php echo htmlspecialchars($notification['message']); ?>
                                    </div>
                                    <?php if (!empty($notification['physician_name'])): ?>
                                        <div style="color: #888; font-size: 0.85rem; margin-top: 0.5rem;">
                                            <strong>Dr.</strong> <?php echo htmlspecialchars($notification['physician_name']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="alert-close-btn" 
                                        onclick="dismissNotification(<?php echo $notification['notification_id']; ?>)" 
                                        title="Dismiss">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                <?php 
                    endif;
                endforeach; 
                ?>
            <?php endif; ?>

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

// Function to dismiss notification and mark as read
function dismissNotification(notificationId) {
    const alertElement = document.getElementById('notification-' + notificationId);
    
    if (alertElement) {
        // Fade out animation
        alertElement.style.opacity = '0';
        alertElement.style.transition = 'opacity 0.3s ease-out';
        
        setTimeout(() => {
            alertElement.remove();
            
            // Mark as read on server
            fetch('../actions/mark_notification_read.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + notificationId
            }).catch(error => console.error('Error marking notification as read:', error));
        }, 300);
    }
}

// Auto-mark notifications as read after 10 seconds if not dismissed
document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('[id^="notification-"]');
    notifications.forEach(notification => {
        setTimeout(() => {
            const notificationId = notification.id.replace('notification-', '');
            if (document.getElementById('notification-' + notificationId)) {
                dismissNotification(notificationId);
            }
        }, 10000); // 10 seconds
    });
});

// Emergency contact functions
function callEmergency() {
    const emergencyNumber = '+233XXXXXXXXXX'; // Replace with actual number
    window.location.href = 'tel:' + emergencyNumber.replace(/[^0-9+]/g, '');
}

function messageEmergency() {
    const emergencyNumber = '+233XXXXXXXXXX'; // Replace with actual number
    const message = 'I need immediate medical assistance. Please help.';
    const cleanedNumber = emergencyNumber.replace(/[^0-9+]/g, '');
    window.location.href = 'sms:' + cleanedNumber + '?body=' + encodeURIComponent(message);
}
</script>

</body>
</html>


