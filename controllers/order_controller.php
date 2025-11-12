<?php
// controllers/order_controller.php
require_once __DIR__ . '/../classes/order_class.php';

class order_controller {
    private $model;
    public function __construct() {
        $this->model = new order_class();
    }

    public function create_order_ctr($customer_id, $invoice_no, $total_amount) {
        return $this->model->createOrder($customer_id, $invoice_no, $total_amount);
    }

    public function add_order_details_ctr($order_id, $items) {
        return $this->model->addOrderDetails($order_id, $items);
    }

    public function record_payment_ctr($order_id, $amount) {
        return $this->model->recordPayment($order_id, $amount);
    }

    public function get_orders_by_user_ctr($customer_id) {
        return $this->model->getOrdersByUser($customer_id);
    }
}
