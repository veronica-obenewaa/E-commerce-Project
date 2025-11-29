<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';
header('Content-Type: application/json');

try {
    if(!isLoggedIn() || !isAdmin()) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'invalid request']);
        exit();
    }

    $data = [
        'brand_name' => trim($_POST['brand_name'] ?? '')
    ];

    $ctrl = new BrandController();
    echo json_encode($ctrl->add_brand_ctr($data));
} catch (Exception $e) {
    error_log("Error in add_brand_action.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
exit();
