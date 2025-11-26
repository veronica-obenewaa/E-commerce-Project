<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/customer_class.php';

header('Content-Type: application/json');

// Only allow logged-in customers (role 2)
if (!isLoggedIn() || (!isCustomer() && getUserRole() != 2)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$customer_id = getUserId();
$customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
$customer_city = isset($_POST['customer_city']) ? trim($_POST['customer_city']) : '';
$customer_country = isset($_POST['customer_country']) ? trim($_POST['customer_country']) : '';
$customer_contact = isset($_POST['customer_contact']) ? trim($_POST['customer_contact']) : '';

// Basic validation
if (empty($customer_name) || empty($customer_contact)) {
    echo json_encode(['status' => 'error', 'message' => 'Name and contact phone are required']);
    exit;
}

// Validate phone number format (basic validation)
if (!preg_match('/^[\d\s\-\+\(\)]+$/', $customer_contact) || strlen(preg_replace('/\D/', '', $customer_contact)) < 7) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid phone number format']);
    exit;
}

$customerClass = new customer_class();

// Check if contact already exists for another customer
if ($customerClass->checkContact($customer_contact, $customer_id)) {
    echo json_encode(['status' => 'error', 'message' => 'This phone number is already registered']);
    exit;
}

// Update customer profile
$result = $customerClass->editCustomer($customer_id, $customer_name, $customer_country, $customer_city, $customer_contact);

if ($result) {
    // Update session data
    $_SESSION['customer_name'] = $customer_name;
    $_SESSION['customer_city'] = $customer_city;
    $_SESSION['customer_country'] = $customer_country;
    $_SESSION['customer_contact'] = $customer_contact;
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Profile updated successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to update profile. Please try again.'
    ]);
}
exit;
?>
