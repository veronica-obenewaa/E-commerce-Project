<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
require_once __DIR__ . '/../controllers/order_controller.php';
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit(); }
$customer_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['status'=>'error','message'=>'Invalid request']); exit(); }

// fetch cart summary
$cartCtrl = new cart_controller();
$summary = $cartCtrl->get_cart_summary_ctr($c_id);
$items = $summary['items'] ?? [];
$total_amount = $summary['total_amount'] ?? 0.0;

if (!$items || count($items) === 0) {
    echo json_encode(['status'=>'error','message'=>'Cart is empty']); exit();
}

// generate invoice - unique
$invoice_no = 'INV-' . time() . '-' . bin2hex(random_bytes(4));

// create order
$orderCtrl = new order_controller();
$order_id = $orderCtrl->create_order_ctr($customer_id, $invoice_no, $total_amount);
if (!$order_id) {
    echo json_encode(['status'=>'error','message'=>'Failed to create order']); exit();
}

// add order details
$ok = $orderCtrl->add_order_details_ctr($order_id, $items);
if (!$ok) {
    echo json_encode(['status'=>'error','message'=>'Failed to add order details']); exit();
}

// record simulated payment
$paid = $orderCtrl->record_payment_ctr($order_id, $total_amount);
if (!$paid) {
    // continue but flag failure
    echo json_encode(['status'=>'error','message'=>'Payment record failed']); exit();
}

// empty cart
$cartCtrl->empty_cart_ctr($customer_id);

// return success with order reference
echo json_encode(['status'=>'success','message'=>'Checkout complete','order_id'=>$order_id,'invoice_no'=>$invoice_no]);
exit();
