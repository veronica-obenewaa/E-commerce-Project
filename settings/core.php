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


//function to get user ID
function getUserId() {
    return $_SESSION['customer_id'] ?? null;
}


//function to check for role
function getUserRole() {
    return $_SESSION['user_role'] ?? null;
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