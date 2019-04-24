<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Leads extends CIUIS_Controller {

	function __construct() {
		parent::__construct();
		$path = $this->uri->segment( 1 );
		if ( !$this->Privileges_Model->has_privilege( $path ) ) {
			$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
			redirect( 'panel/' );
			die;
		}
	}

	function index() {
		$data[ 'title' ] = lang( 'leads' );
		$data[ 'tlh' ] = $this->db->count_all( 'leads' );
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'tcl' ] = $this->Report_Model->tcl();
		$data[ 'tll' ] = $this->Report_Model->tll();
		$data[ 'tjl' ] = $this->Report_Model->tjl();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		if ( !if_admin ) {
			$data[ 'leads' ] = $this->Leads_Model->get_all_leads_for_admin();
		} else {
			$data[ 'leads' ] = $this->Leads_Model->get_all_leads();
		};
		$this->load->view( 'leads/index', $data );
	} 

	function forms() {
		$data['title'] = lang('leadsforms');
		$this->load->view( 'inc/header', $data);
		$this->load->view( 'forms/index');
		$this->load->view( 'inc/footer');
	}

	function form($id) {
		if ($id) {
			$check = $this->Leads_Model->get_weblead($id);
			if ($check) {
				$data['formId'] = $check['id'];
				$data['token'] = $check['token'];
				$data['submit'] = $check['submit_text'];
				$data['title'] = lang('leadsforms');
				$this->load->view( 'inc/header', $data);
				$this->load->view( 'forms/form');
				$this->load->view( 'inc/footer');
			} else {
				$this->session->set_flashdata( 'ntf3', '' . lang( 'you_dont_have_permission' ) );
				redirect(base_url('leads'));
			}
		} else {
			redirect(base_url('leads'));
		}
	}

	function get_weblead($id) {
		if ($id) {
			$form = $this->Leads_Model->get_weblead($id);
			if ($form['duplicate'] == '1') {
				$duplicate = true;
			} else {
				$duplicate = false;
			}
			if ($form['notification'] == '1') {
				$notification = true;
			} else {
				$notification = false;
			}
			if ($form['status'] == '1') {
				$status = true;
			} else {
				$status = false;
			}
			$data = array(
				'name' => $form['name'],
				'assigned_id' => $form['assigned_id'],
				'status_id' => $form['lead_status'],
				'source_id' => $form['lead_source'],
				'submit_text' => $form['submit_text'],
				'success_message' => $form['success_message'],
				'data' => $form['form_data'],
				'token' => $form['token'],
				'duplicate' => $duplicate,
				'notification' => $notification,
				'status' => $status
			);
			echo json_encode($data);
		} else {
			redirect(base_url('panel'));
		}
	}

	function add_weblead_form() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$assigned_id = $this->input->post( 'assigned_id' );
			$status_id = $this->input->post( 'status_id' );
			$source_id = $this->input->post( 'source_id' );
			$submit_text = $this->input->post( 'submit_text' );
			$success_message = $this->input->post( 'success_message' );

			$hasError = false;
			if ($this->input->post('duplicate') == 'true') {
				$duplicate = '1';
			} else {
				$duplicate = '0';
			}
			if ($this->input->post( 'notification' ) == 'true') {
				$notification = '1';
			} else {
				$notification = '0';
			}
			if ($this->input->post( 'status' ) == 'true') {
				$status = '1';
			} else {
				$status = '0';
			}

			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($assigned_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('assigned');
			} else if ($status_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('status');
			} else if ($source_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('source');
			} else if ($submit_text == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('submit_text');
			} else if ($success_message == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('message_after_success');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'token' => md5(uniqid()),
					'name' => $name,
					'assigned_id' => $assigned_id,
					'lead_status' => $status_id,
					'lead_source' => $source_id,
					'form_data' => '[{"label":"Name","allowMultipleMasks":false,"showWordCount":false,"showCharCount":false,"tableView":true,"alwaysEnabled":false,"type":"textfield","key":"name","input":true,"validate":{"customMessage":"","json":"","required":true}},{"label":"E-Mail","tableView":true,"alwaysEnabled":false,"type":"email","key":"eMail","input":true,"validate":{"required":true,"customMessage":"","json":""}},{"label":"Phone","mask":false,"tableView":true,"alwaysEnabled":false,"type":"number","key":"phone","input":true,"validate":{"required":true,"customMessage":"","json":""}},{"label":"Submit","state":"","shortcut":"","mask":false,"tableView":true,"alwaysEnabled":false,"type":"button","key":"submit","input":true}]',
					'submit_text' => $submit_text,
					'success_message' => $success_message,
					'duplicate' => $duplicate,
					'notification' => $notification,
					'created' => date('Y-m-d H:i:s'),
					'status' => $status,
				);
				$weblead_id = $this->Leads_Model->create_weblead_form( $params );
				if ($weblead_id) {
					$data['success'] = true;
					$data['message'] = lang('weblead').' '.lang('createmessage');
					$data['id'] = $weblead_id;
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function save_weblead_form($id) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$assigned_id = $this->input->post( 'assigned_id' );
			$status_id = $this->input->post( 'status_id' );
			$source_id = $this->input->post( 'source_id' );
			$submit_text = $this->input->post( 'submit_text' );
			$success_message = $this->input->post( 'success_message' );

			$hasError = false;
			if ($this->input->post('duplicate') == 'true') {
				$duplicate = '1';
			} else {
				$duplicate = '0';
			}
			if ($this->input->post( 'notification' ) == 'true') {
				$notification = '1';
			} else {
				$notification = '0';
			}
			if ($this->input->post( 'status' ) == 'true') {
				$status = '1';
			} else {
				$status = '0';
			}

			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($assigned_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('assigned');
			} else if ($status_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('status');
			} else if ($source_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('source');
			} else if ($submit_text == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('submit_text');
			} else if ($success_message == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('message_after_success');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'name' => $name,
					'assigned_id' => $assigned_id,
					'lead_status' => $status_id,
					'lead_source' => $source_id,
					'submit_text' => $submit_text,
					'success_message' => $success_message,
					'duplicate' => $duplicate,
					'notification' => $notification,
					'status' => $status,
				);
				$weblead_id = $this->Leads_Model->update_weblead_form($id, $params);
				if ($weblead_id) {
					$data['success'] = true;
					$data['message'] = lang('weblead').' '.lang('updatemessage');
					$data['id'] = $weblead_id;
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function save_weblead_components($id) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$weblead_id = $this->db->where('id', $id)->update('webleads', array('form_data' => $this->input->post('components', FALSE)));
			if ($weblead_id) {
				$data['success'] = true;
				$data['message'] = lang('weblead').' '.lang('updatemessage');
				echo json_encode($data);
			} else {
				$data['success'] = false;
				$data['message'] = lang('errormessage');
				echo json_encode($data);
			}
		}
	}

	function change_weblead_status($id) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			if ($this->input->post( 'status' ) == 'true') {
				$status = '1';
			} else {
				$status = '0';
			}
			$weblead_id = $this->db->where( 'id', $id )->update( 'webleads', array( 'status' => $status ) );
			if ($weblead_id) {
				$data['success'] = true;
				$data['message'] = lang('statuschanged');
				echo json_encode($data);
			} else {
				$data['success'] = false;
				$data['message'] = lang('errormessage');
				echo json_encode($data);
			}
		}
	}

	function webleads() {
		if ( !if_admin ) {
			$leads = $this->Leads_Model->get_all_web_leads_for_admin();
		} else {
			$leads = $this->Leads_Model->get_all_web_leads();
		}
		$data_leads = array();
		foreach ( $leads as $lead ) {
			if ($lead['formstatus'] == '1') {
				$status = true;
			} else {
				$status = false;
			}
			$data_leads[] = array(
				'id' => $lead[ 'id' ],
				'total_submissions' => $this->db->get_where('leads', array('weblead' => $lead[ 'id' ]))->num_rows(),
				'name' => $lead[ 'name' ],
				'statusname' => $lead[ 'statusname' ],
				'sourcename' => $lead[ 'sourcename' ],
				'assigned' => $lead[ 'leadassigned' ],
				'avatar' => $lead[ 'assignedavatar' ],
				'createddate' => $lead[ 'created' ],
				'status' => $status
			);
		};
		echo json_encode($data_leads);
	}

	function delete_web_form( $id ) {
		$form = $this->Leads_Model->get_weblead($id);
		if ( isset( $form[ 'id' ] ) ) {
			$this->Leads_Model->delete_web_form( $id );
			$data['success'] = true;
			$data['message'] = lang('weblead') . ' ' .lang('deletemessage');
			echo json_encode($data);
		} else {
			$data['success'] = false;
			$data['message'] = lang('web_lead_not_found');
			echo json_encode($data);
		}
	}

	function create() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$title = $this->input->post('title');
			$date_contacted = $this->input->post('date_contacted');
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$country_id = $this->input->post('country_id');
			$assigned = $this->input->post('assigned');
			$status = $this->input->post('status');
			$source = $this->input->post('source');
			$description = $this->input->post('description');
			$data['message'] = '';
			$hasError = false;
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($assigned == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('assigned'). ' ' .lang('staff');
			} else if ($status == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('status');
			} else if ($source == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('source');
			} else if ($email == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('email');
			} else if ($country_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('country');
			} else if ($description == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('description');
			} else if ($date_contacted == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('date_contacted');
			}

			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'created' => date( 'Y-m-d H:i:s' ),
					'date_contacted' => $this->input->post( 'date_contacted' ),
					'type' => $this->input->post( 'type' ),
					'name' => $this->input->post( 'name' ),
					'title' => $this->input->post( 'title' ),
					'company' => $this->input->post( 'company' ),
					'description' => $this->input->post( 'description' ),
					'country_id' => $this->input->post( 'country_id' ),
					'zip' => $this->input->post( 'zip' ),
					'city' => $this->input->post( 'city' ),
					'state' => $this->input->post( 'state' ),
					'address' => $this->input->post( 'address' ),
					'email' => $this->input->post( 'email' ),
					'website' => $this->input->post( 'website' ),
					'phone' => $this->input->post( 'phone' ),
					'assigned_id' => $this->input->post( 'assigned' ),
					'source' => $this->input->post( 'source' ),
					'public' => $this->input->post( 'public' ),
					'dateassigned' => date( 'Y-m-d H:i:s' ),
					'staff_id' => $this->session->userdata( 'usr_id' ),
					'status' => $this->input->post( 'status' ),
				);
				$lead_id = $this->Leads_Model->add_lead( $params );
				// Custom Field Post
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' )
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'lead', $lead_id );
				}
				// Custom Field Post
				$this->db->insert( 'tags', array(
					'relation_type' => 'lead',
					'relation' => $lead_id,
					'data' => $this->input->post( 'tags' )
				) );

				$template = $this->Emails_Model->get_template('lead', 'lead_assigned');
				if ($template['status'] == 1) {
					$lead = $this->Leads_Model->get_lead( $lead_id );
					$lead_url = '' . base_url( 'leads/lead/' . $lead_id . '' ) . '';
					$message_vars = array(
						'{lead_name}' => $this->input->post( 'name' ),
						'{lead_email}' => $this->input->post( 'email' ),
						'{lead_url}' => $lead_url,
						'{lead_assigned_staff}' => $lead['leadassigned'],
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $lead['staffemail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($lead['staffemail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$template = $this->Emails_Model->get_template('lead', 'lead_submitted');
				if ($template['status'] == 1) {
					$lead = $this->Leads_Model->get_lead( $lead_id );
					$message_vars = array(
						'{lead_name}' => $this->input->post( 'name' ),
						'{lead_email}' => $this->input->post( 'email' ),
						'{lead_assigned_staff}' => $lead['leadassigned'],
						'{email_signature}' => $template['from_name'],
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);
					$param = array(
						'from_name' => $template['from_name'],
						'email' => $lead['staffemail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($lead['staffemail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['success'] = true;
				$data['id'] = $lead_id;
				$data['message'] = lang('lead').' '. lang('createmessage');
				echo json_encode($data);
			}
		}
	}

	function update( $id ) {
		$data[ 'lead' ] = $this->Leads_Model->get_lead( $id );
		if ( isset( $data[ 'lead' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$title = $this->input->post('title');
				$name = $this->input->post('name');
				$email = $this->input->post('email');
				$country_id = $this->input->post('country_id');
				$assigned = $this->input->post('assigned_id');
				$status = $this->input->post('status');
				$source = $this->input->post('source');
				$description = $this->input->post('description');
				$data['message'] = '';
				$hasError = false;
				if ($name == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('name');
				} else if ($assigned == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('assigned'). ' ' .lang('staff');
				} else if ($status == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('status');
				} else if ($source == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('source');
				} else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('email');
				} else if ($country_id == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('country');
				} else if ($description == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('description');
				}
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$params = array(
						'created' => date( 'Y-m-d H:i:s' ),
						'date_contacted' => $this->input->post( 'date_contacted' ),
						'type' => $this->input->post( 'type' ),
						'name' => $this->input->post( 'name' ),
						'title' => $this->input->post( 'title' ),
						'company' => $this->input->post( 'company' ),
						'description' => $this->input->post( 'description' ),
						'country_id' => $this->input->post( 'country_id' ),
						'zip' => $this->input->post( 'zip' ),
						'city' => $this->input->post( 'city' ),
						'state' => $this->input->post( 'state' ),
						'address' => $this->input->post( 'address' ),
						'email' => $this->input->post( 'email' ),
						'website' => $this->input->post( 'website' ),
						'phone' => $this->input->post( 'phone' ),
						'assigned_id' => $this->input->post( 'assigned_id' ),
						'junk' => $this->input->post( 'junk' ),
						'lost' => $this->input->post( 'lost' ),
						'source' => $this->input->post( 'source' ),
						'public' => $this->input->post( 'public' ),
						'dateassigned' => date( 'Y-m-d H:i:s' ),
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'status' => $this->input->post( 'status' ),
					);
					$this->Leads_Model->update_lead( $id, $params );
					// Custom Field Post
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'lead', $id );
					}
					$data['message'] = lang('lead').' '.lang('updatemessage');
					$data['success'] = true;
					echo json_encode($data);
				}
			} else {
				redirect( 'leads/index' );
			}
		} else
			show_error( 'The lead you are trying to update does not exist.' );
	}

	function lead( $id ) {
		$lead = $this->Leads_Model->get_lead( $id );
		$data[ 'title' ] = $lead[ 'leadname' ];
		$data[ 'lead' ] = $this->Leads_Model->get_lead( $id );
		$this->load->view( 'leads/lead', $data );
	}

	function convert( $id ) {
		$lead = $this->Leads_Model->get_lead( $id );
		$settings = $this->Settings_Model->get_settings_ciuis();
		if ( $lead[ 'dateconverted' ] != null ) {
			echo 'false';
		} else {
			$params = array(
				'staff_id' => $lead[ 'staff_id' ],
				'company' => $lead[ 'company' ],
				'type' => $lead[ 'type' ],
				'namesurname' => $lead[ 'company' ],
				'created' => date( 'Y-m-d H:i:s' ),
				'address' => $lead[ 'address' ],
				'zipcode' => $lead[ 'zip' ],
				'country_id' => $lead[ 'country_id' ],
				'state' => $lead[ 'state' ],
				'city' => $lead[ 'city' ],
				'phone' => $lead[ 'leadphone' ],
				'email' => $lead[ 'leadmail' ],
				'web' => $lead[ 'website' ],
			);
			$this->db->insert( 'customers', $params );
			$customer = $this->db->insert_id();
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' ),
				'detail' => ( '' . $message = sprintf( lang( 'leadconvert' ), $this->session->staffname, $lead[ 'company' ] ) . '' ),
				'staff_id' => $this->session->usr_id,
				'customer_id' => $customer,
			) );
			$response = $this->db->where( 'id', $id )->update( 'leads', array( 'status' => $settings[ 'converted_lead_status_id' ], 'dateconverted' => date( 'Y-m-d H:i:s' ) ) );
			$response = $this->db->where( 'relation', $id, 'relation_type', 'lead' )->update( 'proposals', array( 'relation' => $customer, 'relation_type' => 'customer' ) );
			echo $customer;
		}
	}

	function add_status() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
			);
			$status = $this->Leads_Model->add_status( $params );
			echo $status;
		} else {
			redirect( 'leads/index' );
		}
	}

	function update_status( $id ) {
		$data[ 'statuses' ] = $this->Leads_Model->get_status( $id );
		if ( isset( $data[ 'statuses' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
					'color' => $this->input->post( 'color' ),
				);
				$this->Leads_Model->update_status( $id, $params );
			}
		}
	}

	function remove_status( $id ) {
		$lead = $this->Leads_Model->get_status( $id );
		// check if the expenses exists before trying to delete it
		if ( isset( $lead[ 'id' ] ) ) {
			$this->Leads_Model->delete_status( $id );
		}
	}

	function add_source() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
			);
			$source = $this->Leads_Model->add_source( $params );
			echo $source;
		} else {
			redirect( 'leads/index' );
		}
	}

	function update_source( $id ) {
		$data[ 'sources' ] = $this->Leads_Model->get_source( $id );
		if ( isset( $data[ 'sources' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
				);
				$this->Leads_Model->update_source( $id, $params );
			}
		}
	}

	function remove_source( $id ) {
		$lead = $this->Leads_Model->get_source( $id );
		// check if the expenses exists before trying to delete it
		if ( isset( $lead[ 'id' ] ) ) {
			$this->Leads_Model->delete_source( $id );
		}
	}

	function move_lead() {
		$lead_id = $_POST[ 'lead_id' ];
		$status_id = $_POST[ 'status_id' ];
		$response = $this->db->where( 'id', $lead_id )->update( 'leads', array( 'status' => $status_id ) );
		$lead = $this->Leads_Model->get_lead( $lead_id );
		$data_lead = array(
			'id' => $lead[ 'id' ],
			'name' => $lead[ 'leadname' ],
			'company' => $lead[ 'company' ],
			'phone' => $lead[ 'leadphone' ],
			'color' => $lead[ 'color' ],
			'status' => $lead[ 'status' ],
			'statusname' => $lead[ 'statusname' ],
			'source' => $lead[ 'source' ],
			'sourcename' => $lead[ 'sourcename' ],
			'assigned' => $lead[ 'leadassigned' ],
			'avatar' => $lead[ 'assignedavatar' ],
			'staff' => $lead[ 'staff_id' ],
			'createddate' => $lead[ 'created' ],
			'' . lang( 'filterbystatus' ) . '' => $lead[ 'statusname' ],
			'' . lang( 'filterbysource' ) . '' => $lead[ 'sourcename' ],
		);
		echo json_encode( $data_lead );
	}

	function mark_as_lead( $id ) {
		if ( isset( $id ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				if ( $this->input->post( 'value' ) == 1 ) {
					$response = $this->db->where( 'id', $id )->update( 'leads', array( 'lost' => 1 ) );
				}
				if ( $this->input->post( 'value' ) == 2 ) {
					$response = $this->db->where( 'id', $id )->update( 'leads', array( 'lost' => 0 ) );
				}
				if ( $this->input->post( 'value' ) == 3 ) {
					$response = $this->db->where( 'id', $id )->update( 'leads', array( 'junk' => 1 ) );
				}
				if ( $this->input->post( 'value' ) == 4 ) {
					$response = $this->db->where( 'id', $id )->update( 'leads', array( 'junk' => 0 ) );
				}
				echo lang( 'updated' );
			} else {
				redirect( 'leads/index' );
			}
		} else
			show_error( 'The expensecategory you are trying to edit does not exist.' );
	}

	function import () {
		$this->load->library( 'import' );
		$data[ 'leads' ] = $this->Leads_Model->get_leads_for_import();
		$data[ 'error' ] = ''; //initialize image upload error array to empty
		$config[ 'upload_path' ] = './uploads/imports/';
		$config[ 'allowed_types' ] = 'csv';
		$config[ 'max_size' ] = '1000';
		$this->load->library( 'upload', $config );
		// If upload failed, display error
		if ( !$this->upload->do_upload() ) {
			$data[ 'error' ] = $this->upload->display_errors();
			$this->session->set_flashdata( 'ntf4', lang('csvimporterror') );
			redirect( 'leads/index' );
		} else {
			$file_data = $this->upload->data();
			$file_path = './uploads/imports/' . $file_data[ 'file_name' ];
			if ( $this->import->get_array( $file_path ) ) {
				$csv_array = $this->import->get_array( $file_path );
				foreach ( $csv_array as $row ) {
					$insert_data = array(
						'created' => date( 'Y-m-d H:i:s' ),
						'type' => $row[ 'type' ],
						'name' => $row[ 'name' ],
						'title' => $row[ 'title' ],
						'company' => $row[ 'company' ],
						'description' => $row[ 'description' ],
						'zip' => $row[ 'zip' ],
						'city' => $row[ 'city' ],
						'state' => $row[ 'state' ],
						'address' => $row[ 'address' ],
						'email' => $row[ 'email' ],
						'website' => $row[ 'website' ],
						'phone' => $row[ 'phone' ],
						'assigned_id' => $this->input->post( 'importassigned' ),
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'source' => $this->input->post( 'importsource' ),
						'dateassigned' => date( 'Y-m-d H:i:s' ),
						'status' => $this->input->post( 'importstatus' ),
					);
					$this->Leads_Model->insert_csv( $insert_data );
				}
				$this->session->set_flashdata( 'ntf1', lang('csvimportsuccess') );
				redirect( 'leads/index' );
				//echo "<pre>"; print_r($insert_data);
			} else
				redirect( 'leads/index' );
			$this->session->set_flashdata( 'ntf3', 'Error' );
		}
	}

	function exportdata() {
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');

		$this->db->select('leads.type, leads.name, leads.title, leads.company, leads.description, leads.email, leads.zip, leads.state, leads.address, leads.city, leads.website, leads.phone, leadssources.name as Source, leadssources.id as Source_Id');
		$this->db->join( 'leadssources', 'leads.source = leadssources.id', 'left' );
		$q = $this->db->get_where( 'leads', array( '') );
		 
		$delimiter = ",";
		$nuline    = "\r\n";
		force_download('Leads.csv', $this->dbutil->csv_from_result($q, $delimiter, $nuline));
	}

	function remove_converted( $id ) {
		$response = $this->db->delete( 'leads', array( 'status' => $id ) );
	}

	function make_converted_status( $id ) {
		$response = $this->db->where( 'settingname', 'ciuis' )->update( 'settings', array( 'converted_lead_status_id' => $id ) );
	}

	function remove( $id ) {
		$lead = $this->Leads_Model->get_lead( $id );
		// check if the expenses exists before trying to delete it
		if ( isset( $lead[ 'id' ] ) ) {
			$this->Leads_Model->delete_lead( $id );
			redirect( 'leads/index' );
		} else
			show_error( 'The expenses you are trying to delete does not exist.' );
	}
}