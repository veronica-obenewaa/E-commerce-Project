<?php
// classes/product_class.php
require_once __DIR__ . '/../settings/db_class.php';

class product_class extends db_connection
{
    // Add product -> returns inserted id on success, false on fail
    public function addProduct($product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_image, $product_keywords, $created_by) {
        $conn = $this->db_conn();
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("iisdsssi",
            $product_cat,
            $product_brand,
            $product_title,
            $product_price,
            $product_desc,
            $product_image,
            $product_keywords,
            $created_by
        );
        $ok = $stmt->execute();
        if (!$ok) { $stmt->close(); return false; }
        $insert_id = $conn->insert_id;
        $stmt->close();
        return $insert_id;
    }

    // Update product (image is optional)
    public function updateProduct($product_id, $product_cat, $product_brand, $product_title, $product_price, $product_desc, $product_keywords, $created_by, $image_path = null) {
        $conn = $this->db_conn();
        if ($image_path !== null && $image_path !== '') {
            $sql = "UPDATE products SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_keywords=?, product_image=?, updated_at=NOW()
                    WHERE product_id=? AND created_by=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("iisdsssii",
                $product_cat,
                $product_brand,
                $product_title,
                $product_price,
                $product_desc,
                $product_keywords,
                $image_path,
                $product_id,
                $created_by
            );
        } else {
            $sql = "UPDATE products SET product_cat=?, product_brand=?, product_title=?, product_price=?, product_desc=?, product_keywords=?, updated_at=NOW()
                    WHERE product_id=? AND created_by=?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) return false;
            $stmt->bind_param("iisdssii",
                $product_cat,
                $product_brand,
                $product_title,
                $product_price,
                $product_desc,
                $product_keywords,
                $product_id,
                $created_by
            );
        }
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Fetch products created by an admin
    public function getProductsByUser($created_by) {
        $conn = $this->db_conn();
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.created_by = ?
                ORDER BY p.product_id DESC";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param("i", $created_by);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // Get single product by id (and owner)
    public function getProductById($product_id, $created_by) {
        $conn = $this->db_conn();
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ? AND p.created_by = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("ii", $product_id, $created_by);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    // Set product image path
    public function setProductImage($product_id, $image_path) {
        $conn = $this->db_conn();
        $sql = "UPDATE products SET product_image = ?, updated_at = NOW() WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("si", $image_path, $product_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Delete product
    public function deleteProduct($product_id, $created_by) {
        $conn = $this->db_conn();
        $sql = "DELETE FROM products WHERE product_id = ? AND created_by = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("ii", $product_id, $created_by);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    //fetch all products visible to customers(all admin products)
    public function view_all_products() {
        $conn = $this->db_conn();
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        $stmt = $conn->prepare($sql);
        if(!$stmt) return [];
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;

    }

    //fetch single product details for customers
    public function view_single_product($product_id) {
        $conn = $this->db_conn();
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        if(!$stmt) return null;
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;

    }

    //filter by category
    public function filter_products_by_category($cat_id) {
        $conn = $this->db_conn();
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_cat = ?
                ORDER BY p.product_id DESC";
        $stmt = $conn->prepare($sql);
        if(!$stmt) return [];
        $stmt->bind_param("i", $cat_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    //filter by brand
    public function filter_products_by_brand($brand_id) {
        $conn = $this->db_conn();
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_brand = ?
                ORDER BY p.product_id DESC";
        $stmt = $conn->prepare($sql);
        if(!$stmt) return [];
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    // search_products with category, brand, and pagination
    public function search_products($query = '', $cat_id = 0, $brand_id = 0, $page = 1, $pageSize = 10) {
        $conn = $this->db_conn();
        $offset = ($page - 1) * $pageSize;

        $where = "WHERE 1";
        $params = [];
        $types = "";

        // Search by title or keyword
        if (!empty($query)) {
            $where .= " AND (p.product_title LIKE ? OR p.product_keywords LIKE ?)";
            $q = "%" . $query . "%";
            $params[] = $q;
            $params[] = $q;
            $types .= "ss";
        }

        // Filter by category
        if ($cat_id > 0) {
            $where .= " AND p.product_cat = ?";
            $params[] = $cat_id;
            $types .= "i";
        }

        // Filter by brand
        if ($brand_id > 0) {
            $where .= " AND p.product_brand = ?";
            $params[] = $brand_id;
            $types .= "i";
        }

        // Build main query
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                $where
                ORDER BY p.product_id DESC
                LIMIT ?, ?";
        $params[] = $offset;
        $params[] = $pageSize;
        $types .= "ii";

        $stmt = $conn->prepare($sql);
        if (!$stmt) return ['status' => 'error', 'message' => 'DB prepare failed'];
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Total count (for pagination)
        $count_sql = "SELECT COUNT(*) AS total FROM products p $where";
        $stmt2 = $conn->prepare($count_sql);
        $count_types = substr($types, 0, -2); 
        $count_params = array_slice($params, 0, -2);
        if (!empty($count_params)) {
            $stmt2->bind_param($count_types, ...$count_params);
        }
        $stmt2->execute();
        $total = $stmt2->get_result()->fetch_assoc()['total'] ?? 0;
        $stmt2->close();

        return ['status' => 'success', 'data' => $rows, 'total' => $total];
    }


    public function fetch_products_filtered($query = '', $cat_id = 0, $brand_id = 0) {
        $conn = $this->db_conn();
        $query = "%" . $query . "%";

        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE (p.product_title LIKE ? OR p.product_keywords LIKE ?)";
        
        if ($cat_id > 0) $sql .= " AND p.product_cat = " . intval($cat_id);
        if ($brand_id > 0) $sql .= " AND p.product_brand = " . intval($brand_id);
        
        $sql .= " ORDER BY p.product_id DESC";

        $stmt = $conn->prepare($sql);
        if (!$stmt) return ['status' => 'error', 'message' => 'DB prepare failed'];
        $stmt->bind_param("ss", $query, $query);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return ['status' => 'success', 'data' => $rows];
    }


}
