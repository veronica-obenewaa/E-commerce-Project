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

        // Additional fields depending on role
        $role = isset($kwargs['user_role']) ? (int)$kwargs['user_role'] : 2;
        if ($role === 1) { // Pharmaceutical company
            if (empty($kwargs['company_name'])) $errors[] = "Company name is required for pharmaceutical registrations";
            if (empty($kwargs['pharmaceutical_registration_number'])) $errors[] = "Pharmaceutical registration number is required";
            // uniqueness check
            if (!empty($kwargs['pharmaceutical_registration_number']) && $this->customerModel->checkPharmaRegistration($kwargs['pharmaceutical_registration_number'])) {
                $errors[] = "Pharmaceutical registration number already exists";
            }
        }

        if ($role === 3) { // Physician
            if (empty($kwargs['hospital_name'])) $errors[] = "Hospital name is required for physician registrations";
            if (empty($kwargs['hospital_registration_number'])) $errors[] = "Hospital registration number is required";
            // uniqueness check
            if (!empty($kwargs['hospital_registration_number']) && $this->customerModel->checkHospitalRegistration($kwargs['hospital_registration_number'])) {
                $errors[] = "Hospital registration number already exists";
            }
        }

        if (!empty($errors)) {
            return ['status' => 'error', 'message' => implode(', ', $errors)];
        }
        // Create customer and return inserted id
        $customer_id = $this->customerModel->addCustomer(
            $kwargs['customer_name'],
            $kwargs['customer_email'],
            $kwargs['customer_pass'],
            $kwargs['customer_country'],
            $kwargs['customer_city'],
            $kwargs['customer_contact'],
            $role,
            $kwargs['role_id'] ?? null,
            $kwargs['company_name'] ?? null,
            $kwargs['pharmaceutical_registration_number'] ?? null,
            $kwargs['hospital_name'] ?? null,
            $kwargs['hospital_registration_number'] ?? null
        );

        if (!$customer_id) {
            return ['status' => 'error', 'message' => 'Failed to create customer'];
        }

        // If physician and specializations provided, attach them
        if ($role === 3 && !empty($kwargs['medical_specializations'])) {
            $specs = $kwargs['medical_specializations'];
            if (!is_array($specs)) {
                // accept comma-separated string
                $specs = array_map('trim', explode(',', $specs));
            }
            foreach ($specs as $specName) {
                if (empty($specName)) continue;
                $specId = $this->customerModel->addSpecialization($specName);
                if ($specId) $this->customerModel->addCustomerSpecialization($customer_id, $specId);
            }
        }

        return ['status' => 'success', 'message' => 'Registration successful', 'customer_id' => $customer_id];
    }

 
    public function login_customer_ctr($kwargs) {
        if (empty($kwargs['customer_email']) || empty($kwargs['customer_pass'])) {
            return ['status' => 'error', 'message' => 'Email and password are required'];
        }
        return $this->customerModel->getCustomerByEmail($kwargs['customer_email'], $kwargs['customer_pass']);
    }

    // AJAX helpers for checking registration numbers from front-end
    public function checkPharma($reg) {
        return $this->customerModel->checkPharmaRegistration($reg);
    }

    public function checkHospital($reg) {
        return $this->customerModel->checkHospitalRegistration($reg);
    }

    // Fetch company profile by customer_id (for pharmaceutical companies)
    public function get_company_profile($customer_id) {
        if (empty($customer_id)) {
            return ['status' => 'error', 'message' => 'Invalid customer ID'];
        }
        $customer = $this->customerModel->getCustomerById($customer_id);
        if (!$customer) {
            return ['status' => 'error', 'message' => 'Customer not found'];
        }
        return ['status' => 'success', 'data' => $customer];
    }

    // Update company profile (name, contact, location)
    public function update_company_profile_ctr($data) {
        $customer_id = isset($data['customer_id']) ? intval($data['customer_id']) : 0;
        if ($customer_id <= 0) {
            return ['status' => 'error', 'message' => 'Invalid customer ID'];
        }

        $errors = [];
        if (empty($data['customer_name'])) $errors[] = "Contact person name is required";
        if (empty($data['company_name'])) $errors[] = "Company name is required";
        if (empty($data['customer_country'])) $errors[] = "Country is required";
        if (empty($data['customer_city'])) $errors[] = "City is required";
        if (empty($data['customer_contact'])) $errors[] = "Contact number is required";

        if (!empty($errors)) {
            return ['status' => 'error', 'message' => implode(', ', $errors)];
        }

        $result = $this->customerModel->editCustomer(
            $customer_id,
            $data['customer_name'],
            $data['customer_country'],
            $data['customer_city'],
            $data['customer_contact']
        );

        return $result
            ? ['status' => 'success', 'message' => 'Profile updated successfully']
            : ['status' => 'error', 'message' => 'Failed to update profile'];
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
    
   