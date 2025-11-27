<?php
require_once(__DIR__ . "/../settings/db_class.php");
require_once(__DIR__ . "/zoom_class.php");

class booking_class extends db_connection {

    // Get bookings for a physician with optional patient details
    public function getBookingsByPhysician($physician_id) {
        $conn = $this->db_conn();
        $sql = "SELECT pb.booking_id, pb.physician_id, pb.patient_id, pb.appointment_datetime, pb.reason_text, pb.status, pb.created_at, c.customer_name as patient_name, c.customer_contact as patient_contact
                FROM physician_bookings pb
                LEFT JOIN customer c ON c.customer_id = pb.patient_id
                WHERE pb.physician_id = ?
                ORDER BY pb.appointment_datetime DESC, pb.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $physician_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    // Optional: create booking (used elsewhere by patient booking flow)
    public function createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text, $health_conditions = null) {
        $conn = $this->db_conn();
        // Check if health_conditions column exists, if not use reason_text field
        $sql = "INSERT INTO physician_bookings (physician_id, patient_id, appointment_datetime, reason_text, health_conditions) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $physician_id, $patient_id, $appointment_datetime, $reason_text, $health_conditions);
        $res = $stmt->execute();
        if (!$res) {
            // If health_conditions column doesn't exist, try without it
            $sql = "INSERT INTO physician_bookings (physician_id, patient_id, appointment_datetime, reason_text) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $combined_text = $reason_text . "\n\nHealth Conditions: " . ($health_conditions ?? 'N/A');
            $stmt->bind_param("iiss", $physician_id, $patient_id, $appointment_datetime, $combined_text);
            $res = $stmt->execute();
        }
        $insertId = $conn->insert_id;
        $stmt->close();
        return $res ? $insertId : false;
    }

    // Get bookings for a patient (customer)
    public function getBookingsByPatient($patient_id) {
        $conn = $this->db_conn();
        $sql = "SELECT pb.booking_id, pb.physician_id, pb.patient_id, pb.appointment_datetime, pb.reason_text, pb.health_conditions, pb.status, pb.created_at,
                c.customer_name as physician_name, c.hospital_name, c.customer_city, c.customer_country, c.customer_contact as physician_contact
                FROM physician_bookings pb
                LEFT JOIN customer c ON c.customer_id = pb.physician_id
                WHERE pb.patient_id = ?
                ORDER BY pb.appointment_datetime DESC, pb.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    // Update booking status (scheduled, completed, cancelled)
    public function updateBookingStatus($booking_id, $status) {
        $allowed = ['scheduled','completed','cancelled'];
        if (!in_array($status, $allowed)) return false;
        $conn = $this->db_conn();
        $sql = "UPDATE physician_bookings SET status = ? WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $booking_id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // Create and link Zoom meeting to booking
    public function createZoomMeeting($booking_id, $appointment_datetime, $physician_name, $patient_name) {
        $conn = $this->db_conn();
        
        // Get booking details
        $sql = "SELECT * FROM physician_bookings WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();

        if (!$booking) {
            return ['success' => false, 'error' => 'Booking not found'];
        }

        // Initialize Zoom API
        $zoomAPI = new ZoomAPI();
        
        // Format appointment datetime for Zoom
        $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $appointment_datetime);
        if (!$datetime) {
            return ['success' => false, 'error' => 'Invalid appointment datetime'];
        }

        $meeting_data = [
            'topic' => 'Consultation: ' . htmlspecialchars($physician_name) . ' - ' . htmlspecialchars($patient_name),
            'start_time' => $datetime->format('Y-m-d\TH:i:s'),
            'duration' => 60,
            'password' => $this->generateMeetingPassword()
        ];

        $zoom_result = $zoomAPI->createMeeting($meeting_data);

        if (!$zoom_result['success']) {
            // Include raw response and HTTP code for debugging
            $error_msg = $zoom_result['error'] ?? 'Failed to create Zoom meeting';
            if (isset($zoom_result['raw_response'])) {
                $error_msg .= ' | Raw: ' . substr($zoom_result['raw_response'], 0, 200);
            }
            if (isset($zoom_result['http_code'])) {
                $error_msg .= ' | HTTP: ' . $zoom_result['http_code'];
            }
            return [
                'success' => false,
                'error' => $error_msg,
                'debug' => $zoom_result
            ];
        }

        // Update booking with Zoom details
        $sql = "UPDATE physician_bookings 
                SET zoom_meeting_id = ?, 
                    zoom_join_url = ?, 
                    zoom_start_url = ?, 
                    zoom_password = ?,
                    zoom_created_at = NOW(),
                    zoom_status = 'created'
                WHERE booking_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $zoom_result['meeting_id'],
            $zoom_result['join_url'],
            $zoom_result['start_url'],
            $zoom_result['password'],
            $booking_id
        );
        
        $res = $stmt->execute();
        $stmt->close();

        if ($res) {
            return [
                'success' => true,
                'meeting_id' => $zoom_result['meeting_id'],
                'join_url' => $zoom_result['join_url'],
                'start_url' => $zoom_result['start_url'],
                'password' => $zoom_result['password']
            ];
        } else {
            return ['success' => false, 'error' => 'Failed to save Zoom meeting details'];
        }
    }

    // Update booking with Zoom meeting info (when created afterwards)
    public function updateBookingWithZoom($booking_id, $zoom_meeting_id, $zoom_join_url, $zoom_start_url, $zoom_password) {
        $conn = $this->db_conn();
        $sql = "UPDATE physician_bookings 
                SET zoom_meeting_id = ?, 
                    zoom_join_url = ?, 
                    zoom_start_url = ?, 
                    zoom_password = ?,
                    zoom_created_at = NOW(),
                    zoom_status = 'created'
                WHERE booking_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $zoom_meeting_id, $zoom_join_url, $zoom_start_url, $zoom_password, $booking_id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // Cancel Zoom meeting
    public function cancelZoomMeeting($booking_id) {
        $conn = $this->db_conn();
        
        // Get meeting ID
        $sql = "SELECT zoom_meeting_id FROM physician_bookings WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if (!$row || !$row['zoom_meeting_id']) {
            return false;
        }

        // Delete from Zoom
        $zoomAPI = new ZoomAPI();
        if (!$zoomAPI->deleteMeeting($row['zoom_meeting_id'])) {
            return false;
        }

        // Update booking status
        $sql = "UPDATE physician_bookings SET zoom_status = 'cancelled' WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // Get booking with Zoom details
    public function getBookingWithZoom($booking_id) {
        $conn = $this->db_conn();
        $sql = "SELECT * FROM physician_bookings WHERE booking_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }

    // Generate a secure random password for Zoom meeting
    private function generateMeetingPassword() {
        return substr(base64_encode(random_bytes(12)), 0, 12);
    }

}

?>