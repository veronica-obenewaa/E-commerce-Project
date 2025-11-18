<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

// allow admins/pharmaceutical companies (role 1) to fetch their products
// With new role mapping: 1=pharmaceutical company, 2=customer, 3=physician
if(!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status'=>'error', 'message'=>'Unauthorised']);
    exit;
}

$ctrl = new product_controller();
echo json_encode($ctrl->fetch_products_ctr(getUserId()));
exit;