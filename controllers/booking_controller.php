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
        return ['status' => 'success', 'data' => $rows];
    }

    public function createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text) {
        return $this->bookingModel->createBooking($physician_id, $patient_id, $appointment_datetime, $reason_text);
    }
}

?>