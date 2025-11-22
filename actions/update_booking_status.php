<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/booking_controller.php';

header('Content-Type: application/json');

// Only allow logged-in physicians to update their bookings
if (!isLoggedIn() || getUserRole() != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
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

echo json_encode($result);
exit;
?>