<?php

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
header('Content-Type: application/json');

if(!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'invalid request']);
    exit();
}


$cat_id = intval($_POST['cat_id'] ?? 0);
$customer_id = getUserId();


$ctrl = new CategoryController();
echo json_encode($ctrl->delete_category_ctr($cat_id, $customer_id));
exit();