<?php
include_once("../classes/booking_class.php");

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new booking_class();
    }

    public function getBookingsByPhysician($physician_id) {
        if (empty($physician_id) || !is_numeric($physician_id)) {
            return ['status' => 'error', 'message' => 'Invalid physician id'];
        }
        $rows = $this->bookingModel->getBookingsByPhysician(intval($physician_id));
        // Filter out cancelled bookings
        $active_rows = array_filter($rows, function($row) {
            return ($row['status'] ?? 'scheduled') !== 'cancelled';
        });
        return ['status' => 'success', 'data' => array_values($active_rows)];
    }

    public function createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text) {
        return $this->bookingModel->createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text);
    }

    public function updateBookingStatus($booking_id, $status) {
        if (empty($booking_id) || !is_numeric($booking_id)) {
            return ['status' => 'error', 'message' => 'Invalid booking id'];
        }
        $res = $this->bookingModel->updateBookingStatus(intval($booking_id), $status);
        return $res ? ['status' => 'success', 'message' => 'Booking status updated'] : ['status' => 'error', 'message' => 'Failed to update booking status'];
    }
}

?>