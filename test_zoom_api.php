<?php
require_once('settings/zoom_config.php');
require_once('classes/zoom_class.php');

echo "<h2>Zoom API Test</h2>";

// Test credentials
echo "<h3>Credentials:</h3>";
echo "<p>Client ID: " . (defined('ZOOM_CLIENT_ID') && ZOOM_CLIENT_ID ? substr(ZOOM_CLIENT_ID, 0, 10) . "..." : "NOT SET") . "</p>";
echo "<p>Client Secret: " . (defined('ZOOM_CLIENT_SECRET') && ZOOM_CLIENT_SECRET ? "SET" : "NOT SET") . "</p>";
echo "<p>Account ID: " . (defined('ZOOM_ACCOUNT_ID') && ZOOM_ACCOUNT_ID ? substr(ZOOM_ACCOUNT_ID, 0, 10) . "..." : "NOT SET") . "</p>";

// Test API connection
echo "<h3>API Connection Test:</h3>";
$zoomAPI = new ZoomAPI();

// Try to create a test meeting
$test_data = [
    'topic' => 'Test Meeting - ' . date('Y-m-d H:i:s'),
    'start_time' => date('Y-m-d\TH:i:s', strtotime('+1 hour')),
    'duration' => 30,
    'password' => 'test1234'
];

$result = $zoomAPI->createMeeting($test_data);

if ($result['success']) {
    echo "<p style='color: green;'><strong>✓ SUCCESS!</strong> Meeting created!</p>";
    echo "<p>Meeting ID: " . $result['meeting_id'] . "</p>";
    echo "<p>Join URL: " . $result['join_url'] . "</p>";
} else {
    echo "<p style='color: red;'><strong>✗ FAILED</strong></p>";
    echo "<p>Error: " . $result['error'] . "</p>";
    echo "<p>Check PHP error logs for more details.</p>";
}

echo "<hr>";
echo "<p><small>Check your browser console and PHP error logs for detailed diagnostic information.</small></p>";
?>
