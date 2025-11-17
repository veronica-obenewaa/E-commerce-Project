<?php

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
header('Content-Type: application/json');


if(!isLoggedIn() || (!isAdmin() && !isCompany())) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'invalid request']);
    exit();
}

$data = [
    'cat_name' => trim($_POST['cat_name'] ?? ''),
    'created_by' => getUserId()
];

$ctrl = new CategoryController();
echo json_encode($ctrl->add_category_ctr($data));
exit();