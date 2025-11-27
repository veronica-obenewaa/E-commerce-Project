<?php
/**
 * Paystack Callback Handler & Verification
 * Handles payment verification after user returns from Paystack gateway
 */

header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';
require_once '../controllers/order_controller.php';
require_once '../controllers/cart_controller.php';

$debug_info = [];  // Collect debug info to return in response

// Check if user is logged in
if (!isLoggedIn()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Session expired. Please login again.'
    ]);
    exit();
}

$customer_id = getUserId();
$debug_info['customer_id'] = $customer_id;
$debug_info['session_active'] = true;

// Get verification reference from POST data
$input = json_decode(file_get_contents('php://input'), true);
$reference = isset($input['reference']) ? trim($input['reference']) : null;
$cart_items = isset($input['cart_items']) ? $input['cart_items'] : null;
$total_amount = isset($input['total_amount']) ? floatval($input['total_amount']) : 0;

$debug_info['reference'] = $reference;
$debug_info['cart_items_from_client'] = $cart_items;
$debug_info['total_amount_from_client'] = $total_amount;

if (!$reference) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No payment reference provided',
        'debug' => $debug_info
    ]);
    exit();
}

// Optional: Verify reference matches session
if (isset($_SESSION['paystack_ref']) && $_SESSION['paystack_ref'] !== $reference) {
    error_log("Reference mismatch - Expected: {$_SESSION['paystack_ref']}, Got: $reference");
    // Allow to proceed anyway, but log it
}

