<?php
require_once(__DIR__ . "/../settings/zoom_config.php");
require_once(__DIR__ . "/../settings/db_class.php");

class ZoomAPI {
    private $client_id;
    private $client_secret;
    private $account_id;
    private $access_token;
    private $token_expiry;

    public function __construct() {
        $this->client_id = ZOOM_CLIENT_ID;
        $this->client_secret = ZOOM_CLIENT_SECRET;
        $this->account_id = ZOOM_ACCOUNT_ID;
    }

    /**
     * Get or refresh access token from Zoom using OAuth
     */
    private function getAccessToken() {
        if ($this->access_token && $this->token_expiry && time() < $this->token_expiry) {
            return $this->access_token;
        }

        $auth_string = base64_encode($this->client_id . ':' . $this->client_secret);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => ZOOM_API_BASE_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . $auth_string,
                'Content-Type: application/x-www-form-urlencoded'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'grant_type=account_credentials&account_id=' . urlencode($this->account_id),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        error_log("ZOOM TOKEN RAW RESPONSE: " . $response);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            error_log("Zoom API cURL Error: " . $curl_error);
            return false;
        }

        if ($http_code !== 200) {
            error_log("Zoom API Error - HTTP $http_code: " . $response);
            return false;
        }

        $data = json_decode($response, true);
        if (!isset($data['access_token'])) {
            error_log("Zoom API Error: No access token in response. " . $response);
            return false;
        }

        $this->access_token = $data['access_token'];
        $this->token_expiry = time() + ($data['expires_in'] - 300);

        return $this->access_token ;
    }

        // // Check if token is still valid
        // if ($this->access_token && $this->token_expiry && time() < $this->token_expiry) {
        //     return $this->access_token;
        // }

        // // For Server-to-Server OAuth, send credentials in Authorization header (Base64)
        // $auth_string = base64_encode($this->client_id . ':' . $this->client_secret);
        
        // $ch = curl_init();
        // curl_setopt_array($ch, [
        //     CURLOPT_URL => ZOOM_API_BASE_URL,
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_HTTPHEADER => [
        //         'Authorization: Basic ' . $auth_string,
        //         'Content-Type: application/x-www-form-urlencoded'
        //     ],
        //     CURLOPT_POST => true,
        //     // CURLOPT_POSTFIELDS => http_build_query([
        //     //     'grant_type' => 'account_credentials',
        //     //     'account_id' => $this->account_id
        //     // ]),
        //     CURLOPT_POSTFIELDS => '',
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_SSL_VERIFYPEER => true
        // ]);

        // $response = curl_exec($ch);
        // $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // $curl_error = curl_error($ch);
        // curl_close($ch);

        // if ($curl_error) {
        //     error_log("Zoom API cURL Error: " . $curl_error);
        //     return false;
        // }

        // if ($http_code !== 200) {
        //     error_log("Zoom API Error - HTTP $http_code: " . $response);
        //     return false;
        // }

        // $data = json_decode($response, true);
        // if (!isset($data['access_token'])) {
        //     error_log("Zoom API Error: No access token in response. " . $response);
        //     return false;
        // }

        // $this->access_token = $data['access_token'];
        // $this->token_expiry = time() + ($data['expires_in'] - 300); // Refresh 5 min before expiry

        // return $this->access_token;


        
    

    /**
     * Create a Zoom meeting
     * @param array $meeting_data Meeting configuration
     * @return array|false Meeting details on success, false on failure
     */
    public function createMeeting($meeting_data) {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['success' => false, 'error' => 'Failed to authenticate with Zoom'];
        }

        // Prepare meeting payload
        $payload = [
            'topic' => $meeting_data['topic'] ?? 'Medical Consultation',
            'type' => ZOOM_MEETING_TYPE,
            'start_time' => $meeting_data['start_time'] ?? date('Y-m-d\TH:i:s'),
            'duration' => $meeting_data['duration'] ?? ZOOM_MEETING_DURATION,
            'timezone' => 'UTC',
            'settings' => [
                'host_video' => true,
                'participant_video' => true,
                'cn_meeting' => false,
                'in_meeting' => true,
                'join_before_host' => false,
                'mute_upon_entry' => false,
                'watermark' => false,
                'use_pmi' => false,
                'approval_type' => 0,
                'audio' => 'both'
            ]
        ];

        // Add optional fields
        if (!empty($meeting_data['password'])) {
            $payload['password'] = $meeting_data['password'];
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => ZOOM_API_CREATE_MEETING,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            error_log("Zoom Meeting Create cURL Error: " . $curl_error);
            return ['success' => false, 'error' => 'Network error: ' . $curl_error];
        }

        if ($http_code !== 201) {
            $error_data = json_decode($response, true);
            error_log("Zoom Meeting Create Error - HTTP $http_code: " . $response);
            return [
                'success' => false,
                'error' => $error_data['message'] ?? 'Failed to create Zoom meeting',
                'http_code' => $http_code
            ];
        }

        $meeting = json_decode($response, true);
        
        return [
            'success' => true,
            'meeting_id' => $meeting['id'] ?? null,
            'start_url' => $meeting['start_url'] ?? null,
            'join_url' => $meeting['join_url'] ?? null,
            'password' => $meeting['password'] ?? null,
            'start_time' => $meeting['start_time'] ?? null
        ];
    }

    /**
     * Update an existing Zoom meeting
     * @param string $meeting_id Zoom meeting ID
     * @param array $meeting_data Updated meeting data
     * @return bool Success status
     */
    public function updateMeeting($meeting_id, $meeting_data) {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => ZOOM_API_UPDATE_MEETING . '/' . $meeting_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($meeting_data)
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $http_code === 204;
    }

    /**
     * Delete a Zoom meeting
     * @param string $meeting_id Zoom meeting ID
     * @return bool Success status
     */
    public function deleteMeeting($meeting_id) {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => ZOOM_API_DELETE_MEETING . '/' . $meeting_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token
            ],
            CURLOPT_CUSTOMREQUEST => 'DELETE'
        ]);

        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $http_code === 204;
    }

    /**
     * Get meeting details
     * @param string $meeting_id Zoom meeting ID
     * @return array|false Meeting details
     */
    public function getMeeting($meeting_id) {
        $token = $this->getAccessToken();
        if (!$token) {
            return false;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => ZOOM_API_UPDATE_MEETING . '/' . $meeting_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token
            ]
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            return false;
        }

        return json_decode($response, true);
    }
}

?>
