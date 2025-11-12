<?php
// controllers/cart_controller.php
// require_once __DIR__ . '/../classes/cart_class.php';

// class cart_controller {
//     private $model;
//     public function __construct() {
//         $this->model = new cart_class();
//     }

//     public function add_to_cart_ctr($c_id, $p_id, $qty = 1) {
//         return $this->model->addToCart($c_id, $p_id, $qty);
//     }

//     public function update_cart_item_ctr($c_id, $p_id, $qty) {
//         return $this->model->updateQuantity($c_id, $p_id, $qty);
//     }

//     public function remove_from_cart_ctr($c_id, $p_id) {
//         return $this->model->removeFromCart($c_id, $p_id);
//     }

//     public function empty_cart_ctr($c_id) {
//         return $this->model->emptyCart($c_id);
//     }

//     public function get_user_cart_ctr($c_id) {
//         return $this->model->getCartItems($c_id);
//     }

//     public function get_cart_summary_ctr($c_id) {
//         return $this->model->getCartSummary($c_id);
//     }
// }



require_once __DIR__ . '/../classes/cart_class.php';

class cart_controller {

    private $cart;

    public function __construct() {
        $this->cart = new cart_class();
    }

    // Add product to cart
    public function add_to_cart_ctr($c_id, $p_id, $qty) {
        return $this->cart->addToCart($c_id, $p_id, $qty);
    }

    // Update cart item quantity
    public function update_cart_item_ctr($c_id, $p_id, $qty) {
        return $this->cart->updateCartItem($c_id, $p_id, $qty);
    }

    // Remove one product
    public function remove_from_cart_ctr($c_id, $p_id) {
        return $this->cart->removeCartItem($c_id, $p_id);
    }

    // Empty user cart
    public function empty_cart_ctr($c_id) {
        return $this->cart->emptyCart($c_id);
    }

    // Get all user cart items
    public function get_user_cart_ctr($c_id) {
        return $this->cart->getCartItems($c_id);
    }

    // Count total items in cart
    public function count_user_cart_ctr($c_id) {
        return $this->cart->countCartItems($c_id);
    }
}
