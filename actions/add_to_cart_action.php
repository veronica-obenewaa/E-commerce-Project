<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
	echo json_encode(['status' => 'login_required', 'message' => 'Please log in to add items to your cart.']);
	exit();
}

$customer_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
	exit();
}

$p_id = intval($_POST['p_id'] ?? 0);
$qty = max(1, intval($_POST['qty'] ?? 1));

if ($p_id <= 0) {
	echo json_encode(['status' => 'error', 'message' => 'Invalid product']);
	exit();
}

$ctrl = new cart_controller();
$ok = $ctrl->add_to_cart_ctr($customer_id, $p_id, $qty);

if ($ok) {
	echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
} else {
	echo json_encode(['status' => 'error', 'message' => 'Failed to add to cart']);
}
exit();
