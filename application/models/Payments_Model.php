<?php
class Payments_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function addpayment( $params ) {

		if ( $this->input->post( 'balance' ) == 0 ) {
			$response = $this->db->where( 'id', $this->input->post( 'invoice' ) )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			$response = $this->db->where( 'invoice_id', $this->input->post( 'invoice' ) )->update( 'sales', array( 'status_id' => 2 ) );
		} else {
			$response = $this->db->where( 'id', $this->input->post( 'invoice' ) )->update( 'invoices', array( 'status_id' => 3 ) );
			$response = $this->db->where( 'invoice_id', $this->input->post( 'invoice' ) )->update( 'sales', array( 'status_id' => 3 ) );
		}
		$this->db->insert( 'payments', $params );
		return $this->db->insert_id();
	}

	function todaypayments() {
		return $this->db->get_where( 'payments', array( 'DATE(date)' => date( 'Y-m-d' ) ) )->result_array();
	}

	function todaypayments_by_staff() {
		return $this->db->get_where( 'payments', array( 'DATE(date)' => date( 'Y-m-d' ), 'staff_id' => $this->session->usr_id ) )->result_array();
	}
}