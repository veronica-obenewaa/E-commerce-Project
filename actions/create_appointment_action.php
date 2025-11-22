<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/booking_class.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to book an appointment']);
    exit;
}

// Only allow customers (role 2) to book appointments
if (!isCustomer() && getUserRole() != 2) {
    echo json_encode(['status' => 'error', 'message' => 'Only customers can book appointments']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Get form data
$physician_id = isset($_POST['physician_id']) ? intval($_POST['physician_id']) : 0;
$patient_id = getUserId();
$appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
$appointment_time = isset($_POST['appointment_time']) ? trim($_POST['appointment_time']) : '';
$health_conditions = isset($_POST['health_conditions']) ? trim($_POST['health_conditions']) : '';
$additional_notes = isset($_POST['additional_notes']) ? trim($_POST['additional_notes']) : '';

// Validation
if (!$physician_id || !$patient_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid physician or patient ID']);
    exit;
}

if (empty($appointment_date) || empty($appointment_time)) {
    echo json_encode(['status' => 'error', 'message' => 'Please select both date and time']);
    exit;
}

// Combine date and time
$appointment_datetime = $appointment_date . ' ' . $appointment_time;

// Validate datetime
$datetime = DateTime::createFromFormat('Y-m-d H:i:s', $appointment_datetime);
if (!$datetime || $datetime < new DateTime()) {
    echo json_encode(['status' => 'error', 'message' => 'Please select a valid future date and time']);
    exit;
}

// Combine health conditions and additional notes into reason_text
$reason_text = '';
if (!empty($health_conditions)) {
    $reason_text .= "Health Conditions:\n" . $health_conditions . "\n\n";
}
if (!empty($additional_notes)) {
    $reason_text .= "Additional Notes:\n" . $additional_notes;
}

// Create booking
$bookingClass = new booking_class();
$booking_id = $bookingClass->createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text, $health_conditions);

if ($booking_id) {
    // Clear session booking data
    unset($_SESSION['booking_health_conditions']);
    unset($_SESSION['booking_additional_notes']);
    
    echo json_encode([
        'status' => 'success', 
        'message' => 'Appointment booked successfully!',
        'booking_id' => $booking_id,
        'redirect' => 'view/user_dashboard.php'
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to book appointment. Please try again.']);
}

?>

