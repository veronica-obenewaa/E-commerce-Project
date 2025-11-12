<?php
// actions/update_quantity_action.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'login_required', 'message' => 'Please log in to update your cart.']);
    exit();
}

$c_id = getUserId();
$p_id = intval($_POST['p_id'] ?? 0);
$qty = intval($_POST['qty'] ?? 1);

if ($p_id <= 0 || $qty <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity.']);
    exit();
}

$cartCtrl = new cart_controller();
$success = $cartCtrl->update_cart_item_ctr($c_id, $p_id, $qty);

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Cart updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update cart.']);
}
exit();
