<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/cart_controller.php';
require_once __DIR__ . '/../controllers/order_controller.php';
require_once __DIR__ . '/../controllers/product_controller.php';
header('Content-Type: application/json');

if (!isLoggedIn()) { echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit(); }
$customer_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['status'=>'error','message'=>'Invalid request']); exit(); }

// fetch cart items
$cartCtrl = new cart_controller();
$items = $cartCtrl->get_user_cart_ctr($customer_id);

if (!$items || count($items) === 0) {
    echo json_encode(['status'=>'error','message'=>'Cart is empty']); exit();
}

// calculate total amount
$total_amount = 0.0;
foreach ($items as $item) {
    $total_amount += floatval($item['qty']) * floatval($item['product_price']);
}

// generate invoice - unique
$invoice_no = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
$order_date = date('Y-m-d');
$order_status = 'Pending';

// create order
$order_id = create_order_ctr($customer_id, $invoice_no, $order_date, $order_status);
if (!$order_id) {
    echo json_encode(['status'=>'error','message'=>'Failed to create order']); exit();
}

// add order details for each item
foreach ($items as $item) {
    $product_id = $item['p_id'] ?? $item['product_id'] ?? null;
    $qty = $item['qty'] ?? 0;
    
    if ($product_id && $qty > 0) {
        $ok = add_order_details_ctr($order_id, $product_id, $qty);
        if (!$ok) {
            echo json_encode(['status'=>'error','message'=>'Failed to add order details']); exit();
        }
    }
}

// record payment
$payment_id = record_payment_ctr($total_amount, $customer_id, $order_id, 'GHS', $order_date);
if (!$payment_id) {
    echo json_encode(['status'=>'error','message'=>'Payment record failed']); exit();
}

// empty cart
$cartCtrl->empty_cart_ctr($customer_id);

// return success with order reference
echo json_encode(['status'=>'success','message'=>'Checkout complete','order_id'=>$order_id,'invoice_no'=>$invoice_no]);
exit();
