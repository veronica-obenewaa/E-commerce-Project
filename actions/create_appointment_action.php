<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/booking_class.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to book an appointment']);
    exit;
}

// Only allow registered customers (role 2) to book appointments
if (!isCustomer() && getUserRole() != 2) {
    $userRole = getUserRole();
    $roleName = 'Unknown';
    if ($userRole == 1) {
        $roleName = 'Pharmaceutical Company';
    } elseif ($userRole == 3) {
        $roleName = 'Physician';
    }
    echo json_encode([
        'status' => 'error', 
        'message' => 'Only registered customers can book appointments. You are logged in as a ' . $roleName . '.'
    ]);
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
    // Get physician and patient names for Zoom meeting topic
    require_once __DIR__ . '/../classes/customer_class.php';
    $customerClass = new customer_class();
    $physician = $customerClass->getPhysicianById($physician_id);
    $patient = $customerClass->getCustomerById($patient_id);
    
    $physician_name = $physician['customer_name'] ?? 'Physician';
    $patient_name = $patient['customer_name'] ?? 'Patient';
    
    // Create Zoom meeting
    $zoom_result = $bookingClass->createZoomMeeting(
        $booking_id,
        $appointment_datetime,
        $physician_name,
        $patient_name
    );
    
    // Clear session booking data
    unset($_SESSION['booking_health_conditions']);
    unset($_SESSION['booking_additional_notes']);
    
    if ($zoom_result['success']) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Appointment booked successfully! Zoom meeting link has been created.',
            'booking_id' => $booking_id,
            'zoom_link' => $zoom_result['join_url'],
            'redirect' => '../view/user_dashboard.php'
        ]);
    } else {
        // Booking created but Zoom meeting failed
        $error_msg = $zoom_result['error'] ?? 'Unknown error';
        // Include debug info if available
        if (isset($zoom_result['debug']['raw_response'])) {
            $error_msg .= ' | Response: ' . $zoom_result['debug']['raw_response'];
        }
        echo json_encode([
            'status' => 'partial', 
            'message' => 'Appointment booked, but Zoom meeting could not be created: ' . $error_msg,
            'booking_id' => $booking_id,
            'redirect' => '../view/user_dashboard.php'
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to book appointment. Please try again.']);
}

?>

