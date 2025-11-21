<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/customer_controller.php';

header('Content-Type: application/json');

// Only allow logged-in physicians (role 3)
if (!isLoggedIn() || getUserRole() != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

$customer_id = getUserId();

$data = [
    'customer_id' => $customer_id,
    'customer_name' => isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '',
    'hospital_name' => isset($_POST['hospital_name']) ? trim($_POST['hospital_name']) : '',
    'hospital_registration_number' => isset($_POST['hospital_registration_number']) ? trim($_POST['hospital_registration_number']) : '',
    'customer_country' => isset($_POST['customer_country']) ? trim($_POST['customer_country']) : '',
    'customer_city' => isset($_POST['customer_city']) ? trim($_POST['customer_city']) : '',
    'customer_contact' => isset($_POST['customer_contact']) ? trim($_POST['customer_contact']) : ''
];

$ctrl = new CustomerController();
$result = $ctrl->update_physician_profile_ctr($data);

echo json_encode($result);
exit();

?>