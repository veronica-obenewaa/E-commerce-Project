<?php
require_once __DIR__ . '/../settings/core.php';

// protect route
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../Login/login.php');
    exit();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Category Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #025d0aff;
            --secondary-color: #27ae60;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --light-bg: #f8f9fa;
        }
        
        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 30px;
            border-radius: 0;
        }
        
        .page-header h2 {
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 32px;
        }
        
        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 15px;
        }
        
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
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
        
        .category-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .category-card {
            background: white;
            border-radius: 10px;
            padding: 500px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--secondary-color);
        }
        
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .category-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            word-break: break-word;
        }
        
        .category-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        
        .btn-edit, .btn-delete {
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-edit {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
        }
        
        .modal-title {
            font-weight: 700;
        }
        
        .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>
</head>
<body>
    <div class="page-header">
        <h2>
            <i class="fas fa-folder-open"></i>
            Manage Categories
        </h2>
        <p>Organize your medications by category</p>
    </div>

    <div class="container-lg">
        <div class="controls">
            <div>
                <h5 style="margin: 0; color: var(--primary-color);">
                    <i class="fas fa-list"></i>
                    Categories List
                </h5>
            </div>
            <a href="category_add.php" class="btn-add">
                <i class="fas fa-plus-circle"></i>
                Add New Category
            </a>
        </div>

        <div id="categoryList" class="category-list"></div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="updateCategoryForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit"></i>
                        Edit Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="updateMsg"></div>
                    <input type="hidden" name="cat_id" id="update_category_id">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="cat_name" id="update_category_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Override the category list rendering to use cards
        const originalFetchCategories = window.fetchCategories;
        window.fetchCategories = function() {
            const listEl = document.getElementById('categoryList');
            if (!listEl) return; 
            
            fetch('../actions/fetch_category_action.php')
            .then(r => r.json())
            .then(json => {
                if (json.status !== 'success') {
                    listEl.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Failed to load categories</p></div>';
                    return;
                }

                const rows = json.data;
                if (!rows.length) {
                    listEl.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><p>No categories yet. <a href="category_add.php">Create one</a></p></div>';
                    return;
                }

                let html = '';
                rows.forEach(r => {
                    html += `
                        <div class="category-card">
                            <div class="category-name">${escapeHtml(r.cat_name)}</div>
                            <div class="category-actions">
                                <button class="btn-edit" onclick="openEdit(this)" data-id="${r.cat_id}" data-name="${escapeHtml(r.cat_name)}">
                                    <i class="fas fa-pencil"></i>
                                    Edit
                                </button>
                                <button class="btn-delete" onclick="doDelete(this)" data-id="${r.cat_id}">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    `;
                });
                listEl.innerHTML = html;
            })
            .catch(err => {
                listEl.innerHTML = '<div class="empty-state"><i class="fas fa-exclamation-circle"></i><p>Error loading categories</p></div>';
                console.error(err);
            });
        };
    </script>
    <script src="../js/category.js"></script>
</body>
</html>
