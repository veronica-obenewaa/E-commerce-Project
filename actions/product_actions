<?php
// actions/product_actions.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

header('Content-Type: application/json');

// init controllers
$productCtrl = new product_controller();
$catCtrl = new CategoryController();
$brandCtrl = new BrandController();

$action = $_GET['action'] ?? 'list';

//Return category & brand lists for filters
if ($action === 'filters') {
    $cats = $catCtrl->fetch_categories_ctr(getUserId())['data'] ?? [];
    $brands = $brandCtrl->fetch_brand_ctr(getUserId())['data'] ?? [];
    echo json_encode(['status' => 'success', 'categories' => $cats, 'brands' => $brands]);
    exit();
}

//Single product
if ($action === 'view_single') {
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) { 
        echo json_encode(['status' => 'error', 'message' => 'Invalid product id']); 
        exit(); 
    }
    // Customers see everything; admins see their own — product controller handles access
    $res = $productCtrl->get_product_ctr($id, getUserId());
    echo json_encode($res);
    exit();
}

//Product list (search/filter/pagination)
if ($action === 'list') {
    $q = trim($_GET['q'] ?? '');
    $cat_id = intval($_GET['cat_id'] ?? 0);
    $brand_id = intval($_GET['brand_id'] ?? 0);
    $page = max(1, intval($_GET['page'] ?? 1));
    $page_size = max(1, intval($_GET['page_size'] ?? 12));
    $sort = $_GET['sort'] ?? 'newest';

    // Fetch directly from DB with filters
    $res = $productCtrl->fetch_products_filtered_ctr($q, $cat_id, $brand_id);
    if ($res['status'] !== 'success') {
        echo json_encode(['status' => 'error', 'message' => 'Unable to fetch products']);
        exit();
    }

    $products = $res['data'] ?? [];

    // Sort (optional)
    usort($products, function($a, $b) use ($sort) {
        if ($sort === 'price_asc') return floatval($a['product_price']) <=> floatval($b['product_price']);
        if ($sort === 'price_desc') return floatval($b['product_price']) <=> floatval($a['product_price']);
        return intval($b['product_id']) <=> intval($a['product_id']); // newest
    });

    // Pagination
    $total = count($products);
    $offset = ($page - 1) * $page_size;
    $paged = array_slice($products, $offset, $page_size);

    echo json_encode([
        'status' => 'success',
        'total' => $total,
        'page' => $page,
        'page_size' => $page_size,
        'data' => $paged
    ]);
    exit();
}

//Fallback for invalid action
echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
exit();
?>
