<?php

require_once __DIR__ . '/../classes/category_class.php';

class CategoryController {
    private $model;

    public function __construct() {
        $this->model = new category_class();
    }

    public function add_category_ctr($data) {
        $name = trim($data['cat_name'] ?? '');
        $created_by = intval($data['created_by'] ?? 0);

        if($name === '') return ['status' => 'error', 'message' => 'Category name is required'];
        if(strlen($name) > 100) return ['status' => 'error', 'message' => 'Category name is too long'];
        //if($this->model->categoryNameExists($name)) return ['status' => 'error', 'message' => 'Category name already']

        $ok = $this->model->addCategory($name, $created_by);
        return $ok ? ['status' => 'success', 'message' => 'Category created'] : ['staus' => 'error', 'message' => 'Failed to add category'];

    }

    #fectch category
    public function fetch_categories_ctr($customer_id) {
        $rows = $this->model->getCategoryByUser($customer_id);
        if (!is_array($rows)) $rows = [];
        return ['status' => 'success', 'data' => $rows];
    }

    #update category controller
    public function update_category_ctr($data) {
        $cat_id = intval($data['cat_id'] ?? 0);
        $name = trim($data['cat_name'] ?? '');
        $customer_id = intval($data['customer_id'] ?? 0);

        if(!$cat_id) return ['status' => 'error', 'message' => 'invalid category'];
        if($name === '') return ['status' => 'error', 'message' => 'Category name is required'];

        $ok = $this->model->editCategory($cat_id, $name, $customer_id);
        return $ok ? ['status' => 'success', 'message' => 'Category updated'] : ['staus' => 'error', 'message' => 'Failed to update category'];

    }

    #delete category controller
    public function delete_category_ctr($cat_id, $customer_id) {
        $cat_id = intval($cat_id);
        $customer_id = intval($customer_id);
        if(!$cat_id) return ['status' => 'error', 'message' => 'invalid category'];
        
        $ok = $this->model->deleteCategory($cat_id, $customer_id);
        return $ok ? ['status' => 'success', 'message' => 'Category deleted'] : ['staus' => 'error', 'message' => 'Failed to delete category'];

    }
   
}