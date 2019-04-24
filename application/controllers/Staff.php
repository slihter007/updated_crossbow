<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Staff extends CIUIS_Controller {

	function index() {
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		} else {
			$data[ 'title' ] = lang( 'staff' );
			$data[ 'staff' ] = $this->Staff_Model->get_all_staff();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$data[ 'departments' ] = $this->Settings_Model->get_departments();
			$path = $this->uri->segment( 1 );
			if ( !$this->Privileges_Model->has_privilege( $path ) ) {
				$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
				redirect( 'panel/' );
				die;
			}
			$this->load->view( 'inc/header', $data );
			$this->load->view( 'staff/index', $data );
			$this->load->view( 'inc/footer', $data );
		}
	} 

	function create() {
			$data[ 'title' ] = 'Add Staff';
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$language = $this->input->post( 'language' );
				$staffname = $this->input->post( 'name' );
				$department_id = $this->input->post( 'department' );
				$email = $this->input->post( 'email' );
				$password = $this->input->post( 'password' );

				$hasError = false;
				$data['message'] = '';
				if ($staffname == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('name');
				} else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('email');
				} else if ($email != '') {
					if ($this->Staff_Model->isDuplicate($email) == TRUE) {
						$hasError = true;
						$data['message'] = lang('staffemailalreadyexists');
					}
				}
				if (!$hasError) {
					if ($password == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage'). ' ' .lang('password');
					} else if ($department_id == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staffdepartment');
					} else if ($language == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('language');
					}
				}

				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					switch ( $_POST[ 'admin' ] ) {
						case 'true':
							$is_Admin = 1;
							break;
						case 'false':
							$is_Admin = null;
							break;
					}
					switch ( $_POST[ 'staffmember' ] ) {
						case 'true':
							$is_Staff = 1;
							break;
						case 'false':
							$is_Staff = null;
							break;
					}
					switch ( $_POST[ 'other' ] ) {
						case 'true':
							$is_Other = 1;
							$is_Staff = 1;
							break;
						case 'false':
							$is_Other = null;
							break;
					}
					switch ( $_POST[ 'inactive' ] ) {
						case 'true':
							$is_Active = null;
							break;
						case 'false':
							$is_Active = 0;
							break;
					}
					$params = array(
						'language' => $this->input->post( 'language' ),
						'staffname' => $this->input->post( 'name' ),
						'staffavatar' => 'n-img.jpg',
						'department_id' => $this->input->post( 'department' ),
						'phone' => $this->input->post( 'phone' ),
						'address' => $this->input->post( 'address' ),
						'email' => $this->input->post( 'email' ),
						'password' => md5( $this->input->post( 'password' ) ),
						'birthday' => $this->input->post( 'birthday' ),
						'admin' => $is_Admin,
						'other' => $is_Other,
						'staffmember' => $is_Staff,
						'inactive' => $is_Active,
					);
					$staff_id = $this->Staff_Model->add_staff( $params );
					if ($staff_id) {
						if ($is_Other === 1) {
							$this->update_privilege($staff_id, 'true', '1');
							$this->update_privilege($staff_id, 'true', '3');
						}
						if ( $this->input->post( 'custom_fields' ) ) {
							$custom_fields = array(
								'custom_fields' => $this->input->post( 'custom_fields' )
							);
							$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'staff', $staff_id );
						}
						$template = $this->Emails_Model->get_template('staff', 'new_staff');
						if ($template['status'] == 1) {
							$message_vars = array(
								'{staff_email}' => $this->input->post( 'email' ),
								'{staff}' => $this->input->post( 'name' ),
								'{password}' => $this->input->post( 'password' ),
								'{name}' => $this->session->userdata('staffname'),
								'{email_signature}' => $this->session->userdata('email'),
								'login_url' => '' . base_url( 'login' ) . '' 
							);
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);

							$param = array(
								'from_name' => $template['from_name'],
								'email' => $this->input->post( 'email' ),
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" )
							);
							if ($this->input->post( 'email' )) {
								$this->db->insert( 'email_queue', $param );
							}
						}
						$data['success'] = true;
						$data['message'] = lang('staff'). ' '.lang('addmessage');
						echo json_encode($data);
					}
				}
			} else {
				$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
				$data[ 'languages' ] = $this->Settings_Model->get_languages();
				$data[ 'departments' ] = $this->Settings_Model->get_departments();
				$this->load->view( 'staff/add', $data );
			}
	}

	function update( $id ) {
		if ( isset( $id ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$language = $this->input->post( 'language' );
				$staffname = $this->input->post( 'name' );
				$department_id = $this->input->post( 'department' );
				$email = $this->input->post( 'email' );
				$password = $this->input->post( 'password' );
				$primaryEmail = $this->Staff_Model->get_staff_email($id);

				$hasError = false;
				$data['message'] = '';
				if ($staffname == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('name');
				} else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('email');
				} else if ($email != '') {
					if ($email != $primaryEmail['email']) {
						if ($this->Staff_Model->isDuplicate($email) == TRUE) {
							$hasError = true;
							$data['message'] = lang('staffemailalreadyexists');
						}
					}
				}
				if (!$hasError) {
					if ($department_id == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staffdepartment');
					} else if ($language == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('language');
					}
				}
				
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					switch ( $_POST[ 'admin' ] ) {
						case 'true':
							$is_Admin = 1;
							break;
						case 'false':
							$is_Admin = null;
							break;
					}
					switch ( $_POST[ 'staffmember' ] ) {
						case 'true':
							$is_Staff = 1;
							break;
						case 'false':
							$is_Staff = null;
							break;
					}
					switch ( $_POST[ 'inactive' ] ) {
						case 'true':
							$is_Active = null;
							break;
						case 'false':
							$is_Active = 0;
							break;
					}
					switch ( $_POST[ 'other' ] ) {
						case 'true':
							$is_Other = 1;
							$is_Staff = 1;
							break;
						case 'false':
							$is_Other = null;
							break;
					}
					$params = array(
						'language' => $this->input->post( 'language' ),
						'staffname' => $this->input->post( 'name' ),
						'department_id' => $this->input->post( 'department' ),
						'phone' => $this->input->post( 'phone' ),
						'address' => $this->input->post( 'address' ),
						'email' => $this->input->post( 'email' ),
						'birthday' => $this->input->post( 'birthday' ),
						'admin' => $is_Admin,
						'other' => $is_Other,
						'staffmember' => $is_Staff,
						'inactive' => $is_Active,
					);
					$this->Staff_Model->update_staff( $id, $params ); 
					$this->session->set_userdata(array('language' => $this->input->post('language')));
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'staff', $id );
					}
					$data['success'] = true;
					$data['message'] = lang( 'staffupdated' );
					echo json_encode($data); 
				}
			}
		}
	}

	function update_privilege( $id, $value, $privilege_id ) {
		if ( $value != 'false' ) {
			$params = array(
				'relation' => ( int )$id,
				'relation_type' => 'staff',
				'permission_id' => ( int )$privilege_id
			);
			$this->db->insert( 'privileges', $params );
			return $this->db->insert_id();
		} else {
			$response = $this->db->delete( 'privileges', array( 'relation' => $id, 'relation_type' => 'staff', 'permission_id' => $privilege_id ) );
		}

	}

	function staffmember( $id ) {
		$data[ 'title' ] = lang( 'staffdetail' );
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if (!$this->isAdmin()) {
				if ($staff[ 'id' ] != $this->session->usr_id) {
					$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
					redirect('panel');
				} else {
					$data[ 'id' ] = $staff[ 'id' ];
					$this->load->view( 'inc/header', $data );
					$this->load->view( 'staff/detail', $data );
					$this->load->view( 'inc/footer', $data );
				}
			} else {
				$data[ 'id' ] = $staff[ 'id' ];
				$this->load->view( 'inc/header', $data );
				$this->load->view( 'staff/detail', $data );
				$this->load->view( 'inc/footer', $data );
			}
		} else {
			redirect( 'staff/' );
		}
	}

	function profile() {
		$id = $this->session->userdata('usr_id');
		$data[ 'title' ] = lang( 'staffdetail' );
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if (!$this->isAdmin()) {
				if ($staff[ 'id' ] != $this->session->usr_id) {
					$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
					redirect('panel');
				} else {
					$data[ 'id' ] = $staff[ 'id' ];
					$this->load->view( 'inc/header', $data );
					$this->load->view( 'staff/detail', $data );
					$this->load->view( 'inc/footer', $data );
				}
			} else {
				$data[ 'id' ] = $staff[ 'id' ];
				$this->load->view( 'inc/header', $data );
				$this->load->view( 'staff/detail', $data );
				$this->load->view( 'inc/footer', $data );
			}
		} else {
			redirect( 'staff/' );
		}
	}

	function isAdmin() {
		$id = $this->session->usr_id;
		$this->db->select( '*');
		$rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
	}

	function change_avatar( $id ) {
		if ( isset( $id ) ) {
			if ( isset( $_POST ) ) {
				$config[ 'upload_path' ] = './uploads/images/';
				$config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
				$this->load->library( 'upload', $config );
				$this->upload->do_upload( 'file_name' );
				$data_upload_files = $this->upload->data();
				$image_data = $this->upload->data();
				$params = array(
					'staffavatar' => $image_data[ 'file_name' ],
				);
				$response = $this->Staff_Model->update_staff( $id, $params );
				redirect( 'staff/staffmember/' . $id . '' );
			}
		}
	}

	function remove( $id ) {
		$staff = $this->Staff_Model->get_staff( $id );
		// check if the staff exists before trying to delete it
		if ( isset( $staff[ 'id' ] ) ) {
			$this->Staff_Model->delete_staff( $id );
			redirect( 'staff/index' );
		} else
			show_error( 'The staff you are trying to delete does not exist.' );
	}

	function add_department() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
			);
			$department = $this->Settings_Model->add_department( $params );
			echo $department;
		}
	}

	function update_department( $id ) {
		$departments = $this->Settings_Model->get_department( $id );
		if ( isset( $departments[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
				);
				$this->session->set_flashdata( 'ntf1', '<span><b>' . lang( 'departmentupdated' ) . '</b></span>' );
				$this->Settings_Model->update_department( $id, $params );
			}
		}
	}


	function update_workplan( $id ) {
		$workplan = $this->Staff_Model->get_work_plan( $id );
		if ( isset( $workplan[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$response = $this->db->where( 'id', $workplan[ 'id' ] )->update( 'staff_work_plan', array( 'work_plan' => $this->input->post( 'work_plan' ) ) );
			}
			echo 'Staff work plan updated.';
		}
	}

	function remove_department( $id ) {
		$departments = $this->Settings_Model->get_department( $id );
		if ( isset( $departments[ 'id' ] ) ) {
			$this->Settings_Model->delete_department( $id );
			redirect( 'staff/index' );
		}
	}

	function appointment_availability( $id, $value ) {
		if ( $value === 'true' ) {
			$availability = 1;
		} else {
			$availability = 0;
		}
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'staff', array( 'appointment_availability' => $availability ) );
		};
	}

	function update_google_calendar( $id ) {
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'google_calendar_id' => $this->input->post( 'google_calendar_id' ),
					'google_calendar_api_key' => $this->input->post( 'google_calendar_api_key' ),
					'google_calendar_enable' => $this->input->post( 'google_calendar_enable' ),
				);
				$this->Staff_Model->update_staff( $id, $params );
				$notification = array(
					'color' => 'color success',
					'message' => lang( 'google_calendar_settings_updated' )
				);
				echo json_encode( $notification );
			} else {
				$notification = array(
					'color' => 'color danger',
					'message' => lang( 'google_calendar_settings_not_updated' )
				);
				echo json_encode( $notification );
			}
		} else
			show_error( 'The staff you are trying to update google calendar settings does not exist.' );
	}

	function changestaffpassword() {
		$id = $this->session->userdata('usr_id');
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$password = $this->input->post('password');
				$new_password = $this->input->post('new_password');
				$c_new_password = $this->input->post('c_new_password');
				$hasError = false;
				if ($password == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('old'). ' ' .lang('password');
				} else if ($new_password == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('new'). ' ' .lang('password');
				} else if (strlen($new_password) < 6) {
					$hasError = true;
					$data['message'] = lang('password_length_error');
				} else if ($c_new_password != $new_password) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('confirm'). ' ' .lang('new'). ' ' .lang('password');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					if ($this->Staff_Model->validate_user_password($id, $password)) {
						$params = array(
							'password' => md5($new_password),
						);
						$this->Staff_Model->update_staff( $id, $params );

						$template = $this->Emails_Model->get_template('staff', 'password_reset');
						$message_vars = array(
							'{staff_email}' => $staff['email'],
							'{staffname}' => $staff['staffname'],
							'{email_signature}' => $template['from_name'],
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);

						$param = array(
							'from_name' => $template['from_name'],
							'email' => $staff['email'],
							'subject' => $subject,
							'message' => $message,
							'created' => date( "Y.m.d H:i:s" ),
							'status' => 0
						);
						if ($staff['email']) {
							$this->db->insert( 'email_queue', $param );
							$this->load->library('mail');
							$this->mail->send_email($staff['email'], $template['from_name'], $subject, $message);
						}
						$data['success'] = true;
						$data['message'] = lang('password'). ' ' .lang('updatemessage');
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['message'] = lang('incorrect'). ' ' .lang('old'). ' ' .lang('password');
						echo json_encode($data);
					}
				}
			} else {
				$data[ 'staff' ] = $this->Staff_Model->get_staff( $id );
			}
		} else
			show_error( 'The staff you are trying to update password does not exist.' );
	}

	function changestaffpassword_admin($id) {
		$staff = $this->Staff_Model->get_staff( $id );
		if ( isset( $staff[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$new_password = $this->input->post('new_password');
				$c_new_password = $this->input->post('c_new_password');
				$hasError = false;
				if ($new_password == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('new'). ' ' .lang('password');
				} else if (strlen($new_password) < 6) {
					$hasError = true;
					$data['message'] = lang('password_length_error');
				} else if ($c_new_password != $new_password) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('confirm'). ' ' .lang('new'). ' ' .lang('password');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
						$params = array(
							'password' => md5($new_password),
						);
						$this->Staff_Model->update_staff( $id, $params );

						$template = $this->Emails_Model->get_template('staff', 'password_reset');
						$message_vars = array(
							'{staff_email}' => $staff['email'],
							'{staffname}' => $staff['staffname'],
							'{email_signature}' => $template['from_name'],
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);

						$param = array(
							'from_name' => $template['from_name'],
							'email' => $staff['email'],
							'subject' => $subject,
							'message' => $message,
							'created' => date( "Y.m.d H:i:s" ),
							'status' => 0
						);
						if ($staff['email']) {
							$this->db->insert( 'email_queue', $param );
							$this->load->library('mail');
							$this->mail->send_email($staff['email'], $template['from_name'], $subject, $message);
						}
						$data['success'] = true;
						$data['message'] = lang('password'). ' ' .lang('updatemessage');
						echo json_encode($data);
				}
			} else {
				$data[ 'staff' ] = $this->Staff_Model->get_staff( $id );
			}
		} else
			show_error( 'The staff you are trying to update password does not exist.' );
	}
}