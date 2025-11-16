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
    // store both legacy user_role and new role_id if available
    $_SESSION['user_role'] = $customerData['user_role'] ?? null;
    if (isset($customerData['role_id'])) $_SESSION['role_id'] = $customerData['role_id'];
    $_SESSION['customer_name'] = $customerData['customer_name'];
    $_SESSION['customer_email'] = $customerData['customer_email'];
    $_SESSION['logged_in'] = true;
    
    // Determine redirect based on role
    $role = $customerData['role_id'] ?? $customerData['user_role'];
    $redirect = '../index.php'; // default for customers
    
    if ($role == 1) { // admin
        $redirect = '../admin/product.php';
    } elseif ($role == 3) { // pharmaceutical company
        $redirect = '../company/dashboard.php';
    } elseif ($role == 4) { // physician
        $redirect = '../physician/dashboard.php';
    }
    
    // Return success response
    echo json_encode([
        'status' => 'success', 
        'message' => 'Login successful',
        'redirect' => $redirect
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
