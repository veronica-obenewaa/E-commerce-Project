<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//require_once '../settings/core.php';
//require_once __DIR__ . '/../settings/core.php';

require_once __DIR__ . '/../controllers/customer_controller.php';

//require_once __DIR__ . '/controllers/customer_controller.php';

header('Content-Type: application/json');

$customerController = new CustomerController();

// email availability checks (Ajax)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['check_email'])) {
    $email = filter_var($_GET['check_email'], FILTER_SANITIZE_EMAIL);
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit();
    }
    
    // $exists = $customerController->checkEmail($email);
    // echo json_encode(['status' => 'success', 'exists' => $exists]);
    // exit();
    try {
        $exists = $customerController->checkEmail($email);
        echo json_encode(['status' => 'success', 'exists' => $exists]);
    } catch (Throwable $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
    exit();


}

//customer registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input
    $data = filter_input_array(INPUT_POST, [
        'customer_name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'customer_email' => FILTER_SANITIZE_EMAIL,
        'customer_pass' => FILTER_UNSAFE_RAW, 
        'customer_country' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'customer_city' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'customer_contact' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'user_role'=> FILTER_SANITIZE_NUMBER_INT
    ]);

    try {
        $response = $customerController->register_customer_ctr($data);
        echo json_encode($response);
    } catch (Throwable $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
    
    exit();
}



// ivalid request method
echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
exit();
?>
