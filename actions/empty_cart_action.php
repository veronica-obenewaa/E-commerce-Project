<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit(); }
$customer_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['status'=>'error','message'=>'Invalid request']); exit(); }

$ctrl = new cart_controller();
$res = $ctrl->empty_cart_ctr($c_id);
echo json_encode($res);
exit();
