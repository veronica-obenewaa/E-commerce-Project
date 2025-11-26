<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/notification_class.php';

header('Content-Type: application/json');

// Only allow logged-in customers
if (!isLoggedIn() || (!isCustomer() && getUserRole() != 2)) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized', 'data' => [], 'count' => 0]);
    exit;
}

$customer_id = getUserId();
$notificationClass = new notification_class();

try {
    // Get unread notifications
    $unreadNotifications = $notificationClass->getUnreadNotifications($customer_id);
    $unreadCount = count($unreadNotifications);

    echo json_encode([
        'status' => 'success',
        'data' => $unreadNotifications,
        'count' => $unreadCount
    ]);
} catch (Exception $e) {
    // If table doesn't exist yet, return empty
    echo json_encode([
        'status' => 'success',
        'data' => [],
        'count' => 0,
        'message' => 'No notifications or table not yet created'
    ]);
}
exit;
?>
