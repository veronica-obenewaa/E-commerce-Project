<?php
// classes/cart_class.php
require_once __DIR__ . '/../settings/db_class.php';

class cart_class extends db_connection {

    // add product to cart (if exists, increment qty)
    public function addToCart($c_id, $p_id, $qty = 1) {
        $conn = $this->db_conn();
        // check if exists
        $stmt = $conn->prepare("SELECT qty FROM cart WHERE c_id = ? AND p_id = ? LIMIT 1");
        $stmt->bind_param("ii", $c_id, $p_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $newQty = max(1, intval($row['qty']) + intval($qty));
            $stmt->close();
            $u = $conn->prepare("UPDATE cart SET qty = ?, added_at = NOW() WHERE c_id = ? AND p_id = ?");
            $u->bind_param("iii", $newQty, $c_id, $p_id);
            $ok = $u->execute();
            $u->close();
            return $ok ? ['status'=>'success','message'=>'Cart updated','qty'=>$newQty] : ['status'=>'error','message'=>'Failed to update cart'];
        }
        $stmt->close();

        $ins = $conn->prepare("INSERT INTO cart (p_id, c_id, qty) VALUES (?, ?, ?)");
        $ins->bind_param("iii", $p_id, $c_id, $qty);
        $ok = $ins->execute();
        $ins->close();
        return $ok ? ['status'=>'success','message'=>'Added to cart'] : ['status'=>'error','message'=>'Failed to add to cart'];
    }

    // update quantity
    public function updateQuantity($c_id, $p_id, $qty) {
        $conn = $this->db_conn();
        $qty = max(0, intval($qty));
        if ($qty === 0) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
            $stmt->bind_param("ii", $c_id, $p_id);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok ? ['status'=>'success','message'=>'Item removed'] : ['status'=>'error','message'=>'Failed to remove item'];
        }
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("iii", $qty, $c_id, $p_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok ? ['status'=>'success','message'=>'Quantity updated'] : ['status'=>'error','message'=>'Failed to update quantity'];
    }

    // remove single item
    public function removeFromCart($c_id, $p_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("ii", $c_id, $p_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok ? ['status'=>'success','message'=>'Removed from cart'] : ['status'=>'error','message'=>'Failed to remove'];
    }

    // empty cart for user
    public function emptyCart($c_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ?");
        $stmt->bind_param("i", $c_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok ? ['status'=>'success','message'=>'Cart emptied'] : ['status'=>'error','message'=>'Failed to empty cart'];
    }

    // get cart items with product details (join products)
    public function getCartItems($c_id) {
        $conn = $this->db_conn();
        $sql = "SELECT c.p_id, c.qty, p.product_title, p.product_price, p.product_image
                FROM cart c
                JOIN products p ON c.p_id = p.product_id
                WHERE c.c_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $c_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // total count / total amount helper
    public function getCartSummary($c_id) {
        $items = $this->getCartItems($c_id);
        $totalQty = 0;
        $totalAmount = 0.0;
        foreach ($items as $it) {
            $totalQty += intval($it['qty']);
            $totalAmount += floatval($it['qty']) * floatval($it['product_price']);
        }
        return ['items'=>$items,'total_qty'=>$totalQty,'total_amount'=>$totalAmount];
    }
}
