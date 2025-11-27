<?php
require_once __DIR__ . '/settings/core.php';
require_once __DIR__ . '/settings/db_class.php';
require_once __DIR__ . '/classes/cart_class.php';

// Debug: Show cart contents
if (!isLoggedIn()) {
    die("Please log in first to check your cart");
}

$customer_id = getUserId();
echo "<h2>Debug Cart Check</h2>";
echo "<p>Customer ID: " . htmlspecialchars($customer_id) . "</p>";

$cart = new cart_class();
$items = $cart->getCartItems($customer_id);

echo "<h3>Cart Items for Customer ID = " . htmlspecialchars($customer_id) . ":</h3>";
if ($items) {
    echo "<pre>" . json_encode($items, JSON_PRETTY_PRINT) . "</pre>";
    echo "<p><strong>Item count: " . count($items) . "</strong></p>";
} else {
    echo "<p>No items found in cart</p>";
}

// Check for items with NULL c_id
$conn = $cart->db_conn();
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE c_id IS NULL");
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

echo "<h3>Items with NULL c_id in database:</h3>";
echo "<p><strong>Count: " . $row['count'] . "</strong></p>";

if ($row['count'] > 0) {
    echo "<p style='color: red;'>⚠️ WARNING: There are cart items with NULL customer IDs. These won't be deleted!</p>";
}

// Show raw cart table contents (for admin debugging)
echo "<h3>Raw Cart Table Contents:</h3>";
$stmt = $conn->prepare("SELECT * FROM cart LIMIT 20");
$stmt->execute();
$res = $stmt->get_result();
$rows = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
echo "<pre>" . json_encode($rows, JSON_PRETTY_PRINT) . "</pre>";

?>
