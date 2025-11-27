<?php
// classes/cart_class.php
require_once __DIR__ . '/../settings/db_class.php';

// class cart_class extends db_connection {

//     // add product to cart (if exists, increment qty)
//     public function addToCart($c_id, $p_id, $qty = 1) {
//         $conn = $this->db_conn();
//         // check if exists
//         $stmt = $conn->prepare("SELECT qty FROM cart WHERE c_id = ? AND p_id = ? LIMIT 1");
//         $stmt->bind_param("ii", $c_id, $p_id);
//         $stmt->execute();
//         $res = $stmt->get_result();
//         if ($row = $res->fetch_assoc()) {
//             $newQty = max(1, intval($row['qty']) + intval($qty));
//             $stmt->close();
//             $u = $conn->prepare("UPDATE cart SET qty = ?, added_at = NOW() WHERE c_id = ? AND p_id = ?");
//             $u->bind_param("iii", $newQty, $c_id, $p_id);
//             $ok = $u->execute();
//             $u->close();
//             return $ok ? ['status'=>'success','message'=>'Cart updated','qty'=>$newQty] : ['status'=>'error','message'=>'Failed to update cart'];
//         }
//         $stmt->close();

//         $ins = $conn->prepare("INSERT INTO cart (p_id, c_id, qty) VALUES (?, ?, ?)");
//         $ins->bind_param("iii", $p_id, $c_id, $qty);
//         $ok = $ins->execute();
//         $ins->close();
//         return $ok ? ['status'=>'success','message'=>'Added to cart'] : ['status'=>'error','message'=>'Failed to add to cart'];
//     }

//     // update quantity
//     public function updateQuantity($c_id, $p_id, $qty) {
//         $conn = $this->db_conn();
//         $qty = max(0, intval($qty));
//         if ($qty === 0) {
//             $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
//             $stmt->bind_param("ii", $c_id, $p_id);
//             $ok = $stmt->execute();
//             $stmt->close();
//             return $ok ? ['status'=>'success','message'=>'Item removed'] : ['status'=>'error','message'=>'Failed to remove item'];
//         }
//         $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
//         $stmt->bind_param("iii", $qty, $c_id, $p_id);
//         $ok = $stmt->execute();
//         $stmt->close();
//         return $ok ? ['status'=>'success','message'=>'Quantity updated'] : ['status'=>'error','message'=>'Failed to update quantity'];
//     }

//     // remove single item
//     public function removeFromCart($c_id, $p_id) {
//         $conn = $this->db_conn();
//         $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
//         $stmt->bind_param("ii", $c_id, $p_id);
//         $ok = $stmt->execute();
//         $stmt->close();
//         return $ok ? ['status'=>'success','message'=>'Removed from cart'] : ['status'=>'error','message'=>'Failed to remove'];
//     }

//     // empty cart for user
//     public function emptyCart($c_id) {
//         $conn = $this->db_conn();
//         $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ?");
//         $stmt->bind_param("i", $c_id);
//         $ok = $stmt->execute();
//         $stmt->close();
//         return $ok ? ['status'=>'success','message'=>'Cart emptied'] : ['status'=>'error','message'=>'Failed to empty cart'];
//     }

//     // get cart items with product details (join products)
//     public function getCartItems($c_id) {
//         $conn = $this->db_conn();
//         $sql = "SELECT c.p_id, c.qty, p.product_title, p.product_price, p.product_image
//                 FROM cart c
//                 JOIN products p ON c.p_id = p.p_id
//                 WHERE c.c_id = ?";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("i", $c_id);
//         $stmt->execute();
//         $res = $stmt->get_result();
//         $rows = $res->fetch_all(MYSQLI_ASSOC);
//         $stmt->close();
//         return $rows;
//     }

//     // total count / total amount helper
//     public function getCartSummary($c_id) {
//         $items = $this->getCartItems($c_id);
//         $totalQty = 0;
//         $totalAmount = 0.0;
//         foreach ($items as $it) {
//             $totalQty += intval($it['qty']);
//             $totalAmount += floatval($it['qty']) * floatval($it['product_price']);
//         }
//         return ['items'=>$items,'total_qty'=>$totalQty,'total_amount'=>$totalAmount];
//     }
// }



class cart_class extends db_connection {

    // Check if product already exists in cart
    public function checkProductInCart($c_id, $p_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT * FROM cart WHERE c_id = ? AND p_id = ?");
        if (!$stmt) {
            // prepare failed (DB error) - return null to indicate "not found / no existing"
            error_log('DB prepare failed in checkProductInCart: ' . $conn->error);
            return null;
        }
        $stmt->bind_param("ii", $c_id, $p_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    // Add product to cart or increase qty if exists
    public function addToCart($c_id, $p_id, $qty) {
        $existing = $this->checkProductInCart($c_id, $p_id);
        $conn = $this->db_conn();

        if ($existing) {
            // Update qty instead of inserting duplicate
            $stmt = $conn->prepare("UPDATE cart SET qty = qty + ? WHERE c_id = ? AND p_id = ?");
            if (!$stmt) {
                error_log('DB prepare failed in addToCart (update): ' . $conn->error);
                return false;
            }
            $stmt->bind_param("iii", $qty, $c_id, $p_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO cart (p_id, c_id, qty) VALUES (?, ?, ?)");
            if (!$stmt) {
                error_log('DB prepare failed in addToCart (insert): ' . $conn->error);
                return false;
            }
            $stmt->bind_param("iii", $p_id, $c_id, $qty);
        }

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    //Update quantity of a cart item
    public function updateCartItem($c_id, $p_id, $qty) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("UPDATE cart SET qty = ? WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("iii", $qty, $c_id, $p_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Remove one product from cart
    public function removeCartItem($c_id, $p_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ? AND p_id = ?");
        $stmt->bind_param("ii", $c_id, $p_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Empty entire cart for a user
    public function emptyCart($c_id) {
        $conn = $this->db_conn();
        if (!$c_id) {
            error_log("Warning: emptyCart called with null/empty c_id");
            return false;
        }
        
        // First, delete items with the specific customer ID
        $stmt = $conn->prepare("DELETE FROM cart WHERE c_id = ?");
        if (!$stmt) {
            error_log('DB prepare failed in emptyCart: ' . $conn->error);
            return false;
        }
        $stmt->bind_param("i", $c_id);
        $ok = $stmt->execute();
        $affected = $stmt->affected_rows;
        
        if (!$ok) {
            error_log("Failed to execute emptyCart delete for c_id=$c_id: " . $stmt->error);
        } else {
            error_log("Successfully deleted from cart for c_id=$c_id. Rows affected: " . $affected);
        }
        $stmt->close();
        
        return $ok;
    }

    // Retrieve all items for a user's cart
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

    //Count total items in user's cart
    public function countCartItems($c_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT SUM(qty) AS total_items FROM cart WHERE c_id = ?");
        $stmt->bind_param("i", $c_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $count = $res->fetch_assoc()['total_items'] ?? 0;
        $stmt->close();
        return (int)$count;
    }
}
