<?php
// classes/order_class.php
require_once __DIR__ . '/../settings/db_class.php';

class order_class extends db_connection {

    // create order row and return order_id
    public function createOrder($customer_id, $invoice_no, $total_amount, $order_status = 'pending') {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, invoice_no, order_status, total_amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $customer_id, $invoice_no, $order_status, $total_amount);
        $ok = $stmt->execute();
        if (!$ok) { $stmt->close(); return false; }
        $order_id = $conn->insert_id;
        $stmt->close();
        return $order_id;
    }

    // add order details (array of [product_id, qty, price])
    public function addOrderDetails($order_id, $items) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("INSERT INTO orderdetails (order_id, product_id, qty, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $it) {
            $pid = intval($it['product_id']);
            $qty = intval($it['qty']);
            $price = floatval($it['price']);
            $stmt->bind_param("iiid", $order_id, $pid, $qty, $price);
            if (!$stmt->execute()) { $stmt->close(); return false; }
        }
        $stmt->close();
        return true;
    }

    // simulated payment record
    public function recordPayment($order_id, $amount, $method = 'simulated', $order_status = 'success') {
        $conn = $this->db_conn();
        $currency = 'GHS';
        $stmt = $conn->prepare("INSERT INTO payments (order_id, amount, payment_method, payment_status, currency) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idsss", $order_id, $amount, $method, $order_status, $currency);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // fetch orders for a user
    public function getOrdersByUser($customer_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }
}
