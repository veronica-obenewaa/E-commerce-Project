<?php

require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';

header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Invalid request']);
    exit;
}

$created_by = getUserId();
$product_id = intval($_POST['product_id'] ?? 0);

if ($product_id <= 0 || empty($_FILES['product_image'])) {
    echo json_encode(['status'=>'error','message'=>'Missing product id or file']);
    exit;
}

$file = $_FILES['product_image'];

$uploadBase = __DIR__ . '/../uploads';
$userDir = $uploadBase . '/u' . $created_by;
$productDir = $userDir . '/p' . $product_id;

if (!is_dir($userDir)) mkdir($userDir, 0755, true);
if (!is_dir($productDir)) mkdir($productDir, 0755, true);

$safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', basename($file['name']));
$dest = $productDir . '/' . $safeName;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['status'=>'error','message'=>'Failed to save file']);
    exit;
}

// ensure file is inside uploads directory
$realDest = realpath($dest);
$realUploads = realpath($uploadBase);
if (strpos($realDest, $realUploads) !== 0) {
    // path traversal or wrong location
    @unlink($dest);
    echo json_encode(['status'=>'error','message'=>'Invalid upload location']);
    exit;
}

$relative = 'uploads/u' . $created_by . '/p' . $product_id . '/' . $safeName;
$ctrl = new product_controller();
$update = $ctrl->upload_image_ctr($product_id, $relative);
echo json_encode($update);
exit;
