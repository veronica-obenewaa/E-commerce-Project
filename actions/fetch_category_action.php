<?php

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
header('Content-Type: application/json');

if(!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$customer_id = getUserId();
$ctrl = new CategoryController();
echo json_encode($ctrl->fetch_categories_ctr($customer_id));
exit();