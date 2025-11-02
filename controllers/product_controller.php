<?php
// controllers/product_controller.php
require_once __DIR__ . '/../classes/product_class.php';

class product_controller {
    private $model;

    public function __construct(){
        $this->model = new product_class();

    }

    //add product controller
    public function add_product_ctr($data) {
        $product_cat = intval($data['product_cat'] ?? 0);
        $product_brand = intval($data['product_brand'] ?? 0);
        $product_title = trim($data['product_title'] ?? '');
        $product_price = floatval($data['product_price'] ?? 0);
        $product_desc = trim($data['product_desc'] ?? '');
        $product_keywords = trim($data['product_keywords'] ?? '');
        $created_by = intval($data['created_by'] ?? 0);
        $product_image = $data['product_image'] ?? null;

        if($product_cat <= 0 || $product_title === '' || $product_price <= 0) {
            return ['status' => 'error', 'message' => 'Required fields missing or invalid'];

        }
        $insert_id = $this->model->addProduct($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $created_by);

        if ($insert_id) {
            return ['status' => 'success', 'message' => 'Product added', 'product_id'=> $insert_id];

        }
        return ['status' => 'error', 'message' => 'Failed to add product'];
    }

    //update product
    public function update_product_ctr($data) {
        $product_id = intval($data['product_id'] ?? 0);
        $product_cat = intval($data['product_cat'] ?? 0);
        $product_brand = intval($data['product_brand'] ?? 0);
        $product_title = trim($data['product_title'] ?? '');
        $product_price = floatval($data['product_price'] ?? 0);
        $product_desc = trim($data['product_desc'] ?? '');
        $product_keywords = trim($data['product_keywords'] ?? '');
        $created_by = intval($data['created_by'] ?? 0);
        $image_path = $data['product_image'] ?? null;

        if($product_id <= 0) return ['status' => 'error', 'message' => 'invalid product id'];
        $ok = $this->model->updateProduct($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_keywords, $created_by, $image_path);

        return $ok ? ['status' => 'success', 'message' => 'Product updated']:['status' => 'error', 'message' => 'update failed'];


    }
    public function fetch_products_ctr($created_by) {
        $rows = $this->model->getProductsByUser($created_by);
        return ['status' => 'success', 'data' => $rows];
    }

    public function get_product_ctr($product_id, $created_by) {
        $row = $this->model->getProductById($product_id, $created_by);
        return $row ? ['status' => 'success', 'data' => $row] : ['status' => 'error', 'message' => 'Not found'];
    }

    public function upload_image_ctr($product_id, $image_path) {
        $ok = $this->model->setProductImage($product_id, $image_path);
        return $ok ? ['status' => 'success', 'message' => 'Image saved'] : ['status' => 'error', 'message' => 'Failed to save image'];
    }

    public function delete_product_ctr($product_id, $created_by) {
        $ok = $this->model->deleteProduct($product_id, $created_by);
        return $ok ? ['status' => 'success', 'message' => 'Deleted'] : ['status' => 'error', 'message' => 'Delete failed'];
    }
    

    #fetch all products
    public function fetch_products_public_ctr() {
        $rows = $this->model->view_all_products();
        return ['status'=>'success', 'data'=>$rows];
    }

    //fetch single product for customer view
    public function fetch_single_product_public_ctr($product_id) {
        $row = $this->model->view_single_product($product_id);
        return $row ? ['status' => 'success', 'data' => $row] : ['status' => 'error', 'message' => 'Product not found'];
    }

    //filter by category
    public function filter_by_category_ctr($cat_id) {
        $rows = $this->model->filter_products_by_category($cat_id);
        return ['status'=>'success', 'data'=>$rows];
    }

    //filter by brand
    public function filter_by_brand_ctr($brand_id) {
        $rows = $this->model->filter_products_by_brand($brand_id);
        return ['status'=>'success', 'data'=>$rows];
    }

    //search products
    //filter by category
    public function search_products_ctr($query = '', $cat_id = 0, $brand_id = 0, $page = 1, $pageSize = 10) {
    return $this->model->search_products($query, $cat_id, $brand_id, $page, $pageSize);
    }


    public function fetch_products_filtered_ctr($query = '', $cat_id = 0, $brand_id = 0) {
        // forward the model response directly to avoid double-wrapping the envelope
        // product_class::fetch_products_filtered already returns ['status' => 'success', 'data' => [...]]
        return $this->model->fetch_products_filtered($query, $cat_id, $brand_id);
    }




        
    
}