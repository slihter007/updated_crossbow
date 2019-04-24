<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Trivia extends AREA_Controller {

	function mark_read_notification( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'notifications', array( 'customerread' => ( 1 ) ) );
		}
	}

}