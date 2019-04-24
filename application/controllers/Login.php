<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Login extends CI_Controller {


	public $inactive;
	public $roles;

	function __construct() {
		parent::__construct();
		$this->load->library( 'Google' );
		$this->load->model( 'Settings_Model' );
		define( 'LANG', $this->Settings_Model->get_crm_lang() );
		$this->lang->load( LANG, LANG );
		$settings = $this->Settings_Model->get_settings( 'ciuis' );
		$timezone = $settings[ 'default_timezone' ];
		date_default_timezone_set( $timezone );
		$this->load->model( 'Staff_Model' );
		$this->load->model( 'Emails_Model' );
		$this->load->library( 'form_validation' );
		$this->form_validation->set_error_delimiters( '<div class="error">', '</div>' );
		$this->inactive = $this->config->item( 'inactive' );
		$this->roles = $this->config->item( 'roles' );
		$timezone = $settings[ 'default_timezone' ];
		date_default_timezone_set( $timezone );
	}

	function index() {
		$settings = $this->Settings_Model->get_settings( 'ciuis' );
		if ( $settings[ 'two_factor_authentication' ] == 1 ) {
			if ( $this->session->userdata( 'LoginOK' ) && $this->session->userdata( '2FAVerify' ) ) {
				redirect( base_url() . 'panel' );
			} else {
				$this->show_login( false );
			}
		} else {
			if ( $this->session->userdata( 'LoginOK' ) ) {
				redirect( base_url() . 'panel' );
			} else {
				$this->show_login( false );
			}
		}
	}

	function auth() {
		$settings = $this->Settings_Model->get_settings( 'ciuis' );
		$this->load->model( 'Login_Model' );
		$email = $this->input->post( 'email' );
		$password = $this->input->post( 'password' );
		$clean = $this->security->xss_clean( $email );

		if ( $userInfo = $this->Staff_Model->getUserInfoByEmail( $clean ) ) {
			if ( $userInfo->inactive != $this->inactive[ 1 ] ) { //if inactive is not approved
				$this->session->set_flashdata( 'ntf4', lang( 'customerinactiveaccount' ) );
				redirect( site_url() . 'login' );
			}
		} else {
			$this->session->set_flashdata( 'ntf4', lang( 'customercanffindmail' ) );
			redirect( site_url() . 'login' );
		}
		if ( $email && $password && $this->Login_Model->validate_user( $email, $password ) ) {
			if ( $settings[ 'two_factor_authentication' ] == 1 ) { 
				redirect( base_url( 'login/verify_login' ) );
			} else {
				$this->session->set_flashdata( 'login_notification', lang( 'welcomemessagetwo' ));
				if ( $this->session->userdata( 'admin' ) ) {
					$this->session->set_flashdata( 'admin_notification', lang( 'adminwelcome' ));
				}
				$this->Staff_Model->update_staff($this->session->usr_id, array('language' => $this->input->post('language')));
				$this->session->set_userdata(array('language' => $this->input->post('language')));
				$staffname = $this->session->staffname;
				$loggedinuserid = $this->session->usr_id;
				$this->config->set_item('sess_expire_on_close', '0');
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'loggedinthesystem' )),
					'staff_id' => $loggedinuserid
				) );
				redirect( base_url( 'panel' ) );
			}
		} else {
			$this->show_login( true );
		}
	}

	function verify_login() {
		$this->load->model( 'Login_Model' );
		$data[ 'secret' ] = $this->google->createSecret();
		$website = "http://localhost:8888/googleautenticador/";
		$data[ 'url_qr_code' ] = $this->google->getQRCodeGoogleUrl( $this->session->userdata[ 'email' ], $data[ 'secret' ], $website );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$secret = $this->input->post( 'secret_code' );
			$code_verificador = $this->input->post( 'code' );
			$resultado = $this->google->verifyCode( $secret, $code_verificador, 0 );
			if ( $resultado ) {
				$this->Login_Model->two_factor_authentication();
				$this->session->set_flashdata( 'login_notification', '' . lang( 'welcomemessagetwo' ) . '' );
				if ( $this->session->userdata( 'admin' ) ) {
					$this->session->set_flashdata( 'admin_notification', '' . lang( 'adminwelcome' ) . '' );
				}
				$staffname = $this->session->staffname;
				$loggedinuserid = $this->session->usr_id;
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'loggedinthesystem' ) . '' ),
					'staff_id' => $loggedinuserid
				) );
				redirect( base_url( 'panel' ) );

			} else {

				$this->session->sess_destroy();
				redirect( base_url( 'login' ) );

			}
		} else {
			$this->load->view( 'login/verify', $data );
		}

	}

	function show_login( $show_error = false ) {
		$data[ 'error' ] = $show_error;
		$languages = $this->Settings_Model->get_languages();
		$lang = array();
		foreach ($languages as $language) {
			$lang[] = array(
				'name' => lang($language['name']),
				'foldername' => $language['foldername'],
				'id' => $language['id'],
				'langcode' => $language['langcode']
			);
		}
		$data['languages'] = $lang;
		$this->load->helper( 'form' );
		$this->load->view( 'login/login', $data );
	}

	function logout() {
		$this->session->sess_destroy();
		$this->index();
	}

	function showphpinfo() {
		echo phpinfo();
	}

	public

	function forgot() {

		$this->form_validation->set_rules( 'email', 'Email', 'required|valid_email' );

		if ( $this->form_validation->run() == FALSE ) {
			$this->load->view( 'login/forgot' );
		} else {
			$email = $this->input->post( 'email' );
			$clean = $this->security->xss_clean( $email );
			$userInfo = $this->Staff_Model->getUserInfoByEmail( $clean );
			if ( !$userInfo ) {
				$this->session->set_flashdata( 'ntf4', lang( 'customercanffindmail' ) );
				redirect( site_url() . 'login' );
			}
			if ( $userInfo->inactive != $this->inactive[ 1 ] ) { //if inactive is not approved
				$this->session->set_flashdata( 'ntf4', lang( 'customerinactiveaccount' ) );
				redirect( site_url() . 'login' );
			}

			$token = $this->Staff_Model->insertToken( $userInfo->id );
			$nameis = $userInfo->staffname;
			$qstring = $this->base64url_encode( $token );
			$url = site_url() . 'login/reset_password/token/' . $qstring;

			$template = $this->Emails_Model->get_template('staff', 'forgot_password');
			$message_vars = array(
				'{password_url}' => $url,
				'{staffname}' => $userInfo->staffname,
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
			redirect( 'login' );
		}

	}

	public

	function reset_password() {
		$token = $this->base64url_decode( $this->uri->segment( 4 ) );
		$cleanToken = $this->security->xss_clean( $token );

		$user_info = $this->Staff_Model->isTokenValid( $cleanToken ); //either false or array();               

		if ( !$user_info ) {
			$this->session->set_flashdata( 'ntf1', lang( 'tokenexpired' ) );
			redirect( site_url() . 'login' );
		}
		$data = array(
			'firstName' => $user_info->staffname,
			'email' => $user_info->email,
			//'user_id'=>$user_info->id, 
			'token' => $this->base64url_encode( $token )
		);

		$this->form_validation->set_rules( 'password', 'Password', 'required|min_length[5]' );
		$this->form_validation->set_rules( 'passconf', 'Password Confirmation', 'required|matches[password]' );

		if ( $this->form_validation->run() == FALSE ) {
			$this->load->view( 'login/reset_password', $data );
		} else {

			$post = $this->input->post( NULL, TRUE );
			$cleanPost = $this->security->xss_clean( $post );
			$hashed = md5( $cleanPost[ 'password' ] );
			$cleanPost[ 'password' ] = $hashed;
			$cleanPost[ 'user_id' ] = $user_info->id;
			unset( $cleanPost[ 'passconf' ] );
			if ( !$this->Staff_Model->updatePassword( $cleanPost ) ) {
				$this->session->set_flashdata( 'ntf1', lang( 'problemupdatepassword' ) );
			} else {
				$this->session->set_flashdata( 'ntf1', lang( 'passwordupdated' ) ); 
			}

			$template = $this->Emails_Model->get_template('staff', 'password_reset');
			$message_vars = array(
				'{staff_email}' => $user_info->email,
				'{staffname}' => $userInfo->staffname,
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
			redirect( site_url() . 'login' );
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