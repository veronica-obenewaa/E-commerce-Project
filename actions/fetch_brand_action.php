<?php

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/brand_controller.php';
header('Content-Type: application/json');

if(!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

//$customer_id = getUserId();
$ctrl = new BrandController();
echo json_encode($ctrl->fetch_brand_ctr($getUserId));
exit();

?>