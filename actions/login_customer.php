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
    
    // Determine redirect based on role (default fallback)
    $role = $customerData['role_id'] ?? $customerData['user_role'];
    $redirect = '../index.php'; // default for customers (role 2)
    
    if ($role == 1) { // pharmaceutical company
        $redirect = '../view/dashboard.php';
    } elseif ($role == 3) { // physician
        $redirect = '../admin/dashboard.php';
    }
    
    // If a redirect was posted from login form (e.g. user was sent to login from a protected page), prefer it
    // This ensures that clicking "Add Medication" redirects back to add product page after login
    $posted_redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : '';
    if (!empty($posted_redirect)) {
        // Basic safety: do not allow absolute/external URLs
        $lower = strtolower($posted_redirect);
        if (strpos($lower, 'http://') === false && strpos($lower, 'https://') === false && strpos($lower, '//') === false) {
            $redirect = $posted_redirect;
        }
    }
    
    // Return success response
    echo json_encode([
        'status' => 'success', 
        'message' => 'Login successful',
        'redirect' => $redirect,
        'role' => $role
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
