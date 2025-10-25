<?php

require_once __DIR__ . '/../classes/brand_class.php';

class BrandController {
    private $brandModel;

    public function __construct() {
        $this->brandModel = new brand_class();
    }

    public function add_brand_ctr($data) {
        $name = trim($data['brand_name'] ?? '');
        $created_by = intval($data['created_by'] ?? 0);

        if($name === '') return ['status' => 'error', 'message' => 'Brand name is required'];
        if(strlen($name) > 100) return ['status' => 'error', 'message' => 'Brand name is too long'];
        //if($this->model->categoryNameExists($name)) return ['status' => 'error', 'message' => 'Category name already']

        $ok = $this->brandModel->addBrand($name, $created_by);
        return $ok ? ['status' => 'success', 'message' => 'brand created'] : ['status' => 'error', 'message' => 'Failed to add brand'];

    }

    #fectch brands
    public function fetch_brand_ctr($created_by) {
        $rows = $this->brandModel->fetchBrand($created_by);
        if (!is_array($rows)) $rows = [];
        return ['status' => 'success', 'data' => $rows];
    }

    #update brands controller
    public function update_brand_ctr($data) {
        $brand_id = intval($data['brand_id'] ?? 0);
        $name = trim($data['brand_name'] ?? '');
        $created_by = intval($data['created_by'] ?? 0);

        if(!$brand_id) return ['status' => 'error', 'message' => 'invalid brand'];
        if($name === '') return ['status' => 'error', 'message' => 'Brand name is required'];

        $ok = $this->brandModel->editBrand($brand_id, $name, $created_by);
        return $ok ? ['status' => 'success', 'message' => 'Brand updated'] : ['staus' => 'error', 'message' => 'Failed to update brand'];

    }

    #delete category controller
    public function delete_brand_ctr($brand_id, $created_by) {
        $brand_id = intval($brand_id);
        $created_by = intval($created_by);
        if(!$brand_id) return ['status' => 'error', 'message' => 'invalid brand'];
        
        $ok = $this->brandModel->deleteBrand($brand_id, $created_by);
        return $ok ? ['status' => 'success', 'message' => 'brand deleted'] : ['status' => 'error', 'message' => 'Failed to delete brand'];

    }
   
}