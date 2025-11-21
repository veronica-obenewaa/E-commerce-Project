-- Migration: create physician_bookings table
-- Backup your DB before running

CREATE TABLE IF NOT EXISTS `physician_bookings` (
  `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `physician_id` INT NOT NULL,
  `patient_id` INT DEFAULT NULL,
  `appointment_datetime` DATETIME DEFAULT NULL,
  `reason_text` VARCHAR(1000) DEFAULT NULL,
  `status` ENUM('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (`physician_id`),
  INDEX (`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optionally add foreign keys if your user table is `customer` and `customer_id` is PK
-- ALTER TABLE `physician_bookings`
--   ADD CONSTRAINT fk_physician FOREIGN KEY (`physician_id`) REFERENCES `customer`(`customer_id`) ON DELETE CASCADE,
--   ADD CONSTRAINT fk_patient FOREIGN KEY (`patient_id`) REFERENCES `customer`(`customer_id`) ON DELETE SET NULL;
