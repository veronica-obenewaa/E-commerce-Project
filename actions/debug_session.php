<?php
require_once __DIR__ . '/../settings/core.php';

header('Content-Type: application/json');

$sessionData = [
    'is_logged_in' => isLoggedIn(),
    'customer_id' => getUserId(),
    'user_role' => $_SESSION['user_role'] ?? null,
    'role_id' => $_SESSION['role_id'] ?? null,
    'get_user_role' => getUserRole(),
    'is_physician' => isPhysician(),
    'all_session_keys' => array_keys($_SESSION)
];

echo json_encode($sessionData, JSON_PRETTY_PRINT);
exit;
?>