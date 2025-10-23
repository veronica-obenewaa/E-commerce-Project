<?php

require_once __DIR__ . '/../settings/db_class.php';

class brand_class extends db_connection {
    //add category
    public function addBrand($brand_name, $created_by) {
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

        $stmt = $conn->prepare("INSERT INTO brands (brand_name, created_by) VALUES (?, ?)");
        $stmt->bind_param("si", $brand_name, $created_by);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok
            ?['status' => 'success', 'message' => 'Brand added successfully']:['status' => 'error', 'message' => 'Failed to add brand'];
    }


    //retrieve brands created by a user
    public function fetchBrand($created_by) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT * FROM brands WHERE created_by = ? ORDER BY brand_name ASC");
        $stmt->bind_param("i", $created_by);
        $stmt->execute();
        $result = $stmt->get_result();
        $brands = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return ['status' => 'success', 'data' => $brands];

    } 

    #update brands name
    public function editBrand($brand_id, $brand_name, $created_by) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("UPDATE brands SET brand_name = ? WHERE brand_id = ? AND created_by = ?");
        $stmt->bind_param("sii", $brand_name, $brand_id, $created_by);
        $ok = $stmt->execute();
        
        $stmt->close();


        
        return $ok
            ?["status" => "success", "message" => "Brand updated successfully"]:["status" => "error", "message" => "Failed to update brand"];
       
    }

    //delete brand only if user created it
    public function deleteBrand($brand_id, $created_by) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("DELETE FROM brands WHERE brand_id = ? AND created_by = ?");
        $stmt->bind_param("ii", $brand_id, $created_by);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok
            ?["status" => "success", "message" => "Brand deleted successfully"]:["status" => "error", "message" => "Failed to delete brand"];
       
    }

    #get a brand by id and owner
    public function getBrandById($brand_id, $created_by) {
        $conn = $this->db_conn();
        $stmt = $conn->prepare("SELECT brand_id, brand_name, created_by FROM brands WHERE brand_id = ? AND created_by = ?");
        $stmt->bind_param("ii", $brand_id, $created_by);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }


}

