<?php
//connect to database class
require("../settings/db_class.php");

/**
*General class to handle all functions 
*/
/**
 *@author David Sampah
 *
 */

//  public function add_brand($a,$b)
// 	{
// 		$ndb = new db_connection();	
// 		$name =  mysqli_real_escape_string($ndb->db_conn(), $a);
// 		$desc =  mysqli_real_escape_string($ndb->db_conn(), $b);
// 		$sql="INSERT INTO `brands`(`brand_name`, `brand_description`) VALUES ('$name','$desc')";
// 		return $this->db_query($sql);
// 	}
class customer_class extends db_connection
{
	// Check if email already exists
    
    public function checkEmail($customer_email) {
        $conn = $this->db_conn(); 
        $sql = "SELECT customer_id FROM customer WHERE customer_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $customer_email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

	//--INSERT--// 
    public function addCustomer($customer_name, $customer_email, $customer_pass, $customer_country, $customer_city, $customer_contact, $user_role = 2) {
        $conn = $this->db_conn();
        $hashed_password = password_hash($customer_pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $customer_name, $customer_email, $hashed_password, $customer_country, $customer_city, $customer_contact, $user_role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

	

	//--SELECT CUSTOMER BY ID--//


	//--UPDATE--//
    public function editCustomer($customer_id, $customer_name, $customer_country, $customer_city, $customer_contact) {
        $conn = $this->db_conn();
        $sql = "UPDATE customer SET customer_name = ?, customer_country = ?, customer_city = ?, customer_contact = ? WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $customer_name, $customer_country, $customer_city, $customer_contact, $customer_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    //--DELETE--//
    public function deleteCustomer($customer_id) {
        $conn = $this->db_conn();
        $sql = "DELETE FROM customer WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }


   
    public function checkContact($customer_contact, $exclude_id = null) {
        $conn = $this->db_conn();
        if ($exclude_id) {
            $sql = "SELECT customer_id FROM customer WHERE customer_contact = ? AND customer_id != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $customer_contact, $exclude_id);
        } else {
            $sql = "SELECT customer_id FROM customer WHERE customer_contact = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $customer_contact);
        }
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    public function getCustomerByEmail($customer_email, $customer_pass) {
        
        $conn = $this->db_conn();
        $sql = "SELECT customer_id, customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role 
                FROM customer WHERE customer_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $customer_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $customer = $result->fetch_assoc();

            // Verify password against hashed password in DB
            if (password_verify($customer_pass, $customer['customer_pass'])) {
                unset($customer['customer_pass']);
                //$stmt->close();
                return [
                    "status" => "success",
                    "message" => "Login successful",
                    "data" => $customer
                ];
            } else {
                //$stmt->close();
                return [
                    "status" => "error",
                    "message" => "Invalid password"
                ];
            }
        }

        //$stmt->close();
        return [
            "status" => "error",
            "message" => "Email not found"
        ];
    }



    





	
}


?>

