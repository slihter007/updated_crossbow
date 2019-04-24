<?php
class Emails_Model extends CI_Model {

	function get_email_templates() {
		return $this->db->get_where( 'email_templates', array( 'display' => 1 ) )->result_array();
	}

	function get_sent_emails() {
		$this->db->order_by('id', 'desc');
		return $this->db->get_where( 'email_queue', array( 'status' => 0 ) )->result_array();
	}

	function get_email_template($id) {
		return $this->db->get_where( 'email_templates', array( 'id' => $id ) )->row_array();
	}

	function template_fields($id) {
		return $this->db->get_where( 'email_template_fields', array( 'template_id' => $id ) )->result_array();
	}

	function update_template( $id, $params ) {
		$this->db->where( 'id', $id );
		$this->db->update('email_templates', $params );
		return true;
	}

	function get_emails() {
		$this->db->select( '*' );
		$this->db->from('email_queue');
		$this->db->where('status = 1');
		$this->db->order_by('id', 'asc');
		$this->db->limit(10);
		return $this->db->get()->result_array();
	}

	function email_sent($id) {
		$this->db->where( $id, $id );
		$this->db->update('email_queue', array( 'status' => 0 ) );
		return true;
	}

	function get_template($relation, $name) {
		$template = $this->db->get_where( 'email_templates', array( 'relation' => $relation, 'name' => $name ) )->row_array();
		if (count($template) > 0) {
			return $template;
		} else {
			$data = array(
				'status' => 0,
				'template' => $template
			);
			return $data;
		}
	}
}