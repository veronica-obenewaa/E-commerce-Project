<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/notification_class.php';

header('Content-Type: application/json');

// Only allow logged-in customers
if (!isLoggedIn() || (!isCustomer() && getUserRole() != 2)) {
    echo json_encode(['status' => 'error', 'unread_count' => 0]);
    exit;
}

$customer_id = getUserId();
$notificationClass = new notification_class();
$unread_count = $notificationClass->getUnreadCount($customer_id);

echo json_encode([
    'status' => 'success',
    'unread_count' => $unread_count
]);
exit;
?>
