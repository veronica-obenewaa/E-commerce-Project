<?php
//connect to the user account class
include("../classes/customer_class.php");

//sanitize data

// function add_user_ctr($a,$b,$c,$d,$e,$f,$g){
// 	$adduser=new customer_class();
// 	return $adduser->add_user($a,$b,$c,$d,$e,$f,$g);
// }
class CustomerController {
    private $customerModel;
    
    public function __construct() {
        $this->customerModel = new customer_class();
    }

    public function checkEmail($customer_email) {
        return $this->customerModel->checkEmail($customer_email);
    }
    

    public function register_customer_ctr($kwargs) {
        $errors = [];

        if (empty($kwargs['customer_name'])) $errors[] = "Full name is required";
        elseif (strlen($kwargs['customer_name']) > 100) $errors[] = "Full name must be less than 100 characters";

        if (empty($kwargs['customer_email']) || !filter_var($kwargs['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email is required";
        } elseif (strlen($kwargs['customer_email']) > 100) {
            $errors[] = "Email must be less than 100 characters";
        } elseif ($this->customerModel->checkEmail($kwargs['customer_email'])) {
            $errors[] = "Email already exists";
        }

        if (empty($kwargs['customer_pass']) || strlen($kwargs['customer_pass']) < 8) {
            $errors[] = "Password must be at least 8 characters";
        }

        if (empty($kwargs['customer_country'])) $errors[] = "Country is required";
        elseif (strlen($kwargs['customer_country']) > 50) $errors[] = "Country must be less than 50 characters";

        if (empty($kwargs['customer_city'])) $errors[] = "City is required";
        elseif (strlen($kwargs['customer_city']) > 50) $errors[] = "City must be less than 50 characters";

        if (empty($kwargs['customer_contact'])) $errors[] = "Contact number is required";
        elseif (strlen($kwargs['customer_contact']) > 20) $errors[] = "Contact number must be less than 20 characters";
        elseif ($this->customerModel->checkContact($kwargs['customer_contact'])) {
            $errors[] = "Contact number already exists";
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'message' => implode(', ', $errors)];
        }

        $result = $this->customerModel->addCustomer(
            $kwargs['customer_name'],
            $kwargs['customer_email'],
            $kwargs['customer_pass'],
            $kwargs['customer_country'],
            $kwargs['customer_city'],
            $kwargs['customer_contact'],
            $kwargs['user_role'] ?? 2
        );

        return $result 
            ? ['status' => 'success', 'message' => 'Registration successful'] 
            : ['status' => 'error', 'message' => 'Registration failed. Please try again.'];
    }

 
    public function login_customer_ctr($kwargs) {
        if (empty($kwargs['customer_email']) || empty($kwargs['customer_pass'])) {
            return ['status' => 'error', 'message' => 'Email and password are required'];
        }
        return $this->customerModel->getCustomerByEmail($kwargs['customer_email'], $kwargs['customer_pass']);
    }

  
    public function edit_customer_ctr($kwargs) {
        if (empty($kwargs['customer_id'])) return ['status' => 'error', 'message' => 'Customer ID is required'];

        $errors = [];
        if (empty($kwargs['customer_name'])) $errors[] = "Full name is required";
        elseif (strlen($kwargs['customer_name']) > 100) $errors[] = "Full name must be less than 100 characters";

        if (empty($kwargs['customer_country'])) $errors[] = "Country is required";
        elseif (strlen($kwargs['customer_country']) > 50) $errors[] = "Country must be less than 50 characters";

        if (empty($kwargs['customer_city'])) $errors[] = "City is required";
        elseif (strlen($kwargs['customer_city']) > 50) $errors[] = "City must be less than 50 characters";

        if (empty($kwargs['customer_contact'])) $errors[] = "Contact number is required";
        elseif (strlen($kwargs['customer_contact']) > 20) $errors[] = "Contact number must be less than 20 characters";
        elseif ($this->customerModel->checkContact($kwargs['customer_contact'], $kwargs['customer_id'])) {
            $errors[] = "Contact number already exists";
        }

        if (!empty($errors)) return ['status' => 'error', 'message' => implode(', ', $errors)];

        $result = $this->customerModel->editCustomer(
            $kwargs['customer_id'], $kwargs['customer_name'], $kwargs['customer_country'], $kwargs['customer_city'], $kwargs['customer_contact']
        );

        return $result
            ? ['status' => 'success', 'message' => 'Customer updated successfully']
            : ['status' => 'error', 'message' => 'Update failed. Please try again.'];
    }


    public function delete_customer_ctr($customer_id) {
        if (empty($id)) return ['status' => 'error', 'message' => 'Customer ID is required'];

        $result = $this->customerModel->deleteCustomer($customer_id);
        return $result
            ? ['status' => 'success', 'message' => 'Customer deleted successfully']
            : ['status' => 'error', 'message' => 'Deletion failed. Please try again.'];
    }



}
?>
    
   