<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/booking_controller.php';
require_once __DIR__ . '/../classes/booking_class.php';
require_once __DIR__ . '/../classes/notification_class.php';

header('Content-Type: application/json');

// Only allow logged-in physicians to update their bookings
$userRole = getUserRole();
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized - Not logged in']);
    exit;
}

if ($userRole != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized - Physician only (Role: ' . $userRole . ')']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

// Basic validation
$allowed = ['scheduled','completed','cancelled'];
if ($booking_id <= 0 || !in_array($status, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit;
}

$ctrl = new BookingController();
$result = $ctrl->updateBookingStatus($booking_id, $status);

// If status is cancelled, create notification for patient
if ($result['status'] === 'success' && $status === 'cancelled') {
    $bookingClass = new booking_class();
    $booking = $bookingClass->getBookingWithZoom($booking_id);
    
    if ($booking) {
        $notificationClass = new notification_class();
        $physician_id = $booking['physician_id'];
        $patient_id = $booking['patient_id'];
        $appointment_datetime = $booking['appointment_datetime'];
        
        $message = 'Your consultation appointment scheduled for ' . 
                   date('F j, Y \a\t g:i A', strtotime($appointment_datetime)) . 
                   ' has been cancelled by the physician.';
        
        // Create notification
        $notificationClass->createNotification($booking_id, $patient_id, $physician_id, 'cancellation', $message);
    }
}

echo json_encode($result);
exit;
?>