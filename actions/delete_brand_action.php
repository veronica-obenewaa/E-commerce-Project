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



$brand_id = intval($_POST['brand_id'] ?? 0);
if(!$brand_id) {
    echo json_encode(['status' => 'error', 'message' => 'invalid brand ID']);
    exit();
}
$ctrl = new BrandController();
$response = $ctrl->delete_brand_ctr($brand_id);
echo json_encode($response);
exit();

?> 