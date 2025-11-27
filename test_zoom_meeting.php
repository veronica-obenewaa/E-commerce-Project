<?php
require_once __DIR__ . '/settings/zoom_config.php';
require_once __DIR__ . '/classes/zoom_class.php';

$zoomAPI = new ZoomAPI();

// Test meeting creation
$meeting_data = [
    'topic' => 'Test Meeting',
    'start_time' => date('Y-m-d\TH:i:s', strtotime('+1 day')),
    'duration' => 60,
    'password' => 'Test123'
];

$result = $zoomAPI->createMeeting($meeting_data);

echo "Result:\n";
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

// Also check the error logs
echo "\n\n--- Check your PHP error log for detailed debugging info ---\n";
?>
