-- Create booking_notifications table
CREATE TABLE IF NOT EXISTS booking_notifications (
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
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
);

-- Optional: Create indexes for better query performance
ALTER TABLE booking_notifications ADD INDEX idx_booking_id (booking_id) IF NOT EXISTS;
ALTER TABLE booking_notifications ADD INDEX idx_physician_id (physician_id) IF NOT EXISTS;
