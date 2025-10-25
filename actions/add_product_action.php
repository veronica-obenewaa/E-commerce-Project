<?php
// actions/add_product_action.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request method']);
    exit;
}

$created_by = getUserId();

// sanitize basic fields
$data = [
    'product_cat' => intval($_POST['product_cat'] ?? 0),
    'product_brand' => intval($_POST['product_brand'] ?? 0),
    'product_title' => trim($_POST['product_title'] ?? ''),
    'product_price' => floatval($_POST['product_price'] ?? 0),
    'product_desc' => trim($_POST['product_desc'] ?? ''),
    'product_keywords' => trim($_POST['product_keywords'] ?? ''),
    'created_by' => $created_by
];

$ctrl = new product_controller();
$addRes = $ctrl->add_product_ctr($data);

if ($addRes['status'] === 'success' && isset($addRes['product_id'])) {
    $product_id = $addRes['product_id'];

    // Handle optional image upload in same request
    if (!empty($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['product_image'];

        // create product upload dir inside uploads
        $uploadBase = __DIR__ . '/../uploads';
        $userDir = $uploadBase . '/u' . $created_by;
        $productDir = $userDir . '/p' . $product_id;

        if (!is_dir($userDir)) mkdir($userDir, 0755, true);
        if (!is_dir($productDir)) mkdir($productDir, 0755, true);

        $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', basename($file['name']));
        $dest = $productDir . '/' . $safeName;

        // move uploaded file
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $relative = 'uploads/u' . $created_by . '/p' . $product_id . '/' . $safeName;
            // update DB
            $ctrl->upload_image_ctr($product_id, $relative);
        }
    }

    echo json_encode(['status'=>'success','message'=>'Product created','product_id'=>$product_id]);
    exit;
}

// if failed:
echo json_encode($addRes);
exit;
