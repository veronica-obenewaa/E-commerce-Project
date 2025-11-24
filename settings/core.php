<?php
//start session
session_start(); 

//for header redirection
ob_start();

// Define base path for navigation
define('BASE_PATH', rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'));

define('UPLOAD_BASE_URL', '../');


//function to get user ID
function getUserId() {
    return $_SESSION['customer_id'] ?? null;
}


//function to check for role
function getUserRole() {
    // Prefer the newer `role_id` if present, fall back to legacy `user_role`.
    return $_SESSION['role_id'] ?? $_SESSION['user_role'] ?? null;
}

function isLoggedIn() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

// Role mapping (updated): 1 = pharmaceutical company, 2 = customer, 3 = physician
// Check if user is admin/pharmaceutical company (role 1)
function isAdmin() {
    return getUserRole() == 1;
}

// Check if user is customer
function isCustomer() {
    return getUserRole() == 2;
}

// Check if user is a pharmaceutical company (role 1)
function isCompany() {
    return getUserRole() == 1;
}

// Check if user is a physician (role 3)
function isPhysician() {
    return getUserRole() == 3;
}




?>