<?php
// Start session
//session_start();


require_once '../settings/core.php';
require_once '../controllers/customer_controller.php';

//content type to JSON
header('Content-Type: application/json');

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

// Get and sanitize input data
$customer_email = filter_input(INPUT_POST, 'customer_email', FILTER_SANITIZE_EMAIL);
$customer_pass = trim($_POST['customer_pass']);

// Validate input
if (empty($customer_email) || empty($customer_pass)) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    exit();
}

// Create customer controller instance
$customerController = new CustomerController();

// Verify customer credentials
$result = $customerController->login_customer_ctr([
    'customer_email' => $customer_email,
    'customer_pass' => $customer_pass
]);

// Check if login was successful
if ($result['status'] === 'success') {
    $customerData = $result['data'];
    
    $_SESSION['customer_id'] = $customerData['customer_id'];
    $_SESSION['user_role'] = $customerData['user_role'];
    $_SESSION['customer_name'] = $customerData['customer_name'];
    $_SESSION['customer_email'] = $customerData['customer_email'];
    $_SESSION['logged_in'] = true;
    
    // Return success response
    echo json_encode([
        'status' => 'success', 
        'message' => 'Login successful',
        'redirect' => '../index.php'
    ]);
} else {
    // Return error response
    echo json_encode([
        'status' => 'error', 
        'message' => $result['message']
    ]);
}



//exit();
?>
