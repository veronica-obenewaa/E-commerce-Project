<?php
// actions/remove_from_cart_action.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'login_required', 'message' => 'Please log in to remove items from your cart.']);
    exit();
}

$c_id = getUserId();
$p_id = intval($_POST['p_id'] ?? 0);

if ($p_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID.']);
    exit();
}

$cartCtrl = new cart_controller();
$success = $cartCtrl->remove_from_cart_ctr($c_id, $p_id);

if ($success) {
    echo json_encode(['status' => 'success', 'message' => 'Item removed from cart.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to remove item.']);
}
exit();
