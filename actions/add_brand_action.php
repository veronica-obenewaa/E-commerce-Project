<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';
header('Content-Type: application/json');

if(!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'invalid request']);
    exit();
}

$data = [
    'brand_name' => trim($_POST['brand_name'] ?? ''),
    'created_by' => getUserId()
];

$ctrl = new BrandController();
echo json_encode($ctrl->add_brand_ctr($data));
exit();
