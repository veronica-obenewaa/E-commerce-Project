<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit(); }
$customer_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['status'=>'error','message'=>'Invalid request']); exit(); }

$p_id = intval($_POST['p_id'] ?? 0);
$qty = max(1, intval($_POST['qty'] ?? 1));

if ($p_id <= 0) { echo json_encode(['status'=>'error','message'=>'Invalid product']); exit(); }

$ctrl = new cart_controller();
$res = $ctrl->add_to_cart_ctr($c_id, $p_id, $qty);
echo json_encode($res);
exit();
