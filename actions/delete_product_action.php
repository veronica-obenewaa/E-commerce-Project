<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

if(!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status'=>'error', 'message'=>'Unauthorised']);
    exit;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error', 'message'=>'Invalid']);
    exit;
}

$product_id = intval($_POST['product_id'] ?? 0);
$ctrl = new product_controller();
echo json_encode($ctrl->delete_product_ctr($product_id, getUserId()));
exit;