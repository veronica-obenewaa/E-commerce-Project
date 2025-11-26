<?php
require_once(__DIR__ . "/../settings/db_class.php");

class notification_class extends db_connection {

    // Create a notification for booking cancellation
    public function createNotification($booking_id, $patient_id, $physician_id, $notification_type = 'cancellation', $message = null) {
        try {
            $conn = $this->db_conn();
            
            if (empty($message)) {
                $message = $notification_type === 'cancellation' 
                    ? 'Your consultation appointment has been cancelled by the physician.' 
                    : 'You have a new notification about your appointment.';
            }
            
            $sql = "INSERT INTO booking_notifications (booking_id, patient_id, physician_id, notification_type, message) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                // Table may not exist yet
                return false;
            }
            $stmt->bind_param("iisss", $booking_id, $patient_id, $physician_id, $notification_type, $message);
            $result = $stmt->execute();
            $insertId = $conn->insert_id;
            $stmt->close();
            
            return $result ? $insertId : false;
        } catch (Exception $e) {
            error_log("Error creating notification: " . $e->getMessage());
            return false;
        }
    }

    // Get unread notifications for a patient
    public function getUnreadNotifications($patient_id) {
        try {
            $conn = $this->db_conn();
            $sql = "SELECT n.*, 
                    c.customer_name as physician_name, c.hospital_name,
                    pb.appointment_datetime, pb.status as booking_status
                    FROM booking_notifications n
                    LEFT JOIN customer c ON n.physician_id = c.customer_id
                    LEFT JOIN physician_bookings pb ON n.booking_id = pb.booking_id
                    WHERE n.patient_id = ? AND n.is_read = FALSE
                    ORDER BY n.created_at DESC";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                return [];
            }
            $stmt->bind_param("i", $patient_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $notifications = [];
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
            $stmt->close();
            return $notifications;
        } catch (Exception $e) {
            error_log("Error getting unread notifications: " . $e->getMessage());
            return [];
        }
    }

    // Get all notifications for a patient
    public function getNotifications($patient_id, $limit = 10, $offset = 0) {
        $conn = $this->db_conn();
        $sql = "SELECT n.*, 
                c.customer_name as physician_name, c.hospital_name,
                pb.appointment_datetime, pb.status as booking_status
                FROM booking_notifications n
                LEFT JOIN customer c ON n.physician_id = c.customer_id
                LEFT JOIN physician_bookings pb ON n.booking_id = pb.booking_id
                WHERE n.patient_id = ?
                ORDER BY n.created_at DESC
                LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $patient_id, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        $stmt->close();
        return $notifications;
    }

    // Mark notification as read
    public function markAsRead($notification_id) {
        $conn = $this->db_conn();
        $sql = "UPDATE booking_notifications SET is_read = TRUE, read_at = NOW() WHERE notification_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $notification_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Mark all notifications as read for a patient
    public function markAllAsRead($patient_id) {
        $conn = $this->db_conn();
        $sql = "UPDATE booking_notifications SET is_read = TRUE, read_at = NOW() 
                WHERE patient_id = ? AND is_read = FALSE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Get count of unread notifications
    public function getUnreadCount($patient_id) {
        $conn = $this->db_conn();
        $sql = "SELECT COUNT(*) as count FROM booking_notifications 
                WHERE patient_id = ? AND is_read = FALSE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row['count'] ?? 0;
    }

    // Delete notification
    public function deleteNotification($notification_id) {
        $conn = $this->db_conn();
        $sql = "DELETE FROM booking_notifications WHERE notification_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $notification_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}

?>
