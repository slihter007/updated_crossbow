<?php
class Login_Model extends CI_Model {

	var $details;

	function validate_user( $email, $password ) {
		$this->db->from( 'staff' );
		$this->db->where( 'email', $email );
		$this->db->where( 'password', md5( $password ) );
		$login = $this->db->get()->result();

		if ( is_array( $login ) && count( $login ) == 1 ) {
			$this->details = $login[ 0 ];
			if ($this->input->post('remember')) {
				$data['new_expiration'] = 30*24*60*60*1000;
				$this->config->set_item('sess_expiration', $data['new_expiration']);
				$this->config->set_item('sess_expire_on_close', FALSE);
				$this->session->sess_expiration = $data['new_expiration'];
			} else {
				$this->config->set_item('sess_expire_on_close', TRUE);
			}
			$this->set_session();
			return true;
		}
		return false;
	}

	function two_factor_authentication() {
		$this->set_two_factor_authentication_session();
	}

	function get_logged_in_staff_info( $loggedinuserid = 0 ) {
		$staff_tablo = $this->db->dbprefix( 'staff' );
		$sql = "SELECT $staff_tablo.id,$staff_tablo.root,$staff_tablo.language, $staff_tablo.admin,$staff_tablo.staffmember,$staff_tablo.email,$staff_tablo.staffname, $staff_tablo.staffavatar
        FROM $staff_tablo
        WHERE $staff_tablo.inactive=0 AND $staff_tablo.id=$loggedinuserid";
		return $this->db->query( $sql )->row();
	}

	function set_session() {
		$this->session->set_userdata( array(
			'usr_id' => $this->details->id,
			'staffname' => $this->details->staffname,
			'email' => $this->details->email,
			'root' => $this->details->root,
			'language' => $this->details->language,
			'admin' => $this->details->admin,
			'staffmember' => $this->details->staffmember,
			'staffavatar' => $this->details->staffavatar,
			'other' => $this->details->other,
			'LoginOK' => true
		) );
	}

	function set_two_factor_authentication_session() {
		$this->session->set_userdata( array(
			'2FAVerify' => true
		) );
	}

	function if_admin() {
		if ( !$this->session->userdata( 'admin' ) ) {
			return 'display:none';
		}
	}

	function usr_id() {
		$loggedinuserid = $this->session->usr_id;
		return $loggedinuserid ? $loggedinuserid : false;
	}
}