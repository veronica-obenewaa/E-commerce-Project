<?php
//start session
session_start(); 

//for header redirection
ob_start();

//funtion to check for login
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header("Location: ../Login/login.php");
//     exit;
// }

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

// Check if user is admin
function isAdmin() {
    return getUserRole() == 1;
}


// Check if user is customer
function isCustomer() {
    return getUserRole() == 2;
}




?>