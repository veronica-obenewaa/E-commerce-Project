<?php

require_once __DIR__ . '/../settings/db_class.php';

class category_class extends db_connection {
    //add category
    public function addCategory($cat_name, $created_by) {
        $conn = $this->db_conn();
        $sql = "INSERT INTO categories (cat_name, created_by) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $cat_name, $created_by);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    //check unique name excluding id
    public function categoryNameExists($cat_name, $exclude_id=null) {
        $conn = $this->db_conn();
        if($exclude_id) {
            $sql = "SELECT cat_id FROM categories WHERE cat_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $cat_name, $exclude_id);
        } else {
            $sql = "SELECT cat_id FROM categories WHERE cat_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $cat_name);

        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    //get category created by a specific user
    public function getCategoryByUser($customer_id) {
        $conn = $this->db_conn();
        $sql = "SELECT cat_id, cat_name, created_at FROM categories WHERE created_by = ? ORDER BY cat_name ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;

    } 

    #edit catergory name
    public function editCategory($cat_id, $cat_name, $customer_id) {
        $conn = $this->db_conn();
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ? AND created_by = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $cat_name, $cat_id, $customer_id);
        $res = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        if ($res && $affected > 0) {
            return ["status" => "success", "message" => "Category updated successfully"];
        }
        return ["status" => "error", "message" => "No changes made or update failed"];
        //return $res && ($stmt->affected_rows >= 0);
    }

    //delete category only if user created it
    public function deleteCategory($cat_id, $customer_id) {
        $conn = $this->db_conn();
        $sql = "DELETE FROM categories WHERE cat_id = ? AND created_by = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cat_id, $customer_id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    #get a category by id and owner
    public function getCategoryById($cat_id, $customer_id) {
        $conn = $this->db_conn();
        $sql = "SELECT cat_id, cat_name, created_at FROM categories WHERE cat_id = ? AND created_by = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cat_id, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }


}

