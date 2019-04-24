<?php
class Events_Model extends CI_Model {

	function get_all_events() {
		$this->db->select( '*,,staff.staffname as staff, staff.staffavatar as staff_avatar, events.id as id ' );
		$this->db->join( 'staff', 'events.staff_id = staff.id', 'left' );
		$this->db->where( 'public = "true" AND staff_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		return $this->db->get_where( 'events' )->result_array();
	}

	function add_event( $params ) {
		$this->db->insert( 'events', $params );
		return $this->db->insert_id();
	}

	function remove( $id ) {
		$response = $this->db->delete( 'events', array( 'id' => $id ) );
	}

}