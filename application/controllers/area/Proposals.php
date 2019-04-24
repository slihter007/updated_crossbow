<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Proposals extends AREA_Controller {


	function index() {
		$data[ 'title' ] = lang( 'areatitleproposals' );
		$data[ 'proposals' ] = $this->db->select( '*,staff.staffname as staffmembername,staff.staffavatar as staffavatar,customers.company as customer,customers.email as toemail,customers.namesurname as individual,customers.address as toaddress, proposals.id as id ' )->join( 'customers', 'proposals.relation = customers.id', 'left' )->join( 'staff', 'proposals.assigned = staff.id', 'left' )->get_where( 'proposals', array( 'relation' => $_SESSION[ 'customer' ], 'relation_type' => 'customer' ) )->result_array();
		//Detaylar
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'area/proposals/index', $data );

	}

	function proposal( $token ) {
		$proposal = $this->Proposals_Model->get_proposal_by_token( $token );
		$id = $proposal[ 'id' ];
		$data[ 'title' ] = 'PRO-' . $id . ' Detail';
		$this->load->model( 'Proposals_Model' );
		$this->load->model( 'Settings_Model' );
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'proposals' ] = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'proposal', 'relation' => $id ) )->result_array();
		$data[ 'comments' ] = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'proposal' ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'share/proposal', $data );
	}
}