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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --light-bg: #f8f9fa;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 30px;
            border-radius: 0;
            margin-left: -12px;
            margin-right: -12px;
            margin-top: -20px;
        }
        
        .page-header .container-lg {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header-title h2 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 32px;
        }
        
        .header-title p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        
        .btn-add {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
            color: white;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-content {
            padding: 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .product-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
            line-height: 1.3;
            word-break: break-word;
        }
        
        .product-meta {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 12px;
        }
        
        .product-meta span {
            display: block;
            margin-bottom: 4px;
        }
        
        .product-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--success-color);
            margin: 12px 0;
        }
        
        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #e0e0e0;
        }
        
        .btn-action {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .btn-edit {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #2980b9;
        }
        
        .btn-delete {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #7f8c8d;
        }
        
        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .empty-state p {
            font-size: 16px;
            margin: 0;
        }
        
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        
        .search-box {
            flex: 1;
            min-width: 250px;
        }
        
        .search-box input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 14px;
        }
        
        .search-box input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
    </style>
</head>

<body>
    <div class="page-header">
        <div class="container-lg">
            <div class="header-title">
                <h2>
                    <i class="fas fa-pills"></i>
                    Your Medications
                </h2>
                <p>Manage and organize your pharmaceutical products</p>
            </div>
            <a href="product_add.php" class="btn-add">
                <i class="fas fa-plus-circle"></i>
                Add Medication
            </a>
        </div>
    </div>

    <div class="container-lg">
        <div class="filters">
            <div class="search-box">
                <input type="text" class="form-control" id="searchInput" placeholder="Search medications...">
            </div>
        </div>

        <div id="productList" class="product-grid"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Override the product list rendering to use cards
        window.fetchProducts = function() {
            const listEl = document.getElementById('productList');
            if (!listEl) return; 
            
            fetch('../actions/fetch_product_action.php')
            .then(r => r.json())
            .then(json => {
                if (json.status !== 'success') {
                    listEl.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Failed to load medications</p></div>';
                    return;
                }

                const rows = json.data;
                if (!rows || rows.length === 0) {
                    listEl.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><p>No medications yet. <a href="product_add.php" class="text-decoration-none">Create one</a></p></div>';
                    return;
                }

                let html = '';
                rows.forEach(p => {
                    const imgSrc = p.product_image ? `../${p.product_image}` : null;
                    html += `
                        <div class="product-card">
                            <div class="product-image">
                                ${imgSrc ? `<img src="${imgSrc}" alt="${escapeHtml(p.product_title)}">` : '<i class="fas fa-prescription-bottle"></i>'}
                            </div>
                            <div class="product-content">
                                <div class="product-name">${escapeHtml(p.product_title)}</div>
                                <div class="product-meta">
                                    <span><strong>Category:</strong> ${escapeHtml(p.cat_name || 'N/A')}</span>
                                    <span><strong>Brand:</strong> ${escapeHtml(p.brand_name || 'N/A')}</span>
                                </div>
                                <div class="product-price">GH₵ ${parseFloat(p.product_price).toFixed(2)}</div>
                                <div class="product-actions">
                                    <button class="btn-action btn-edit" onclick="editProduct(${p.product_id})">
                                        <i class="fas fa-pencil"></i>
                                        Edit
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteProduct(${p.product_id})">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                listEl.innerHTML = html;
            })
            .catch(err => {
                listEl.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error loading medications</p></div>';
                console.error(err);
            });
        };

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function editProduct(productId) {
            alert('Edit functionality coming soon!');
        }

        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this medication?')) {
                alert('Delete functionality coming soon!');
            }
        }

        // Load products on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchProducts();
        });

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            // Search implementation can be added here
        });
    </script>
    <script src="../js/product.js"></script>
</body>
</html>