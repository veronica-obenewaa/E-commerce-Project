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

$data = [
    'cat_id' => intval($_POST['cat_id'] ?? 0),
    'cat_name' => trim($_POST['cat_name'] ?? ''),
    'customer_id' => getUserId()
];

$ctrl = new CategoryController();
echo json_encode($ctrl->update_category_ctr($data));
exit();