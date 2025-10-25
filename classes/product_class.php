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
        $sql = "SELECT * FROM products WHERE product_id = ? AND created_by = ? LIMIT 1";
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
}