try {
    error_log("Verifying Paystack transaction - Reference: $reference");
    
    // Verify transaction with Paystack
    $verification_response = paystack_verify_transaction($reference);
    
    if (!$verification_response) {
        throw new Exception("No response from Paystack verification API");
    }
    
    // Check if verification was successful
    if (!isset($verification_response['status']) || $verification_response['status'] !== true) {
        $error_msg = $verification_response['message'] ?? 'Payment verification failed';
        
        echo json_encode([
            'status' => 'error',
            'message' => $error_msg,
            'verified' => false
        ]);
        exit();
    }
    
    // Extract transaction data
    $transaction_data = $verification_response['data'] ?? [];
    $payment_status = $transaction_data['status'] ?? null;
    $amount_paid = isset($transaction_data['amount']) ? $transaction_data['amount'] / 100 : 0; // Convert from pesewas
    $customer_email = $transaction_data['customer']['email'] ?? '';
    $authorization = $transaction_data['authorization'] ?? [];
    $authorization_code = $authorization['authorization_code'] ?? '';
    $payment_method = $authorization['channel'] ?? 'card';
    $auth_last_four = $authorization['last_four'] ?? 'XXXX';
    
    // Validate payment status
    if ($payment_status !== 'success') {
        
        echo json_encode([
            'status' => 'error',
            'message' => 'Payment was not successful. Status: ' . ucfirst($payment_status),
            'verified' => false,
            'payment_status' => $payment_status
        ]);
        exit();
    }
    
    // Ensure we have expected total server-side (calculate from cart if frontend didn't send it)
    require_once '../controllers/cart_controller.php';
    
    if (!$cart_items || count($cart_items) == 0) {
        $cart_controller = new cart_controller();
        $cart_items = $cart_controller->get_user_cart_ctr($customer_id);
        $debug_info['cart_fetched_from_db'] = true;
        $debug_info['cart_items_count'] = $cart_items ? count($cart_items) : 0;
    }

    $calculated_total = 0.00;
    if ($cart_items && count($cart_items) > 0) {
        foreach ($cart_items as $ci) {
            if (isset($ci['subtotal'])) {
                $calculated_total += floatval($ci['subtotal']);
            } elseif (isset($ci['product_price']) && isset($ci['qty'])) {
                $calculated_total += floatval($ci['product_price']) * intval($ci['qty']);
            }
        }
        $debug_info['calculated_total'] = $calculated_total;
    } else {
        $debug_info['cart_status'] = 'EMPTY';
        $debug_info['cart_items'] = $cart_items;
    }

    if ($total_amount <= 0) {
        $total_amount = round($calculated_total, 2);
    }

    $debug_info['final_total_amount'] = $total_amount;

    // Check if cart is empty
    if ($calculated_total <= 0 || !$cart_items || count($cart_items) == 0) {
        echo json_encode([
            'status' => 'error',
            'verified' => false,
            'message' => 'Cart is empty. Cannot create order without items.',
            'debug' => $debug_info
        ]);
        exit();
    }

    // Verify amount matches (with 1 pesewa tolerance)
    if (abs($amount_paid - $total_amount) > 0.01) {
        $debug_info['amount_mismatch'] = [
            'expected' => $total_amount,
            'paid' => $amount_paid,
            'difference' => abs($amount_paid - $total_amount)
        ];

        echo json_encode([
            'status' => 'error',
            'message' => 'Payment amount does not match order total',
            'verified' => false,
            'expected' => number_format($total_amount, 2),
            'paid' => number_format($amount_paid, 2),
            'debug' => $debug_info
        ]);
        exit();
    }
    
    // Payment is verified! Now create the order in our system
    require_once '../controllers/order_controller.php';
    require_once '../settings/db_class.php';
    
    // Cart items already fetched above, create cart controller for empty operation
    $cart_controller = new cart_controller();
    
    // Create database connection for transaction
    $db = new db_connection();
    $conn = $db->db_conn();
    
    // Begin database transaction
    mysqli_begin_transaction($conn);
    error_log("Database transaction started");
    
    try {
        // Generate invoice number
        $invoice_no = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        $order_date = date('Y-m-d');
        
        $debug_info['invoice_generated'] = $invoice_no;
        
        // Create order in database
        $order_id = create_order_ctr($customer_id, $invoice_no, $order_date, 'Paid');
        
        $debug_info['order_creation_result'] = $order_id;
        
        if (!$order_id) {
            $debug_info['order_creation_failed'] = true;
            throw new Exception("Failed to create order in database");
        }
        
        $debug_info['order_id'] = $order_id;
        
        // Add order details for each cart item
        foreach ($cart_items as $item) {
            // Ensure we have the correct field name for product ID
            $product_id = $item['p_id'] ?? $item['product_id'] ?? null;
            $quantity = $item['qty'] ?? 0;
            
            if (!$product_id || $quantity <= 0) {
                throw new Exception("Invalid cart item data: " . json_encode($item));
            }
            
            $detail_result = add_order_details_ctr($order_id, $product_id, $quantity);
            
            if (!$detail_result) {
                throw new Exception("Failed to add order details for product: {$product_id}");
            }
            
            $debug_info['order_details_added'][] = [
                'product_id' => $product_id,
                'quantity' => $quantity
            ];
        }
        
        // Record payment in database
        $payment_id = record_payment_ctr(
            $total_amount,
            $customer_id,
            $order_id,
            'GHS',
            $order_date,
            'paystack',
            $reference,
            $authorization_code,
            $payment_method
        );
        
        if (!$payment_id) {
            throw new Exception("Failed to record payment");
        }
        
        $debug_info['payment_id'] = $payment_id;
        
        // Empty the customer's cart
        $empty_result = $cart_controller->empty_cart_ctr($customer_id);
        
        if (!$empty_result) {
            throw new Exception("Failed to empty cart");
        }
        
        $debug_info['cart_emptied'] = true;
        
        // Commit database transaction
        mysqli_commit($conn);
        
        // Clear session payment data
        unset($_SESSION['paystack_ref']);
        unset($_SESSION['paystack_amount']);
        unset($_SESSION['paystack_timestamp']);
        $delivery_service = $_SESSION['paystack_delivery_service'] ?? 'pickup';
        unset($_SESSION['paystack_delivery_service']);
        
        // Return success response
        echo json_encode([
            'status' => 'success',
            'verified' => true,
            'message' => 'Payment successful! Order confirmed.',
            'order_id' => $order_id,
            'invoice_no' => $invoice_no,
            'delivery_service' => $delivery_service,
            'total_amount' => number_format($total_amount, 2),
            'currency' => 'GHS',
            'order_date' => date('F j, Y', strtotime($order_date)),
            'item_count' => count($cart_items),
            'payment_reference' => $reference,
            'payment_method' => ucfirst($payment_method),
            'customer_email' => $customer_email,
            'debug' => $debug_info
        ]);
        
    } catch (Exception $e) {
        // Rollback database transaction on error
        mysqli_rollback($conn);
        $debug_info['transaction_rolled_back'] = true;
        $debug_info['error_details'] = $e->getMessage();
        
        throw $e;
    }
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'verified' => false,
        'message' => 'Payment processing error: ' . $e->getMessage(),
        'debug' => $debug_info
    ]);
}
?>
