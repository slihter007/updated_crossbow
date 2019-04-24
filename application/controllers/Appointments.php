<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Appointments extends CIUIS_Controller {

	function index() {
		$data[ 'title' ] = 'Appointments';
		$this->load->view( 'appointments/index', $data );
	}

	function confirm_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'appointments', array( 'status' => 1 ) );
		}
	}

	function decline_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'appointments', array( 'status' => 2 ) );

		}
	}

	function mark_as_done_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'appointments', array( 'status' => 3 ) );

		}
	}

	function remove_appointment( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->delete( 'appointments', array( 'id' => $id ) );

		}
	}
}