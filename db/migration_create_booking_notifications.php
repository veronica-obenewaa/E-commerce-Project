<?php
// Create booking_notifications table if it doesn't exist
require_once __DIR__ . '/../settings/db_class.php';

class booking_notifications_setup {
    public static function createTable() {
        $db = new db_connection();
        $conn = $db->db_conn();
        
        $sql = "CREATE TABLE IF NOT EXISTS booking_notifications (
            notification_id INT AUTO_INCREMENT PRIMARY KEY,
            booking_id INT NOT NULL,
            patient_id INT NOT NULL,
            physician_id INT NOT NULL,
            notification_type VARCHAR(50) NOT NULL DEFAULT 'cancellation',
            message TEXT,
            is_read BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            read_at TIMESTAMP NULL,
            FOREIGN KEY (booking_id) REFERENCES physician_bookings(booking_id) ON DELETE CASCADE,
            FOREIGN KEY (patient_id) REFERENCES customer(customer_id) ON DELETE CASCADE,
            FOREIGN KEY (physician_id) REFERENCES customer(customer_id) ON DELETE CASCADE,
            INDEX idx_patient_id (patient_id),
            INDEX idx_is_read (is_read)
        )";
        
        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            error_log("Error creating notifications table: " . $conn->error);
            return false;
        }
    }
}

// Run setup
booking_notifications_setup::createTable();
?>
