-- Migration: add Zoom meeting fields to physician_bookings table
-- Adds support for Zoom meeting links and meeting IDs

ALTER TABLE `physician_bookings`
ADD COLUMN `zoom_meeting_id` VARCHAR(50) DEFAULT NULL AFTER `status`,
ADD COLUMN `zoom_join_url` VARCHAR(500) DEFAULT NULL AFTER `zoom_meeting_id`,
ADD COLUMN `zoom_start_url` VARCHAR(500) DEFAULT NULL AFTER `zoom_join_url`,
ADD COLUMN `zoom_password` VARCHAR(50) DEFAULT NULL AFTER `zoom_start_url`,
ADD COLUMN `zoom_created_at` TIMESTAMP NULL AFTER `zoom_password`,
ADD INDEX (`zoom_meeting_id`);

-- Optional: Add a status column for Zoom meeting (pending, created, cancelled)
-- This helps track if the meeting was successfully created in Zoom
ALTER TABLE `physician_bookings`
ADD COLUMN `zoom_status` ENUM('pending','created','cancelled') DEFAULT 'pending' AFTER `zoom_created_at`;
