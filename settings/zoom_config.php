<?php
/**
 * Zoom API Configuration
 * Store your Zoom credentials securely
 */

// Zoom API Credentials
define('ZOOM_CLIENT_ID', getenv('ZOOM_CLIENT_ID') ?: 'AjNXZCo3SwWLMiwrdyJHA');
define('ZOOM_CLIENT_SECRET', getenv('ZOOM_CLIENT_SECRET') ?: 'c1RT51ZP6W38daJRW6oGCKHbSgaKWVC4');
define('ZOOM_ACCOUNT_ID', getenv('ZOOM_ACCOUNT_ID') ?: 'ETka8FtcS8KsScZN3EOHJw');

// Zoom API Endpoints
define('ZOOM_API_BASE_URL', 'https://zoom.us/oauth/token');
define('ZOOM_API_CREATE_MEETING', 'https://api.zoom.us/v2/users/me/meetings');
define('ZOOM_API_UPDATE_MEETING', 'https://api.zoom.us/v2/meetings');
define('ZOOM_API_DELETE_MEETING', 'https://api.zoom.us/v2/meetings');

// Default meeting settings
define('ZOOM_MEETING_DURATION', 60); // in minutes
define('ZOOM_MEETING_TYPE', 2); // 1 = instant, 2 = scheduled

// Note: For production, use environment variables or a secure config file
// Example .env setup:
// ZOOM_CLIENT_ID=your_client_id
// ZOOM_CLIENT_SECRET=your_client_secret
// ZOOM_ACCOUNT_ID=your_account_id
?>
