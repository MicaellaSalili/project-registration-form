<?php
// ENUM values
define('SEATS_TYPE_SOLO', 'solo');
define('SEATS_TYPE_DUO', 'duo');
define('SEATS_TYPE_GROUPED', 'grouped');

define('BOOKING_STATUS_PENDING', 'pending');
define('BOOKING_STATUS_CONFIRMED', 'confirmed');
define('BOOKING_STATUS_CANCELLED', 'cancelled');

define('DIGITAL_PAYMENT_TYPE_GCASH', 'gcash');
define('DIGITAL_PAYMENT_TYPE_MAYA', 'maya');
define('DIGITAL_PAYMENT_TYPE_SEABANK', 'seabank');

define('CARD_TYPE_DEBIT', 'debit');
define('CARD_TYPE_CREDIT', 'credit');

// Booking form 
class Booking {
    public $id;
    public $first_name;
    public $last_name;
    public $date_time;
    public $email;
    public $phone_number;
    public $hours_booked;
    public $student;
    public $notes;
    
    public function __construct($first_name, $last_name, $date_time, $email, $phone_number, $seats_booked, $notes = null) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->date_time = $date_time;
        $this->email = $email;
        $this->phone_number = $phone_number;
        $this->hours_booked = $hours_booked; 
        $this->student = $student;
        $this->notes = $notes;
    }
    
    public function validate() {
        if (empty($this->first_name) || empty($this->last_name) || empty($this->date_time) || empty($this->email) || empty($this->seats_booked)) {
            return false;
        }

        if (!is_numeric($this->hours_booked) || $this->hours_booked <= 0) {
            return false; 

        return true;
    }
}

// BookingCard 
class BookingCard {
    public $id;
    public $booking_id;
    public $status;
    
    public function __construct($booking_id, $status) {
        $this->booking_id = $booking_id;
        $this->status = $status;
    }

    public function validate() {
        // Validate booking status
        $valid_statuses = [BOOKING_STATUS_PENDING, BOOKING_STATUS_CONFIRMED, BOOKING_STATUS_CANCELLED];
        if (!in_array($this->status, $valid_statuses)) {
            return false;
        }
        return true;
    }
}

// For payment section 
class PaymentSection {
    public $id;
    public $booking_id;
    public $digital_payment;
    public $pay_on_the_counter;
    
    public function __construct($booking_id, $digital_payment = null, $pay_on_the_counter = null) {
        $this->booking_id = $booking_id;
        $this->digital_payment = $digital_payment;
        $this->pay_on_the_counter = $pay_on_the_counter;
    }

    public function validate() {
        $valid_payment_types = [DIGITAL_PAYMENT_TYPE_GCASH, DIGITAL_PAYMENT_TYPE_MAYA, DIGITAL_PAYMENT_TYPE_SEABANK];
        if ($this->digital_payment && !in_array($this->digital_payment, $valid_payment_types)) {
            return false;
        }
        return true;
    }
}

// If bank payment
class BankPayment {
    public $id;
    public $payment_section_id;
    public $account_name;
    public $account_num;
    public $amount;
    public $send_receipt_to;
    
    public function __construct($payment_section_id, $account_name, $account_num, $amount, $send_receipt_to) {
        $this->payment_section_id = $payment_section_id;
        $this->account_name = $account_name;
        $this->account_num = $account_num;
        $this->amount = $amount;
        $this->send_receipt_to = $send_receipt_to;
    }

    public function validate() {
        if (empty($this->account_name) || empty($this->account_num) || empty($this->amount) || empty($this->send_receipt_to)) {
            return false;
        }
        return true;
    }
}

// If debit or credit card payment
class DebitCredit {
    public $id;
    public $payment_section_id;
    public $type_of_card;
    public $holder_name;
    public $card_num;
    public $cvc_cvv;
    
    public function __construct($payment_section_id, $type_of_card, $holder_name, $card_num, $cvc_cvv) {
        $this->payment_section_id = $payment_section_id;
        $this->type_of_card = $type_of_card;
        $this->holder_name = $holder_name;
        $this->card_num = $card_num;
        $this->cvc_cvv = $cvc_cvv;
    }

    public function validate() {

        $valid_card_types = [CARD_TYPE_DEBIT, CARD_TYPE_CREDIT];
        if (!in_array($this->type_of_card, $valid_card_types)) {
            return false;
        }
        return true;
    }
}
?>
