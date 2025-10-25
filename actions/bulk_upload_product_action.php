<?php
// actions/bulk_upload_product_action.php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/product_controller.php';
require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

header('Content-Type: application/json');

if (!isLoggedIn() || !isAdmin()) {
    echo json_encode(['status'=>'error','message'=>'Unauthorized']); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['zipfile'])) {
    echo json_encode(['status'=>'error','message'=>'No file uploaded']); 
    exit;
}

$created_by = getUserId();
$zipFile = $_FILES['zipfile'];
if ($zipFile['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status'=>'error','message'=>'Upload error']); 
    exit;
}

// Create temporary extraction folder
$tmpBase = sys_get_temp_dir();
$uniq = 'bulk_' . $created_by . '_' . time();
$extractDir = $tmpBase . '/' . $uniq;
mkdir($extractDir, 0755, true);

$zipPath = $extractDir . '/' . basename($zipFile['name']);
move_uploaded_file($zipFile['tmp_name'], $zipPath);

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    echo json_encode(['status'=>'error','message'=>'Cannot open ZIP']); cleanup($extractDir); 
    exit;
}
$zip->extractTo($extractDir);
$zip->close();

// find first xlsx file
$xlsx = null;
foreach (glob($extractDir . '/*.xlsx') as $f) { $xlsx = $f; break; }
if (!$xlsx) {
    // try look in subfolders
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($extractDir));
    foreach ($files as $file) {
        if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'xlsx') { $xlsx = $file->getPathname(); break; }
    }
}

if (!$xlsx) { echo json_encode(['status'=>'error','message'=>'No .xlsx found']); cleanup($extractDir); exit; }

try {
    $spreadsheet = IOFactory::load($xlsx);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, true);
} catch (Exception $e) {
    echo json_encode(['status'=>'error','message'=>'Excel read error: '.$e->getMessage()]); cleanup($extractDir); exit;
}

$ctrl = new product_controller();
$success = 0; $failed = 0; $errors = [];

foreach ($rows as $i => $r) {
    if ($i == 1) continue; // header
    // require product_title in col A
    $title = trim($r['A'] ?? '');
    if ($title === '') continue;
    $price = floatval($r['B'] ?? 0);
    $desc = trim($r['C'] ?? '');
    $cat_id = intval($r['D'] ?? 0);
    $brand_id = intval($r['E'] ?? 0);
    $keywords = trim($r['F'] ?? '');
    $imageFile = trim($r['G'] ?? '');

    // check image exists in extracted folder (search)
    $imagePath = null;
    if ($imageFile !== '') {
        // try direct file
        $direct = $extractDir . '/' . $imageFile;
        if (file_exists($direct)) $imagePath = $direct;
        else {
            // search recursively
            $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($extractDir));
            foreach ($it as $file) {
                if (strtolower($file->getFilename()) === strtolower($imageFile)) { $imagePath = $file->getPathname(); break; }
            }
        }
    }

    // Add product to DB (no image path yet)
    $addRes = $ctrl->add_product_ctr([
        'product_cat' => $cat_id,
        'product_brand' => $brand_id,
        'product_title' => $title,
        'product_price' => $price,
        'product_desc' => $desc,
        'product_keywords' => $keywords,
        'created_by' => $created_by,
        'product_image' => null
    ]);

    if ($addRes['status'] !== 'success' || empty($addRes['product_id'])) {
        $failed++;
        $errors[] = "Row {$i}: failed to add product - " . ($addRes['message'] ?? 'unknown');
        continue;
    }

    $product_id = $addRes['product_id'];

    if ($imagePath) {
        // create destination folder
        $userDir = __DIR__ . '/../uploads/u' . $created_by;
        $productDir = $userDir . '/p' . $product_id;
        if (!is_dir($userDir)) mkdir($userDir, 0755, true);
        if (!is_dir($productDir)) mkdir($productDir, 0755, true);

        $safeName = time().'_'.preg_replace('/[^A-Za-z0-9\-\_\.]/','_',basename($imagePath));
        $dest = $productDir . '/' . $safeName;
        if (@copy($imagePath, $dest)) {
            $relative = 'uploads/u' . $created_by . '/p' . $product_id . '/' . $safeName;
            $ctrl->upload_image_ctr($product_id, $relative);
            $success++;
        } else {
            $failed++;
            $errors[] = "Row {$i}: image copy failed for {$imageFile}";
        }
    } else {
        $success++; // product exists without image
    }
}

// cleanup
cleanup($extractDir);

echo json_encode(['status'=>'success','message'=>"Bulk processed. success={$success}, failed={$failed}", 'errors'=>$errors]);
exit;

// helper
function cleanup($dir) {
    if (!is_dir($dir)) return;
    $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($files as $file) {
        if ($file->isDir()) rmdir($file->getPathname());
        else @unlink($file->getPathname());
    }
    @rmdir($dir);
}
