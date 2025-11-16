<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/customer_controller.php';

header('Content-Type: application/json');

// Only allow logged-in pharmaceutical companies (role 3)
if (!isLoggedIn() || getUserRole() != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$customer_id = getUserId();

// Sanitize input
$data = [
    'customer_id' => $customer_id,
    'customer_name' => isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '',
    'company_name' => isset($_POST['company_name']) ? trim($_POST['company_name']) : '',
    'customer_country' => isset($_POST['customer_country']) ? trim($_POST['customer_country']) : '',
    'customer_city' => isset($_POST['customer_city']) ? trim($_POST['customer_city']) : '',
    'customer_contact' => isset($_POST['customer_contact']) ? trim($_POST['customer_contact']) : ''
];

$customerCtrl = new CustomerController();
$result = $customerCtrl->update_company_profile_ctr($data);

echo json_encode($result);
exit();
