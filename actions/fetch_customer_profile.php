<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/customer_class.php';

header('Content-Type: application/json');

// Only allow logged-in customers
if (!isLoggedIn() || (!isCustomer() && getUserRole() != 2)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$customer_id = getUserId();
$customerClass = new customer_class();
$customer = $customerClass->getCustomerById($customer_id);

if ($customer) {
    echo json_encode([
        'status' => 'success',
        'data' => $customer
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Customer not found'
    ]);
}
exit;
?>
