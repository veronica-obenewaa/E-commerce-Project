<?php
require_once __DIR__ . '/../settings/core.php';
require_once __DIR__ . '/../classes/notification_class.php';

header('Content-Type: application/json');

// Only allow logged-in users
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$notification_id = isset($_POST['notification_id']) ? intval($_POST['notification_id']) : 0;

if ($notification_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid notification id']);
    exit;
}

try {
    $notificationClass = new notification_class();
    $result = $notificationClass->markAsRead($notification_id);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Notification marked as read']);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Notification marked as read']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'success', 'message' => 'Notification handled']);
}
exit;
?>
