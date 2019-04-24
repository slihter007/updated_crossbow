<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Panel extends CIUIS_Controller {
	function index() {
		$rebrand = load_config();
		$data[ 'title' ] = $rebrand['title'];
		$this->load->view( 'inc/header', $data );
		if ($this->session->userdata('other')) {
			$this->load->view( 'panel/consultant-panel', $data );
		} else {
			$this->load->view( 'panel/index', $data );
		}
		$this->load->view( 'inc/footer', $data );
	}
}