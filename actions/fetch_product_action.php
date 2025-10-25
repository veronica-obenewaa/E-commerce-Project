<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

if(!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status'=>'error', 'message'=>'Unauthorised']);
    exit;
}

$ctrl = new product_controller();
echo json_encode($ctrl->fetch_products_ctr(getUserId()));
exit;