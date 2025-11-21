<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/customer_controller.php';

header('Content-Type: application/json');

if (!isLoggedIn() || getUserRole() != 3) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$physician_id = getUserId();
$ctrl = new CustomerController();
$result = $ctrl->get_physician_profile($physician_id);
if ($result['status'] === 'success') {
    echo json_encode(['status' => 'success', 'data' => $result['data']]);
} else {
    echo json_encode(['status' => 'error', 'message' => $result['message'] ?? 'Could not fetch profile']);
}

?>