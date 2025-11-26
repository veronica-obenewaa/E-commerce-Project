<?php
require_once('settings/zoom_config.php');

echo "<h2>Zoom Credentials Validation</h2>";

echo "<h3>Current Credentials:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><td><strong>Client ID:</strong></td><td>" . ZOOM_CLIENT_ID . "</td></tr>";
echo "<tr><td><strong>Client Secret:</strong></td><td>" . ZOOM_CLIENT_SECRET . "</td></tr>";
echo "<tr><td><strong>Account ID:</strong></td><td>" . ZOOM_ACCOUNT_ID . "</td></tr>";
echo "</table>";

echo "<h3>Credentials Check:</h3>";
echo "<ul>";
echo "<li>Client ID is set: " . (ZOOM_CLIENT_ID ? "<span style='color:green'>✓</span>" : "<span style='color:red'>✗</span>") . "</li>";
echo "<li>Client Secret is set: " . (ZOOM_CLIENT_SECRET ? "<span style='color:green'>✓</span>" : "<span style='color:red'>✗</span>") . "</li>";
echo "<li>Account ID is set: " . (ZOOM_ACCOUNT_ID ? "<span style='color:green'>✓</span>" : "<span style='color:red'>✗</span>") . "</li>";
echo "<li>Client ID length: " . strlen(ZOOM_CLIENT_ID) . " characters</li>";
echo "<li>Client Secret length: " . strlen(ZOOM_CLIENT_SECRET) . " characters</li>";
echo "<li>Account ID length: " . strlen(ZOOM_ACCOUNT_ID) . " characters</li>";
echo "</ul>";

echo "<h3>Base64 Encoding Test:</h3>";
$auth = base64_encode(ZOOM_CLIENT_ID . ':' . ZOOM_CLIENT_SECRET);
echo "<p>Basic Auth Header: " . $auth . "</p>";

echo "<h3>IMPORTANT - Please verify:</h3>";
echo "<ol>";
echo "<li>Are these credentials for a <strong>Server-to-Server OAuth</strong> app? (Not a Web/Desktop App)</li>";
echo "<li>Have you activated the app in the Zoom Marketplace?</li>";
echo "<li>Did you copy all credentials exactly with no extra spaces?</li>";
echo "</ol>";

echo "<h3>Next Steps:</h3>";
echo "<ol>";
echo "<li>If credentials look wrong, update them in <code>settings/zoom_config.php</code></li>";
echo "<li>Make sure to copy the exact values from Zoom Marketplace</li>";
echo "<li>Run this test again after updating</li>";
echo "</ol>";

echo "<p><strong>If credentials are correct, the issue might be:</strong></p>";
echo "<ul>";
echo "<li>Zoom app not activated</li>";
echo "<li>App needs to be authorized for your account</li>";
echo "<li>Zoom server issue (try again in a few minutes)</li>";
echo "</ul>";
?>
