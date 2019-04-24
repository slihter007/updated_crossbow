<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Privileges_Model extends CI_MODEL {

	function __construct() {
		parent::__construct();
	}

	function get_staff_permissions( $id ) {
		$array = array('relation_type' => 'staff', 'relation' => $id);
		$result = $this->db->select( 'permission_id' )->where($array)->get( 'privileges' );
		return array_column( $result->result_array(), 'permission_id' );
	}
	
	function has_privilege( $path ) {
		$relation = $this->session->usr_id;
		$this->db->select( '*,permissions.key as permission_key');
		$this->db->join( 'permissions', 'privileges.permission_id = permissions.id', 'left' );
		$rows = $this->db->get_where( 'privileges', array( 'permissions.key' => $path, 'relation' => $relation, 'relation_type' => 'staff') )->num_rows();
		return ( $rows > 0 ) ? TRUE : FALSE;
	}
	
	function contact_has_privilege( $path ) {
		$relation = $_SESSION[ 'contact_id' ];
		$this->db->select( '*,permissions.key as permission_key');
		$this->db->join( 'permissions', 'privileges.permission_id = permissions.id', 'left' );
		$rows = $this->db->get_where( 'privileges', array( 'permissions.key' => $path, 'relation' => $relation, 'relation_type' => 'contact') )->num_rows();
		return ( $rows > 0 ) ? TRUE : FALSE;
	}

	function get_privileges() {
		$query = $this->db->get( 'privileges' );
		return $query->result_array();
	}

	function get_all_permissions() {
		return $this->db->get( 'permissions' )->result_array();
	}
	
	function get_all_common_permissions() {
		return $this->db->get_where( 'permissions', array( 'type' => 'common') )->result_array();
	}

	function add_privilege( $id, $privileges ) {
		$array = array('relation_type' => 'staff', 'relation' => $id);
		$delete_old = $this->db->where($array)->delete( 'privileges' );
		$data = array();
		foreach ( $privileges as $key ) {
			$arr = array(
				'relation' => ( int )$id,
				'relation_type' => 'staff',
				'permission_id' => ( int )$key
			);

			array_push( $data, $arr );
		}
		$insert_new = $this->db->insert_batch( 'privileges', $data );

		if ( $insert_new ) {
			return TRUE;
		} else {
			return FALSE;
		}

	}
	
	function add_contact_privilege( $id, $privileges ) {
		$array = array('relation_type' => 'contact', 'relation' => $id);
		$delete_old = $this->db->where($array)->delete( 'privileges' );
		$data = array();
		foreach ( $privileges as $key ) {
			$arr = array(
				'relation' => ( int )$id,
				'relation_type' => 'contact',
				'permission_id' => ( int )$key
			);

			array_push( $data, $arr );
		}
		$insert_new = $this->db->insert_batch( 'privileges', $data );

		if ( $insert_new ) {
			return TRUE;
		} else {
			return FALSE;
		}

	}

}