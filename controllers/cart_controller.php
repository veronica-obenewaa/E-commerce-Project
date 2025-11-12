<?php
// controllers/cart_controller.php
require_once __DIR__ . '/../classes/cart_class.php';

class cart_controller {
    private $model;
    public function __construct() {
        $this->model = new cart_class();
    }

    public function add_to_cart_ctr($c_id, $p_id, $qty = 1) {
        return $this->model->addToCart($c_id, $p_id, $qty);
    }

    public function update_cart_item_ctr($c_id, $p_id, $qty) {
        return $this->model->updateQuantity($c_id, $p_id, $qty);
    }

    public function remove_from_cart_ctr($c_id, $p_id) {
        return $this->model->removeFromCart($c_id, $p_id);
    }

    public function empty_cart_ctr($c_id) {
        return $this->model->emptyCart($c_id);
    }

    public function get_user_cart_ctr($c_id) {
        return $this->model->getCartItems($c_id);
    }

    public function get_cart_summary_ctr($c_id) {
        return $this->model->getCartSummary($c_id);
    }
}
