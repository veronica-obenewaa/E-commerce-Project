<?php

require_once __DIR__ . '/../classes/brand_class.php';

class BrandController {
    private $brandModel;

    public function __construct() {
        $this->brandModel = new brand_class();
    }

    public function add_brand_ctr($data) {
        $name = trim($data['brand_name'] ?? '');
        if($name === '') return ['status' => 'error', 'message' => 'Brand name is required'];
        if(strlen($name) > 100) return ['status' => 'error', 'message' => 'Brand name is too long'];
        $ok = $this->brandModel->addBrand($name);
        return $ok ? ['status' => 'success', 'message' => 'brand created'] : ['status' => 'error', 'message' => 'Failed to add brand'];
    }

    #fetch all brands
    public function fetch_brand_ctr() {
        $result = $this->brandModel->fetchBrand();
        return ['status' => 'success', 'data' => $result];
    }

    // fetch all brands (no owner filter)
    public function fetch_all_brands_ctr() {
        $rows = $this->brandModel->fetchAllBrands();
        if (!is_array($rows)) $rows = [];
        return ['status' => 'success', 'data' => $rows];
    }


    #update brands controller
    public function update_brand_ctr($data) {
        $brand_id = intval($data['brand_id'] ?? 0);
        $name = trim($data['brand_name'] ?? '');
        if(!$brand_id) return ['status' => 'error', 'message' => 'invalid brand'];
        if($name === '') return ['status' => 'error', 'message' => 'Brand name is required'];
        $ok = $this->brandModel->editBrand($brand_id, $name);
        return $ok ? ['status' => 'success', 'message' => 'Brand updated'] : ['status' => 'error', 'message' => 'Failed to update brand'];
    }

    #delete brand controller
    public function delete_brand_ctr($brand_id) {
        $brand_id = intval($brand_id);
        if(!$brand_id) return ['status' => 'error', 'message' => 'invalid brand'];
        $ok = $this->brandModel->deleteBrand($brand_id);
        return $ok ? ['status' => 'success', 'message' => 'brand deleted'] : ['status' => 'error', 'message' => 'Failed to delete brand'];
    }
   
}