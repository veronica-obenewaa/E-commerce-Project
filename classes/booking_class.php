<?php
require_once(__DIR__ . "/../settings/db_class.php");

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

}

?>