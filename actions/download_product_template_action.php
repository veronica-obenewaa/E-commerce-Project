<?php
// actions/download_product_template.php
//require_once __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../controllers/category_controller.php';
require_once __DIR__ . '/../controllers/brand_controller.php';

if (!isLoggedIn() || !isAdmin()) {
    header('HTTP/1.1 403 Forbidden'); exit('Unauthorized');
}

require_once __DIR__ . '/../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$catCtrl = new CategoryController();
$brandCtrl = new BrandController();

$cats = $catCtrl->fetch_categories_ctr(getUserId())['data'] ?? [];
$brands = $brandCtrl->fetch_brand_ctr(getUserId())['data'] ?? [];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Product Template');

$headers = ['product_title','product_price','product_desc','product_cat','product_brand','product_keywords','product_image_filename'];
$col = 'A';
foreach ($headers as $h) {
    $sheet->setCellValue($col . '1', $h);
    $col++;
}
$sheet->fromArray(['Example Paracetamol', '12.50', 'Pain relief','1','1','pain,fever','paracetamol.jpg'], NULL, 'A2');

$row = 4;
$sheet->setCellValue("A{$row}", '--- Categories (id, name) ---');
$row++;

if (is_array($cats)) {
    foreach ($cats as $c) {
        if (is_array($c) && isset($c['cat_id'], $c['cat_name'])) {
            $sheet->setCellValue("A{$row}", $c['cat_id']);
            $sheet->setCellValue("B{$row}", $c['cat_name']);
            $row++;
        }
    }
} else {
    $sheet->setCellValue("A{$row}", 'No category data available');
    $row++;
}

$row += 1;
$sheet->setCellValue("A{$row}", '--- Brands (id, name) ---');
$row++;

if (is_array($brands)) {
    foreach ($brands as $b) {
        if (is_array($b) && isset($b['brand_id'], $b['brand_name'])) {
            $sheet->setCellValue("A{$row}", $b['brand_id']);
            $sheet->setCellValue("B{$row}", $b['brand_name']);
            $row++;
        }
    }
} else {
    $sheet->setCellValue("A{$row}", 'No brand data available');
}


// Style headers (optional)
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(20);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(40);

// write to temp and stream
$tmpFile = sys_get_temp_dir() . '/product_template_' . time() . '.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($tmpFile);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="product_bulk_template.xlsx"');
readfile($tmpFile);
@unlink($tmpFile);
exit;