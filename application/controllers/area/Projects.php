<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Projects extends AREA_Controller {


	function index() {
		$data[ 'title' ] = lang( 'areatitleprojects' );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'area/projects/index', $data );
	}

	function project( $id ) {
		$permission = $this->Projects_Model->check_project_permission( $id, $_SESSION[ 'contact_id' ] );
		if ($permission) {
			$project = $this->Projects_Model->get_projects( $id );
			$data[ 'title' ] = $project[ 'name' ];
			$data[ 'projects' ] = $project;
			$this->load->view( 'area/projects/project', $data );
		} else {
			redirect('area/projects');
		}
	}

	function addnote() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$description = $_POST[ 'description' ];
			$hasError = false;
			$data['message'] = '';
			if ($description == '' || !$description) {
				$hasError = true;
				$data['message'] = lang('invalidmessage') .' '. lang('note');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'relation_type' => $_POST[ 'relation_type' ],
					'relation' => $_POST[ 'relation' ],
					'description' => $_POST[ 'description' ],
					'customer_id' => $_SESSION[ 'customer' ],
					'created' => date( 'Y-m-d H:i:s' ),
				);
				$this->db->insert( 'notes', $params );
				$data[ 'insert_id' ] = $this->db->insert_id();
					$template = $this->Emails_Model->get_template('project', 'new_note_to_members_by_customer');
					if ($template['status'] == 1) {
						$project = $this->Projects_Model->get_projects( $_POST[ 'relation' ] );
						$project_url = '' . base_url( 'projects/project/' . $_POST[ 'relation' ] . '' ) . '';
						switch ( $project[ 'status' ] ) {
							case '1':
								$status_project = lang( 'notstarted' );
								break;
							case '2':
								$status_project = lang( 'started' );
								break;
							case '3':
								$status_project = lang( 'percentage' );
								break;
							case '4':
								$status_project = lang( 'cancelled' );
								break;
							case '5':
								$status_project = lang( 'complete' );
								break;
						};
						if ( $project[ 'namesurname' ] ) {
							$customer = $project[ 'namesurname' ];
						} else {
							$customer = $project[ 'customercompany' ];
						}
						$message_vars = array(
							'{customer}' => $customer,
							'{project_name}' => $project[ 'name' ],
							'{project_start_date}' => $project[ 'start_date' ],
							'{project_end_date}' => $project[ 'deadline' ],
							'{project_value}' => $project[ 'projectvalue' ],
							'{project_tax}' => $project[ 'tax' ],
							'{note}' => $_POST[ 'description' ],
							'{loggedin_staff}' => $_SESSION[ 'name' ],
							'{project_url}' => $project_url,
							'{project_status}' => $status_project,
							'{name}' => $_SESSION[ 'name' ],
							'{email_signature}' => $_SESSION['email'],
						);
						$email = '';
						$project_admin = $this->Projects_Model->get_project_admin($_POST[ 'relation' ]);
						if ($project_admin['adminemail']) {
							$email = $project_admin['adminemail'];
						}
						$members = $this->Projects_Model->get_members($_POST[ 'relation' ]);
						$recipients = array();
						foreach ($members as $member) {
							$recipients[] = $member['memberemail'];
						}
						$recipients[] = $email;
						if (count($recipients) > 0) {
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);
							$param = array(
								'from_name' => $template['from_name'],
								'email' => serialize($recipients),
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" )
							);
							$this->db->insert( 'email_queue', $param );
						}
					}
				$data['success'] = true;
				$data['message'] = lang('note'). ' ' .lang('addmessage');
				echo json_encode( $data );
			}
		}
	}

	function add_file( $id ) { 
		if ( isset( $id ) ) {
			if ( isset( $_POST ) ) {
				if (!is_dir('uploads/files/projects/'.$id)) { 
					mkdir('./uploads/files/projects/'.$id, 0777, true);
				}
				$config[ 'upload_path' ] = './uploads/files/projects/'.$id.'';
				$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|txt|csv|ppt|opt';
				$config['max_size'] = '9000';
				$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["file_name"]['name']));
				$config['file_name'] = $new_name;
				$this->load->library( 'upload', $config );
				$this->upload->do_upload( 'file_name' );
				$data_upload_files = $this->upload->data();
				$image_data = $this->upload->data();
				if (is_file('./uploads/files/projects/'.$id.'/'.$image_data[ 'file_name' ])) {
					$params = array(
						'relation_type' => 'project',
						'relation' => $id,
						'file_name' => $image_data[ 'file_name' ],
						'created' => date( " Y.m.d H:i:s " ),
						'is_old' => '0'
					);
					$this->db->insert( 'files', $params );
				}
				$template = $this->Emails_Model->get_template('project', 'new_file_uploaded_by_customer');
				if ($template['status'] == 1) {
					$project = $this->Projects_Model->get_projects( $id );
						$project_url = '' . base_url( 'projects/project/' . $id . '' ) . '';
						switch ( $project[ 'status' ] ) {
							case '1':
								$status_project = lang( 'notstarted' );
								break;
							case '2':
								$status_project = lang( 'started' );
								break;
							case '3':
								$status_project = lang( 'percentage' );
								break;
							case '4':
								$status_project = lang( 'cancelled' );
								break;
							case '5':
								$status_project = lang( 'complete' );
								break;
						};
						if ( $project[ 'namesurname' ] ) {
							$customer = $project[ 'namesurname' ];
						} else {
							$customer = $project[ 'customercompany' ];
						}
						if ($template['attachment'] == 1) {
							$attachment = base_url('uploads/files/projects/'.$id.'/'.$image_data[ 'file_name' ]);
						}
						$message_vars = array(
							'{customer}' => $customer,
							'{project_name}' => $project[ 'name' ],
							'{project_start_date}' => $project[ 'start_date' ],
							'{project_end_date}' => $project[ 'deadline' ],
							'{project_value}' => $project[ 'projectvalue' ],
							'{project_tax}' => $project[ 'tax' ],
							'{note}' => $_POST[ 'description' ],
							'{loggedin_staff}' => $_SESSION[ 'name' ],
							'{project_url}' => $project_url,
							'{project_status}' => $status_project,
							'{name}' => $_SESSION[ 'name' ],
							'{email_signature}' => $_SESSION['email'],
						);
						$email = '';
						$project_admin = $this->Projects_Model->get_project_admin($id);
						if ($project_admin['adminemail']) {
							$email = $project_admin['adminemail'];
						}
						$members = $this->Projects_Model->get_members($id);
						$recipients = array();
						foreach ($members as $member) {
							$recipients[] = $member['memberemail'];
						}
						$recipients[] = $email;
						if (count($recipients) > 0) {
							$subject = strtr($template['subject'], $message_vars);
							$message = strtr($template['message'], $message_vars);
							$param = array(
								'from_name' => $template['from_name'],
								'email' => serialize($recipients),
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" ),
								'attachments' => $attachment?$attachment:''
							);
							$this->db->insert( 'email_queue', $param );
						}
				}
				redirect( 'area/projects/project/' . $id . '' );
			}
		}
	}

	function download_file($id) {
		if (isset($id)) {
			$fileData = $this->Expenses_Model->get_file( $id );
			if ($fileData['is_old'] == '1') {
				if (is_file('./uploads/files/' . $fileData['file_name'])) {
		    		$this->load->helper('file');
		    		$this->load->helper('download');
		    		$data = file_get_contents('./uploads/files/' . $fileData['file_name']);
		    		force_download($fileData['file_name'], $data);
		    	} else {
		    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
		    		redirect('projects/project/'.$fileData['relation']);
		    	}
			} else {
				if (is_file('./uploads/files/projects/'.$fileData['relation'].'/' . $fileData['file_name'])) {
		    		$this->load->helper('file');
		    		$this->load->helper('download');
		    		$data = file_get_contents('./uploads/files/projects/'.$fileData['relation'].'/' . $fileData['file_name']);
		    		force_download($fileData['file_name'], $data);
		    	} else {
		    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
		    		redirect('projects/project/'.$fileData['relation']);
		    	}
		    }
				
		}
	}
}