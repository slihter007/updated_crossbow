<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Appointments extends AREA_Controller {

	function index() {
		echo 'Appointments';
	}

	function new_appointment() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$booked_date_time = '' . $_POST[ 'booking_date' ] . ' ' . $_POST[ 'start_time' ] . ':00';
			$minutes_to_add = 30;
			$time = new DateTime( $booked_date_time );
			$time->add( new DateInterval( 'PT' . $minutes_to_add . 'M' ) );
			$params = array(
				'booking_date' => $_POST[ 'booking_date' ],
				'start_time' => $_POST[ 'start_time' ],
				'staff_id' => $_POST[ 'staff_id' ],
				'end_time' => $time->format( 'H:i:s' ),
				'contact_id' => $_SESSION[ 'contact_id' ],
			);
			$appointment = $this->Appointments_Model->new_appointment( $params );
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => '' . $message = sprintf( lang( 'x_wants_an_appointment' ), $_SESSION[ 'name' ] ) . '',
				'perres' => 'n-img.png',
				'staff_id' => $_POST[ 'staff_id' ],
				'target' => '' . base_url( 'calendar' ) . ''
			) );
			echo lang( 'appointment_request_sent' );
		}
	}
}