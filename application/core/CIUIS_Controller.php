<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

class CIUIS_Controller extends CI_Controller {

	public $loggedinuserid;
	protected

	function only_admin_access() {
		if ( $this->session->userdata( 'admin' ) );
		else {
			redirect( "panel" );
		};
	}
	public

	function __construct() {
		parent::__construct();
		$this->load->helper( 'security' );
		$this->load->model( 'Settings_Model' );
		$this->load->model( 'Staff_Model' );
		
		if ( $this->session->userdata( 'LoginOK' ) ) {
			$this->lang->load( '' . $this->session->userdata( 'language' ) . '', '' . $this->session->userdata( 'language' ));
		} else {
			define('LANG', $this->Settings_Model->get_crm_lang());
			$this->lang->load( LANG, LANG );
			define( 'TWFAUTH', $this->Settings_Model->two_factor_authentication() );
			if ( TWFAUTH == 1 ) {
				if ( !$this->session->userdata( '2FAVerify' ) ) {
					$this->session->sess_destroy();
					redirect( base_url( 'login' ) );
				}
			}
		}
			
		if ( $this->session->userdata( 'LoginOK' ) ) {
			$this->load->model( 'Login_Model' );
			$this->load->model( 'Privileges_Model' );
			$this->load->model( 'Customers_Model' );
			$this->load->model( 'Staff_Model' );
			$this->load->model( 'Products_Model' );
			$this->load->model( 'Tickets_Model' );
			$this->load->model( 'Settings_Model' );
			$this->load->model( 'Invoices_Model' );
			$this->load->model( 'Proposals_Model' );
			$this->load->model( 'Orders_Model' );
			$this->load->model( 'Tickets_Model' );
			$this->load->model( 'Report_Model' );
			$this->load->model( 'Logs_Model' );
			$this->load->model( 'Sales_Model' );
			$this->load->model( 'Projects_Model' );
			$this->load->model( 'Notifications_Model' );
			$this->load->model( 'Contacts_Model' );
			$this->load->model( 'Events_Model' );
			$this->load->model( 'Appointments_Model' );
			$this->load->model( 'Tasks_Model' );
			$this->load->model( 'Accounts_Model' );
			$this->load->model( 'Payments_Model' );
			$this->load->model( 'Expenses_Model' );
			$this->load->model( 'Trivia_Model' );
			$this->load->model( 'Leads_Model' );
			$this->load->model( 'Fields_Model' );
			$this->load->model( 'Search_Model' );
			$this->load->model( 'Emails_Model' );
			$loggedinuserid = $this->Login_Model->usr_id();
			if ( !$loggedinuserid ) {
				redirect( base_url() . '' );
			}
			$this->logged_in_staff = $this->Login_Model->get_logged_in_staff_info( $loggedinuserid );
			define( 'currency', $this->Settings_Model->get_currency() );
			define( 'if_admin', $this->Login_Model->if_admin() );
			define( 'current_user_id', $this->session->userdata( 'usr_id' ) );
			define( 'timezone', $this->Settings_Model->default_timezone() );
			date_default_timezone_set( timezone );
		} else {
			redirect( base_url( 'login' ) );
		}
	}

}

class AREA_Controller extends CI_Controller { 
	function __construct() {
		parent::__construct();
		if ( isset( $_SESSION[ 'logged_in' ] ) && $_SESSION[ 'logged_in' ] === true ) {
			$this->load->model( 'Settings_Model' );
			define( 'LANG', $this->Settings_Model->get_crm_lang() );
			$this->lang->load( LANG, LANG );
			$this->load->library( array( 'session' ) );
			$this->load->helper( array( 'url' ) );
			$this->load->library( 'form_validation' );
			$this->form_validation->set_error_delimiters( '<div class="error">', '</div>' );
			$this->inactive = $this->config->item( 'inactive' );
			$this->roles = $this->config->item( 'roles' );
			$this->load->model( 'Area_Model' );
			$this->load->model( 'Customers_Model' );
			$this->load->model( 'Staff_Model' );
			$this->load->model( 'Products_Model' );
			$this->load->model( 'Tickets_Model' );
			$this->load->model( 'Settings_Model' );
			$this->load->model( 'Invoices_Model' );
			$this->load->model( 'Report_Model' );
			$this->load->model( 'Logs_Model' );
			$this->load->model( 'Sales_Model' );
			$this->load->model( 'Notifications_Model' );
			$this->load->model( 'Contacts_Model' );
			$this->load->model( 'Appointments_Model' );
			$this->load->model( 'Projects_Model' );
			$this->load->model( 'Tasks_Model' );
			$this->load->model( 'Accounts_Model' );
			$this->load->model( 'Payments_Model' );
			$this->load->model( 'Expenses_Model' );
			$this->load->model( 'Proposals_Model' );
			$this->load->model( 'Privileges_Model' );
			$this->load->model( 'Emails_Model' );
			$data[ 'contacts' ] = $this->Contacts_Model->get_all_contacts();
			define( 'currency', $this->Settings_Model->get_currency() );
			define( 'timezone', $this->Settings_Model->default_timezone() );
			date_default_timezone_set( timezone );
		} else {
			redirect( 'area/login' );
		}
	}
}