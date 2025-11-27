<?php
require_once __DIR__ . '/settings/core.php';
require_once __DIR__ . '/controllers/cart_controller.php';
require_once __DIR__ . '/settings/db_class.php';

echo "<h1>Cart Debugging Tool</h1>";

if (!isLoggedIn()) {
    echo "<p style='color: red;'>❌ Not logged in. <a href='index.php'>Please log in first</a></p>";
    exit;
}

$customer_id = getUserId();
echo "<p>Customer ID: <strong>" . htmlspecialchars($customer_id) . "</strong></p>";

// Test 1: Get cart via controller
echo "<h2>Test 1: Cart via Controller</h2>";
try {
    $cart_controller = new cart_controller();
    $cart_items = $cart_controller->get_user_cart_ctr($customer_id);
    
    if ($cart_items) {
        echo "<p style='color: green;'>✅ Found " . count($cart_items) . " items</p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>";
        echo htmlspecialchars(json_encode($cart_items, JSON_PRETTY_PRINT));
        echo "</pre>";
    } else {
        echo "<p style='color: red;'>❌ No items found (controller returned empty/null)</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 2: Direct database query
echo "<h2>Test 2: Direct Database Query</h2>";
try {
    $db = new db_connection();
    $conn = $db->db_conn();
    
    // Check cart with this customer ID
    $query = "SELECT * FROM cart WHERE c_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    if (count($rows) > 0) {
        echo "<p style='color: green;'>✅ Found " . count($rows) . " items with c_id = " . htmlspecialchars($customer_id) . "</p>";
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>";
        echo htmlspecialchars(json_encode($rows, JSON_PRETTY_PRINT));
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ No items found with c_id = " . htmlspecialchars($customer_id) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 3: Check for NULL c_id items
echo "<h2>Test 3: Items with NULL c_id (orphaned items)</h2>";
try {
    $db = new db_connection();
    $conn = $db->db_conn();
    
    $query = "SELECT COUNT(*) as count FROM cart WHERE c_id IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    if ($row['count'] > 0) {
        echo "<p style='color: orange;'>⚠️ Found " . $row['count'] . " items with NULL c_id (orphaned)</p>";
        
        // Show them
        $query = "SELECT * FROM cart WHERE c_id IS NULL LIMIT 5";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $orphans = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        echo "<pre style='background: #f5f5f5; padding: 10px; overflow-x: auto;'>";
        echo htmlspecialchars(json_encode($orphans, JSON_PRETTY_PRINT));
        echo "</pre>";
        
        echo "<p><strong>Suggested Fix:</strong> These items won't be retrieved. They may need to be cleaned up or assigned to the correct customer.</p>";
    } else {
        echo "<p style='color: green;'>✅ No orphaned items (c_id IS NULL)</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 4: Complete cart count
echo "<h2>Test 4: Total Cart Items in Database</h2>";
try {
    $db = new db_connection();
    $conn = $db->db_conn();
    
    $query = "SELECT COUNT(*) as total_items, COUNT(DISTINCT c_id) as unique_customers FROM cart";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    echo "<p>Total cart items: <strong>" . $row['total_items'] . "</strong></p>";
    echo "<p>Customers with items: <strong>" . $row['unique_customers'] . "</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Test 5: Session and customer info
echo "<h2>Test 5: Session Information</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Variable</th><th>Value</th></tr>";
echo "<tr><td>customer_id</td><td>" . htmlspecialchars(var_export($customer_id, true)) . "</td></tr>";
echo "<tr><td>\$_SESSION['customer_id']</td><td>" . htmlspecialchars(var_export($_SESSION['customer_id'] ?? 'NOT SET', true)) . "</td></tr>";
echo "<tr><td>\$_SESSION['user_id']</td><td>" . htmlspecialchars(var_export($_SESSION['user_id'] ?? 'NOT SET', true)) . "</td></tr>";
echo "<tr><td>Session ID</td><td>" . htmlspecialchars(session_id()) . "</td></tr>";
echo "</table>";

echo "<h2>Next Steps</h2>";
echo "<ul>";
echo "<li>If cart is empty: Add items to your cart first</li>";
echo "<li>If items shown but with NULL c_id: These are orphaned, add new items to cart</li>";
echo "<li>If customer_id is wrong: Check your login session</li>";
echo "</ul>";

?>
