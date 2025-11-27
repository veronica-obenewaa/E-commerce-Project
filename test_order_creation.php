<?php
require_once __DIR__ . '/settings/core.php';
require_once __DIR__ . '/controllers/order_controller.php';
require_once __DIR__ . '/classes/order_class.php';

echo "<h1>Order Creation Debug Test</h1>";

// Check if user is logged in
if (!isLoggedIn()) {
    echo "<p style='color: red;'>❌ Not logged in</p>";
    exit;
}

$customer_id = getUserId();
echo "<p>Customer ID: <strong>" . htmlspecialchars($customer_id) . "</strong></p>";

// Test 1: Create an order directly
echo "<h2>Test 1: Direct Order Creation</h2>";
try {
    $order = new order_class();
    $invoice_no = 'TEST-' . date('YmdHis') . '-' . uniqid();
    $order_date = date('Y-m-d');
    
    echo "<p>Invoice: $invoice_no</p>";
    echo "<p>Date: $order_date</p>";
    
    $order_id = $order->create_order($customer_id, $invoice_no, $order_date, 'Testing');
    
    echo "<p>Order ID Result: " . var_export($order_id, true) . "</p>";
    
    if ($order_id) {
        echo "<p style='color: green;'>✅ Order created successfully! ID: $order_id</p>";
    } else {
        echo "<p style='color: red;'>❌ Order creation returned falsy value</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

// Test 2: Using controller function
echo "<h2>Test 2: Order via Controller Function</h2>";
try {
    $invoice_no_2 = 'TEST2-' . date('YmdHis') . '-' . uniqid();
    $order_date = date('Y-m-d');
    
    $order_id_2 = create_order_ctr($customer_id, $invoice_no_2, $order_date, 'Testing');
    
    echo "<p>Order ID Result: " . var_export($order_id_2, true) . "</p>";
    
    if ($order_id_2) {
        echo "<p style='color: green;'>✅ Order created via controller! ID: $order_id_2</p>";
    } else {
        echo "<p style='color: red;'>❌ Controller returned falsy value</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 3: Check database connection
echo "<h2>Test 3: Database Connection</h2>";
try {
    $db = new db_connection();
    $conn = $db->db_conn();
    
    if ($conn) {
        echo "<p style='color: green;'>✅ Database connection successful</p>";
        
        // Check orders table
        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM orders");
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            echo "<p>Total orders in database: " . $row['count'] . "</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Check error logs
echo "<h2>Error Logs (last 20 lines)</h2>";
echo "<pre style='background: #f0f0f0; padding: 10px; max-height: 300px; overflow-y: auto;'>";
// PHP error log usually stored in the project directory
if (file_exists(__DIR__ . '/php_errors.log')) {
    $logs = file(__DIR__ . '/php_errors.log');
    $last_lines = array_slice($logs, -20);
    echo htmlspecialchars(implode('', $last_lines));
} else {
    echo "No php_errors.log found in project directory";
}
echo "</pre>";

?>
