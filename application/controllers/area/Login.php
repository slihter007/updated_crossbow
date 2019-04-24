<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Login extends CI_Controller {

	public $inactive;
	public $roles;

	function __construct() {

		parent::__construct();
		$this->load->model( 'Settings_Model' );
		define( 'LANG', $this->Settings_Model->get_crm_lang() );
		$this->lang->load( LANG, LANG );
		$settings = $this->Settings_Model->get_settings( 'ciuis' );
		$timezone = $settings[ 'default_timezone' ];
		date_default_timezone_set( $timezone );
		$this->load->model( 'Area_Model' );
		$this->load->model('Contacts_Model');
		$this->load->model('Emails_Model');
		$this->load->library( 'form_validation' );
		$this->form_validation->set_error_delimiters( '<div class="error">', '</div>' );
		$this->inactive = $this->config->item( 'inactive' );
		$this->roles = $this->config->item( 'roles' );
		$timezone = $settings[ 'default_timezone' ];
		date_default_timezone_set( $timezone );
	}

	function index() {
		if ( isset( $_SESSION[ 'logged_in' ] ) && $_SESSION[ 'logged_in' ] === true ) {
			redirect( 'area/panel' );
		} else {
			redirect( 'area/login/auth' );
		}
	}

	function auth() {
		$data = new stdClass();
		$this->load->helper( 'form' );
		$this->load->library( 'form_validation' );
		$this->form_validation->set_rules( 'email', 'Email', 'required' );
		$this->form_validation->set_rules( 'password', 'Password', 'required' );
		if ( $this->form_validation->run() == false ) {
			$this->load->view( 'area/login/login' );
		} else {
			$email = $this->input->post( 'email' );
			$password = $this->input->post( 'password' );
			if ( $this->Area_Model->resolve_user_login( $email, $password ) ) {
				$contact_id = $this->Area_Model->get_contact_id_from_email( $email );
				$user = $this->Area_Model->get_user( $contact_id );
				$_SESSION[ 'contact_id' ] = ( int )$user->id;
				$_SESSION[ 'customer' ] = ( int )$user->customer_id;
				$_SESSION[ 'name' ] = ( string )$user->name;
				$_SESSION[ 'surname' ] = ( string )$user->surname;
				$_SESSION[ 'email' ] = ( string )$user->email;
				$_SESSION[ 'admin' ] = ( string )$user->admin;
				$_SESSION[ 'logged_in' ] = ( bool )true;
				redirect( 'area/panel' );
			} else {
				$data->error = lang( 'wrongmessage' );
				$this->load->view( 'area/login/login', $data );

			}
		}
	}

	function logout() {
		$data = new stdClass();
		if ( isset( $_SESSION[ 'logged_in' ] ) && $_SESSION[ 'logged_in' ] === true ) {
			foreach ( $_SESSION as $key => $value ) {
				unset( $_SESSION[ $key ] );
			}
			redirect( '/area' );
		} else {
			redirect( '/area' );
		}
	}

	public function forgot() {

		$this->form_validation->set_rules( 'email', 'Email', 'required|valid_email' );

		if ( $this->form_validation->run() == FALSE ) {
			$this->load->view( 'area/login/forgot' );
		} else {
			$email = $this->input->post( 'email' );
			$clean = $this->security->xss_clean( $email );
			$userInfo = $this->Contacts_Model->getUserInfoByEmail( $clean );

			if ( !$userInfo ) {
				$this->session->set_flashdata( 'ntf4', lang( 'customercanffindmail' ) );
				redirect( site_url() . 'area/login' );
			}

			//build token 

			$token = $this->Contacts_Model->insertToken( $userInfo->id );
			$qstring = $this->base64url_encode( $token );
			$url = site_url() . 'area/login/reset_password/' . $qstring;
			$link = '<a href="' . $url . '">' . $url . '</a>';

			$template = $this->Emails_Model->get_template('staff', 'customer_forgot_password');
			$message_vars = array(
				'{password_url}' => $url,
				'{customer}' => $userInfo->name,
				'{email_signature}' => $template['from_name'],
			);
			$subject = strtr($template['subject'], $message_vars);
			$message = strtr($template['message'], $message_vars);

			$param = array(
				'from_name' => $template['from_name'],
				'email' => $clean,
				'subject' => $subject,
				'message' => $message,
				'created' => date( "Y.m.d H:i:s" ),
				'status' => 0
			);
			if ($clean) {
				$this->db->insert( 'email_queue', $param );
			}
			$this->load->library('mail');
			$this->mail->send_email($clean, $template['from_name'], $subject, $message);
			
			$this->session->set_flashdata( 'ntf1', '<b>' . lang( 'customerpasswordsend' ) . '</b>' );
			redirect( 'area/login' );
		}
	}

	public function reset_password($token_coming) {
		$token = $this->base64url_decode( $token_coming );
		$cleanToken = $this->security->xss_clean( $token );

		$user_info = $this->Contacts_Model->isTokenValid( $cleanToken ); //either false or array();               

		if ( !$user_info ) {
			$this->session->set_flashdata( 'ntf1', lang( 'tokenexpired' ) );
			redirect( site_url() . 'area/login' );
		}
		$data = array(
			'firstName' => $user_info->name,
			'email' => $user_info->email,
			'contact_id'=>$user_info->id, 
			'token' => $this->base64url_encode( $token )
		);

		$this->form_validation->set_rules( 'password', 'Password', 'required|min_length[5]' );
		$this->form_validation->set_rules( 'passconf', 'Password Confirmation', 'required|matches[password]' );

		if ( $this->form_validation->run() == FALSE ) {
			$this->load->view( 'area/login/reset_password', $data );
		} else {

			$post = $this->input->post( NULL, TRUE );
			$cleanPost = $this->security->xss_clean( $post );
			$hashed = password_hash( $cleanPost[ 'password' ], PASSWORD_BCRYPT );
			$cleanPost[ 'password' ] = $hashed;
			$cleanPost[ 'contact_id' ] = $user_info->id;
			unset( $cleanPost[ 'passconf' ] );
			if ( !$this->Contacts_Model->updatePassword( $cleanPost ) ) {
				$this->session->set_flashdata( 'ntf1', lang( 'problemupdatepassword' ) );
			} else {
				$this->session->set_flashdata( 'ntf1', lang( 'passwordupdated' ) );
				$template = $this->Emails_Model->get_template('staff', 'customer_password_reset');
				$message_vars = array(
					'{email}' => $user_info->email,
					'{contact}' => $userInfo->name,
					'{email_signature}' => $template['from_name'],
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $user_info->email,
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" ),
					'status' => 0
				);
				if ($user_info->email) {
					$this->db->insert( 'email_queue', $param );
				}
				$this->load->library('mail');
				$this->mail->send_email($user_info->email, $template['from_name'], $subject, $message);
			}
			redirect( site_url() . 'area/login' );
		}
	}

	public

	function base64url_encode( $data ) {
		return rtrim( strtr( base64_encode( $data ), '+/', '-_' ), '=' );
	}

	public

	function base64url_decode( $data ) {
		return base64_decode( str_pad( strtr( $data, '-_', '+/' ), strlen( $data ) % 4, '=', STR_PAD_RIGHT ) );
	}


}