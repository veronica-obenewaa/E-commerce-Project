<?php
// actions/update_product_action.php
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

$data = [
    'product_id' => intval($_POST['product_id'] ?? 0),
    'product_cat' => intval($_POST['product_cat'] ?? 0),
    'product_brand' => intval($_POST['product_brand'] ?? 0),
    'product_title' => trim($_POST['product_title'] ?? ''),
    'product_price' => floatval($_POST['product_price'] ?? 0),
    'product_desc' => trim($_POST['product_desc'] ?? ''),
    'product_keywords' => trim($_POST['product_keywords'] ?? ''),
    'created_by' => $created_by
    
];

$ctrl = new product_controller();

//optional image in separate upload or same request
if(!empty($_FILES['product_image'])&&$_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['product_image'];
    $product_id = $data['product_id'];

    $uploadBase = __DIR__ . '/../uploads';
    $userDir = $uploadBase . '/u' . $created_by;
    $productDir = $userDir . '/p' . $product_id;

    if(!is_dir($userDir)) mkdir($userDir, 0755, true);
    if(!is_dir($productDir)) mkdir($productDir, 0755, true);

    $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', basename($file['name']));
    $dest = $productDir . '/' . $safeName;

    if(move_uploaded_file($file['tmp_name'], $dest)) {
        $relative = 'uploads/u' . $created_by . '/p' . $product_id . '/' . $safeName;
        $data['product_image'] = $relative;
    }
}

$res = $ctrl->update_product_ctr($data);
echo json_encode($res);
exit;