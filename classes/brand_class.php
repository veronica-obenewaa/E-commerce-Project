<?php

require_once __DIR__ . '/../settings/db_class.php';

class brand_class extends db_connection {
        // Edit brand name by brand_id
        public function editBrand($brand_id, $brand_name) {
            $conn = $this->db_conn();
            $stmt = $conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ?");
            $stmt->bind_param("si", $brand_name, $brand_id);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok
                ? ["status" => "success", "message" => "Brand updated successfully"]
                : ["status" => "error", "message" => "Failed to update brand"];
        }
    //add brand
    public function addBrand($brand_name) {
        $conn = $this->db_conn();
        $brand_name = trim($brand_name);
        //check if brand exists
        $check = $conn->prepare("SELECT brand_id FROM brands WHERE brand_name = ?");
        $check->bind_param("s", $brand_name);
        $check->execute();
        $check->store_result();
        if($check->num_rows > 0) {
            return ['status' => 'error', 'message' => 'Brand name already exists'];
        }
        $check->close();
        $stmt = $conn->prepare("INSERT INTO brands (brand_name) VALUES (?)");
        $stmt->bind_param("s", $brand_name);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok
            ? ['status' => 'success', 'message' => 'Brand added successfully']
            : ['status' => 'error', 'message' => 'Failed to add brand'];
    }


    //retrieve all brands
    public function fetchBrand() {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT * FROM brands ORDER BY brand_name ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $brands = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $brands;
    }

    // get all brands (no owner filter)
    public function fetchAllBrands() {
        $conn = $this->db_conn();
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    //delete brand
    public function deleteBrand($brand_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("DELETE FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok
            ? ["status" => "success", "message" => "Brand deleted successfully"]
            : ["status" => "error", "message" => "Failed to delete brand"];
    }

    #get a brand by id
    public function getBrandById($brand_id) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT brand_id, brand_name FROM brands WHERE brand_id = ?");
        $stmt->bind_param("i", $brand_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }


}

