<?php
// admin/product.php
require_once __DIR__ . '/../settings/core.php';
// allow admins/pharmaceutical companies (role 1) to view/manage their products
// With new role mapping: 1=pharmaceutical company, 2=customer, 3=physician
if (!isLoggedIn() || !isAdmin()) {
    $return = urlencode('../admin/product.php');
    header('Location: ../Login/login.php?redirect=' . $return); exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Your Medications</h2>
            <a href="product_add.php" class="btn btn-primary">Add Medications</a>
        </div>

        <div id="productList"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/product.js"></script>

</body>
</html>