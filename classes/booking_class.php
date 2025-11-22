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
    public function createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text) {
        $conn = $this->db_conn();
        $sql = "INSERT INTO physician_bookings (physician_id, patient_id, appointment_datetime, reason_text) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $physician_id, $patient_id, $appointment_datetime, $reason_text);
        $res = $stmt->execute();
        $insertId = $conn->insert_id;
        $stmt->close();
        return $res ? $insertId : false;
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