<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Projects extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'projects' );
		$data[ 'projects' ] = $this->Projects_Model->get_all_projects();
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'projects/index', $data );
	}

	function project( $id ) {
		$project = $this->Projects_Model->get_projects( $id );
		$data[ 'title' ] = $project[ 'name' ];
		$data[ 'projects' ] = $this->Projects_Model->get_projects( $id );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'projects/project', $data );
	}

	function create() { 
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$customer_id = $this->input->post('customer');
			$description = $this->input->post( 'description' );
			$tax = $this->input->post( 'tax' );
			$value = $this->input->post( 'value' );
			$start_date = $this->input->post( 'start' );
			$end_date = $this->input->post( 'deadline' );
			$template = $this->input->post( 'template' );
			if ($template == 'false' || $template == '0' || !$template) {
				$template = 0;
			} else if ($template == 'true' || $template == '1') {
				$template = 1;
				$customer_id = 0;
			}
			$hasError = false;
			$data['message'] = '';
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($customer_id == '' && $template == false) {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
			} else if ($start_date == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('startdate');
			} else if ($end_date == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('deadline');
			} else if (strtotime($end_date) < strtotime($start_date)) {
				$hasError = true;
				$data['message'] = lang('startdate').' '.lang('date_error'). ' ' .lang('deadline');
			} else if ($value == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('projectcost');
			} else if ($tax == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('tax');
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
					'name' => $name,
					'description' => $this->input->post( 'description', true ),
					'customer_id' => $customer_id,
					'projectvalue' => $value,
					'tax' => $tax,
					'start_date' => $start_date,
					'deadline' => $end_date,
					'staff_id' => $this->session->userdata( 'usr_id' ),
					'status_id' => 1,
					'template' => $template,
					'created' => date( 'Y-m-d H:i:s' ), 
				);

				$this->db->insert( 'projects', $params );
				$project_id = $this->db->insert_id();
				// Custom Field Post
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' )
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'project', $project_id );
				}
				$loggedinuserid = $this->session->usr_id;
				$staffname = $this->session->staffname;
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $staffname . ' created new project' ),
					'staff_id' => $loggedinuserid,
					'project_id' => $project_id,
					'customer_id' => $customer_id
				));
				
				$template = $this->Emails_Model->get_template('project', 'project_notification');
				if ($template['status'] == 1) {
					$project = $this->Projects_Model->get_projects( $project_id );
					$project_url = '' . base_url( 'area/projects/project/' . $project_id . '' ) . '';
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
						'{project_name}' => $name,
						'{project_start_date}' => $_POST[ 'start' ],
						'{project_end_date}' => $_POST[ 'deadline' ],
						'{project_value}' => $value,
						'{project_tax}' => $tax,
						'{loggedin_staff}' => $this->session->userdata('staffname'),
						'{project_url}' => $project_url,
						'{project_status}' => $status_project,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
						'{project_description}' => $project['description']
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $project['customeremail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($project['customeremail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['success'] = true;
				$data['message'] = lang('project'). ' ' .lang('createmessage');
				$data['id'] = $project_id;
				echo json_encode($data);
			}
		}
	}

	function update( $id ) {
		$data[ 'project' ] = $this->Projects_Model->get_projects( $id );
		if ( isset( $data[ 'project' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$name = $this->input->post( 'name' );
				$customer_id = $this->input->post('customer');
				$description = $this->input->post( 'description' );
				$tax = $this->input->post( 'tax' );
				$value = $this->input->post( 'value' );
				$start_date = $this->input->post( 'start' );
				$end_date = $this->input->post( 'deadline' );
				$template = $this->input->post( 'template' );
				if ($template == 'false' || $template == '0' || !$template) {
					$template = 0;
				} else if ($template == 'true' || $template == '1') {
					$template = 1;
					$customer_id = 0;
				}
				$hasError = false;
				$data['message'] = '';
				if ($name == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('name');
				} else if ($customer_id == '' && $template == 0) {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('choisecustomer');
				} else if ($start_date == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('startdate');
				} else if ($end_date == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('deadline');
				} else if (strtotime($end_date) < strtotime($start_date)) {
					$hasError = true;
					$data['message'] = lang('startdate').' '.lang('date_error'). ' ' .lang('deadline');
				} else if ($value == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('projectcost');
				} else if ($tax == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('tax');
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
						'name' => $name,
						'description' => $this->input->post( 'description', true ),
						'customer_id' => $customer_id,
						'projectvalue' => $value,
						'tax' => $tax,
						'start_date' => $_POST[ 'start' ],
						'deadline' => $_POST[ 'deadline' ],
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'status_id' => 1,
						'created' => date( 'Y-m-d H:i:s' ),
					);
					$this->Projects_Model->update( $id, $params );
					// Custom Field Post
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'project', $id );
					}
					$data['success'] = true;
					$data['message'] = lang('project'). ' ' .lang('updatemessage');
					echo json_encode($data);
				}
			} else {
				$this->load->view( 'projects/index', $data );
			}
		} else
			show_error( 'The task you are trying to edit does not exist.' );
	}

	function createticket($id) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$contact_id = $this->input->post( 'contact' );
			$customer_id = $this->input->post( 'customer' );
			$department_id = $this->input->post( 'department' );
			$subject = $this->input->post( 'subject' );
			$message = $this->input->post( 'message' );
			$priority = $this->input->post( 'priority' );

			$hasError = false;
			$data['message'] = '';
			if ($subject == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('subject');
			} else if ($customer_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
			} else if ($contact_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('contact');
			} else if ($department_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('department');
			} else if ($priority == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('priority');
			} else if ($message == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('message');
			}

			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'contact_id' => $this->input->post( 'contact' ),
					'customer_id' => $this->input->post( 'customer' ),
					'department_id' => $this->input->post( 'department' ),
					'priority' => $this->input->post( 'priority' ),
					'status_id' => 1,
					'subject' => $this->input->post( 'subject' ),
					'message' => $this->input->post( 'message' ),
					'relation_id' => $id,
					'relation' => 'project',
					'date' => date( " Y.m.d H:i:s " ),
				);
				$tickets_id = $this->Tickets_Model->add_tickets( $params );

				$template = $this->Emails_Model->get_template('ticket', 'new_ticket');
				if ($template['status'] == 1) {
					$ticket = $this->Tickets_Model->get_tickets( $tickets_id );
					if ( $ticket[ 'type' ] == 0 ) {
						$customer = $ticket[ 'company' ];
					} else {
						$customer = $ticket[ 'namesurname' ];
					} 

					switch ( $ticket[ 'priority' ] ) {
						case '1':
							$priority = lang( 'low' );
							break;
						case '2':
							$priority = lang( 'medium' );
							break;
						case '3':
							$priority = lang( 'high' );
							break;
					};

					$message_vars = array(
						'{customer}' => $customer,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
						'{ticket_subject}' => $this->input->post( 'subject' ),
						'{ticket_message}' => $this->input->post( 'message' ),
						'{ticket_priority}' => $priority,
						'{ticket_department}' => $ticket['department'],
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);
					$param = array(
						'from_name' => $template['from_name'],
						'email' => $ticket['customeremail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($ticket['customeremail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['success'] = true;
				$data['message'] = lang('ticket'). ' ' .lang('createmessage');
				echo json_encode($data);
			}
		} 
	}

	function ticket_markas() { 
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$response = $this->db->where( 'id', $_POST[ 'ticket_id' ] )->update( 'tickets', array( 'status_id' => $_POST[ 'status_id' ] ) );
		}
	}

	function tickets( $id ) {
		$tickets = $this->Projects_Model->get_all_tickets($id);
		echo json_encode($tickets);
	}

	function remove_ticket( $id ) {
		$tickets = $this->Projects_Model->get_tickets( $id );
		if ( isset( $tickets[ 'id' ] ) ) {
			if ($this->Projects_Model->delete_tickets( $id )) {
				echo lang('ticket'). ' ' .lang('deletemessage');
			}
		} else
			show_error( 'Eror' );
	}

	function copyProject( $id ) { 
		$project = $this->Projects_Model->get_projects( $id );
		$params = array(
			'customer_id' => $this->input->post( 'customer_id' ),
			'name' => $project['name'].'-Copy',
			'staff_id' => $project['staff_id'],
			'status_id' => 1,
			'created' => date( 'Y-m-d H:i:s' ),
			'projectvalue' => $project['projectvalue'],
			'tax' => $project['tax'],
			'start_date' => $this->input->post( 'startdate' ),
			'deadline' => $this->input->post( 'enddate' ),
			'description' => $project['description'],
			'template' => 0
		);
		$this->db->insert( 'projects', $params );
		$projectId = $this->db->insert_id();
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $staffname . lang('created_a_new_project') ),
			'staff_id' => $loggedinuserid,
			'project_id' => $projectId,
		));

		// Items List to be copied:
		$isExpenses = $this->input->post('expenses');
		$isServices = $this->input->post('services');
		$isMilestones = $this->input->post('milestones');
		$isTasks = $this->input->post('tasks');
		$isPeoples = $this->input->post('peoples');
		$isFiles = $this->input->post('files');
		$isNotes = $this->input->post('notes');

		if ($isServices == 'true') {
			$services = $this->Projects_Model->get_project_services($id);
			$this->Projects_Model->copy_services($services, $projectId);
		}
		if ($isExpenses == 'true') {
			$expenses = $this->Expenses_Model->get_all_expenses_by_relation( 'project', $id );
			$this->Projects_Model->copy_expenses($expenses, $projectId);
		}
		if ($isMilestones == 'true') {
			$milestones = $this->Projects_Model->get_all_project_milestones( $id );
			$this->Projects_Model->copy_milestones($milestones, $projectId);
		}
		if ($isTasks == 'true') {
			$tasks = $this->Tasks_Model->get_project_tasks( $id );
			$this->Projects_Model->copy_tasks($tasks, $projectId);
		}
		if ($isPeoples == 'true') {
			$members = $this->Projects_Model->get_members( $id );
			$this->Projects_Model->copy_members($members, $projectId);
		}
		if ($isFiles == 'true') {
			$files = $this->Projects_Model->get_project_files( $id );
			$this->Projects_Model->copy_files($files, $projectId);
		}
		if ($isNotes == 'true') {
			$notes = $this->db->select( '*' )->get_where( 'notes', array( 'relation' => $id, 'relation_type' => 'project' ) )->result_array();
			$this->Projects_Model->copy_notes($notes, $projectId);
		}
		$data['success'] = true;
		$data['message'] = lang('project'). ' ' .lang('createmessage');
		$data['id'] = $projectId;
		echo json_encode($data);
	}


	function addservice() {
		if ( isset( $_POST ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$categoryid = $this->input->post( 'categoryid' );
				$productid = $this->input->post('productid');
				$servicename = $this->input->post( 'servicename' );
				$serviceprice = $this->input->post( 'serviceprice' );
				$servicetax = $this->input->post( 'servicetax' );
				$quantity = $this->input->post( 'quantity' );
				$unit = $this->input->post('unit');
				$servicedescription = $this->input->post( 'servicedescription' );
				$hasError = false;
				$data['message'] = '';
				if ($categoryid == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
				} else if ($productid == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('product');
				} else if ($servicename == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('productname');
				} else if ($serviceprice == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('price');
				} else if ($servicetax == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('tax');
				} else if ($quantity == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('quantity');
				} else if ($unit == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('unit');
				} else if ($servicedescription == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('description');
				}

				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
 				} 

 				if (!$hasError) {
 					$params = array(
 						'categoryid' => $categoryid,
 						'productid' => $productid,
 						'servicename' => $servicename,
 						'serviceprice' => $serviceprice,
 						'servicetax' => $servicetax,
 						'quantity' => $quantity,
 						'unit' => $unit,
 						'servicedescription' => $servicedescription,
 						'projectid' => $this->input->post( 'projectid' ),
 					);
 					$this->db->insert( 'projectservices', $params );
					$project = $this->db->insert_id();
					$data['success'] = true;
					$data['message'] = lang('service'). ' ' .lang('createmessage');
					echo json_encode($data);
 				}
 			}
 		}
	}

	function updateservice($id) {
		if ( isset( $_POST ) && $id ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$categoryid = $this->input->post( 'categoryid' );
				$productid = $this->input->post('productid');
				$servicename = $this->input->post( 'servicename' );
				$serviceprice = $this->input->post( 'serviceprice' );
				$servicetax = $this->input->post( 'servicetax' );
				$servicedescription = $this->input->post( 'servicedescription' );
				$quantity = $this->input->post( 'quantity' );
				$unit = $this->input->post('unit');
				$hasError = false;
				$data['message'] = '';
				if ($categoryid == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
				} else if ($productid == '') {
					$hasError = true;
					$data['message'] = lang('selectinvalidmessage'). ' ' .lang('product');
				} else if ($servicename == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('productname');
				} else if ($serviceprice == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('price');
				} else if ($servicetax == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('tax');
				} else if ($quantity == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('quantity');
				} else if ($unit == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('unit');
				} else if ($servicedescription == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('description');
				}

				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
 				} 

 				if (!$hasError) {
 					$params = array(
 						'categoryid' => $categoryid,
 						'productid' => $productid,
 						'servicename' => $servicename,
 						'serviceprice' => $serviceprice,
 						'servicetax' => $servicetax,
 						'quantity' => $quantity,
 						'unit' => $unit,
 						'servicedescription' => $servicedescription,
 						'projectid' => $this->input->post( 'projectid' ),
 					);
 					$this->db->where( 'id', $id );
 					$this->db->update( 'projectservices', $params );
					$data['success'] = true;
					$data['message'] = lang('service'). ' ' .lang('updatemessage');
					echo json_encode($data);
 				}
 			}
 		} else {
 			if ($hasError) {
 				$data['success'] = false;
 				$data['message'] = lang('errormessage');
 				echo json_encode($data);
 			}
 		}
	}

	function get_project_services( $id ) {
		$data = $this->Projects_Model->get_project_services($id);
		echo json_encode($data);
	}

	function get_products_by_category( $id ) {
		$data = $this->Projects_Model->get_products_by_category($id);
		echo json_encode($data);
	}

	function markas_complete() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'project_id' => $_POST[ 'project_id' ],
				'status_id' => '5',
			); 
			$response = $this->Projects_Model->markas_complete();
			$template = $this->Emails_Model->get_template('project', 'project_status_changed');
			if ($template['status'] == 1) {
				$project = $this->Projects_Model->get_projects( $_POST[ 'project_id' ] );
				$project_url = '' . base_url( 'area/projects/project/' . $_POST[ 'project_id' ] . '' ) . '';
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
					'{loggedin_staff}' => $this->session->userdata('staffname'),
					'{project_url}' => $project_url,
					'{project_status}' => $status_project,
					'{name}' => $this->session->userdata('staffname'),
					'{email_signature}' => $this->session->userdata('email'),
					'{project_description}' => $project['description']
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);
				$param = array(
					'from_name' => $template['from_name'],
					'email' => $project['customeremail'],
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" )
				);
				if ($project['customeremail']) {
					$this->db->insert( 'email_queue', $param );
				}
			}
			$return['success'] = true;
			$return['message'] = lang('project_complete');
			echo json_encode($return);
		}
	}

	function markas() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'project_id' => $_POST[ 'project_id' ],
				'status_id' => $_POST[ 'status_id' ],
			);
			$tickets = $this->Projects_Model->markas();

			$template = $this->Emails_Model->get_template('project', 'project_status_changed');
				if ($template['status'] == 1) {
					$project = $this->Projects_Model->get_projects( $_POST[ 'project_id' ] );
					$project_url = '' . base_url( 'area/projects/project/' . $_POST[ 'project_id' ] . '' ) . '';
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
						'{loggedin_staff}' => $this->session->userdata('staffname'),
						'{project_url}' => $project_url,
						'{project_status}' => $status_project,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
						'{project_description}' => $project['description']
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $project['customeremail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($project['customeremail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
		}
	}

	function addmilestone( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$order = $this->input->post( 'order' );
			$description = $this->input->post( 'description' );
			$hasError = false;
			$data['message'] = '';
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($this->input->post( 'duedate' ) == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('duedate');
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
					'project_id' => $id,
					'name' => $name,
					'order' => $order,
					'duedate' => _phdate( $this->input->post( 'duedate' ) ),
					'description' => $description,
					'created' => date( 'Y-m-d' ),
					'color' => 'green',
				);
				$response = $this->Projects_Model->add_milestone( $id, $params );
				$data['success'] = true;
				$data['message'] = lang('milestone'). ' ' .lang('createmessage');
				echo json_encode($data);
			}
				
		}
	}

	function updatemilestone( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$description = $this->input->post( 'description' );
			$hasError = false;
			$data['message'] = '';
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('name');
			} else if ($this->input->post( 'duedate' ) == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('duedate');
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
					'order' => $this->input->post( 'order' ),
					'name' => $this->input->post( 'name' ),
					'description' => $this->input->post( 'description' ),
					'duedate' => $this->input->post( 'duedate' ),
				);
				$response = $this->Projects_Model->update_milestone( $id, $params );
				$data['success'] = true;
				$data['message'] = lang('milestone'). ' ' .lang('createmessage');
				echo json_encode($data);
			}
		}
	}

	function removemilestone() {
		if ( isset( $_POST[ 'milestone' ] ) ) {
			$milestone = $_POST[ 'milestone' ];
			$response = $this->db->delete( 'milestones', array( 'id' => $milestone ) );
		}
	}

	function addtask( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$description =  $this->input->post( 'description' );
			$priority = $this->input->post( 'priority' );
			$assigned = $this->input->post( 'assigned' );

			$hasError = false;
			$data['message'] = '';
			if ($name == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('task'). ' ' .lang('name');
			} else if ($assigned == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('assigned');
			} else if ($priority == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('priority');
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
					'name' => $name,
					'description' => $description,
					'priority' => $priority,
					'assigned' => $assigned,
					'relation_type' => 'project',
					'relation' => $id,
					'milestone' => $this->input->post( 'milestone' ),
					'public' => $this->input->post( 'public' ),
					'billable' => $this->input->post( 'billable' ),
					'visible' => $this->input->post( 'visible' ),
					'hourly_rate' => $this->input->post( 'hourlyrate' ),
					'startdate' => $this->input->post( 'startdate' ),
					'duedate' => $this->input->post( 'duedate' ),
					'addedfrom' => $this->session->userdata( 'usr_id' ),
					'status_id' => 1,
					'created' => date( 'Y-m-d H:i:s' ),
				);
				$this->session->set_flashdata( 'ntf1', '<b>'.lang( 'task_added' ).'</b>' );
				$this->db->insert( 'tasks', $params );
				$task_id = $this->db->insert_id();
				$loggedinuserid = $this->session->usr_id;
				$staffname = $this->session->staffname;
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $staffname . lang( 'added_a_new_task' ) ),
					'staff_id' => $loggedinuserid,
					'project_id' => $id,
				) );
				$template = $this->Emails_Model->get_template('task', 'new_task_assigned');
				if ($template['status'] == 1) {
					$tasks = $this->Tasks_Model->get_task_detail( $task_id );
					$task_url = '' . base_url( 'tasks/task/' . $task_id . '' ) . '';
					switch ( $tasks[ 'status_id' ] ) {
						case '1':
							$status = lang( 'open' );
							break;
						case '2':
							$status = lang( 'inprogress' );
							break;
						case '3':
							$status = lang( 'waiting' );
							break;
						case '4':
							$status = lang( 'complete' );
							break;
						case '5':
							$status = lang( 'cancelled' );
							break;
					};
					switch ( $tasks[ 'priority' ] ) {
						case '1':
							$priority = lang( 'low' );
							break;
						case '2':
							$priority = lang( 'medium' );
							break;
						case '3':
							$priority = lang( 'high' );
							break;
						default: 
							$priority = lang( 'medium' );
							break;
					};
					$message_vars = array(
						'{task_name}' => $tasks[ 'name' ],
						'{task_startdate}' => $tasks[ 'startdate' ],
						'{task_duedate}' => $tasks[ 'duedate' ],
						'{task_priority}' => $priority,
						'{task_url}' => $task_url,
						'{staffname}' => $tasks[ 'assigner' ],
						'{task_status}' => $status,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $tasks['staffemail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($tasks['staffemail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['success'] = true;
				$data['message'] = lang('task'). ' ' .lang('createmessage');
				echo json_encode($data);
			}
		} else {
 			if ($hasError) {
 				$data['success'] = false;
 				$data['message'] = lang('errormessage');
 				echo json_encode($data);
 			}
 		}
	}

	function addmember() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$staff = $_POST[ 'staff' ];
			$projectId = $_POST[ 'project' ];
			$members = $this->Projects_Model->get_members($projectId);
			$hasError = false;
			$data['message'] = '';
			if ($staff == '' || $staff == null) {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staff');
			} else {
				foreach ($members as $member) {
					if (($member['project_id'] == $projectId) && ($member['staff_id'] == $staff)) {
						$hasError = true;
						$data['message'] = lang('same').' '.lang('staff'). ' '.lang('duplicate_message');
						continue;
					}
				}
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'staff_id' => $staff,
					'project_id' => $_POST[ 'project' ],
				);
				$this->db->insert( 'projectmembers', $params );
				$this->db->insert( 'notifications', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( lang( 'assignednewproject' ) ),
					'perres' => $this->session->staffavatar,
					'staff_id' => $_POST[ 'staff' ],
					'target' => '' . base_url( 'projects/project/' . $_POST[ 'project' ] . '' ) . ''
				) );
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $this->session->staffname . lang('added_a_member_project') ),
					'staff_id' => $this->session->usr_id,
					'project_id' => $_POST[ 'project' ],
				) );
				$member_detail = $this->Staff_Model->get_staff( $_POST[ 'staff' ] );

				$template = $this->Emails_Model->get_template('project', 'staff_added');
				if ($template['status'] == 1) {
					$project = $this->Projects_Model->get_projects( $_POST[ 'project' ] );
					$project_url = '' . base_url( 'projects/project/' . $_POST[ 'project' ] . '' ) . '';
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
						'{loggedin_staff}' => $this->session->userdata('staffname'),
						'{project_url}' => $project_url,
						'{staff}' => $member_detail['staffname'],
						'{project_status}' => $status_project,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
						'{project_description}' => $project['description']
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $member_detail['email'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($member_detail['email']) {
						$this->db->insert( 'email_queue', $param );
					}
				}

				$data['success'] = true;
				$data['message'] = lang('project'). ' ' .lang('createmessage');
				$data['member'] = $member_detail;
				echo json_encode( $data );
			}
		}
	}

	function unlinkmember( $id ) {
		if ( isset( $_POST[ 'linkid' ] ) ) {
			$linkid = $_POST[ 'linkid' ];
			$response = $this->db->where( 'id', $linkid )->delete( 'projectmembers', array( 'id' => $linkid ) );
			$data['success'] = true;
			$data['message'] = lang('staff'). ' '.lang('deletemessage');
			echo json_encode($data);
		}
	}

	function delete_file($id) {
		if (isset($id)) {
			$fileData = $this->Expenses_Model->get_file($id);
			if ($fileData) {
				$response = $this->db->where( 'id', $id )->delete( 'files', array( 'id' => $id ) );
				if ($fileData['is_old'] == '1') {
					if (is_file('./uploads/files/' . $fileData['file_name'])) {
			    		unlink('./uploads/files/' . $fileData['file_name']);
			    	}
				} else {
					if (is_file('./uploads/files/projects/'.$fileData['relation'].'/' . $fileData['file_name'])) {
			    		unlink('./uploads/files/projects/'.$fileData['relation'].'/' . $fileData['file_name']);
			    	}
				}
		    	if ($response) {
		    		$data['success'] = true;
		    		$data['message'] = lang('file'). ' '.lang('deletemessage');
		    	} else {
		    		$data['success'] = false;
		    		$data['message'] = lang('errormessage');
		    	}
		    	echo json_encode($data);
		    }
		} else {
			redirect('projects');
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
				$template = $this->Emails_Model->get_template('project', 'new_file_uploaded_to_customer');
				if ($template['status'] == 1) {
					$project = $this->Projects_Model->get_projects( $id );
					$project_url = '' . base_url( 'area/projects/project/' . $id . '' ) . '';
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
						'{loggedin_staff}' => $this->session->userdata('staffname'),
						'{project_url}' => $project_url,
						'{project_status}' => $status_project,
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
						'{project_description}' => $project['description']
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $project['customeremail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($project['customeremail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				redirect( 'projects/project/' . $id . '' );
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

	function checkpinned() {
		if ( isset( $_POST[ 'project' ] ) ) {
			$project = $_POST[ 'project' ];
			$response = $this->db->where( 'id', $project )->update( 'projects', array( 'pinned' => 1 ) );
		}
	}

	function unpinned() {
		if ( isset( $_POST[ 'pinnedproject' ] ) ) {
			$pinnedproject = $_POST[ 'pinnedproject' ];
			$response = $this->db->where( 'id', $pinnedproject )->update( 'projects', array( 'pinned' => 0 ) );
			echo true;
		}
	}

	function addexpense( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$category_id = $this->input->post( 'category' );
			$customer_id = $this->input->post( 'customer' );
			$account_id = $this->input->post( 'account' );
			$title = $this->input->post( 'title' );
			$date = $this->input->post( 'date' );
			$amount = $this->input->post( 'amount' );
			$description = $this->input->post( 'description' );

			$hasError = false;
			if ($title == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('title');
			} else if ($amount == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('amount');
			} else if ($category_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
			} else if ($account_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
			}

			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}

			if (!$hasError) {
				$params = array(
					'category_id' => $category_id,
					'staff_id' => $this->session->usr_id,
					'customer_id' => $customer_id,
					'relation_type' => 'project',
					'relation' => $id,
					'account_id' => $account_id,
					'title' => $title,
					'date' => $date,
					'created' => date( 'Y-m-d H:i:s' ),
					'amount' => $amount,
					'description' => $description,
					'internal' => '1',
					'total_tax' => '0',
					'total_discount' => '0',
					'sub_total' => $amount,
				);
				$this->db->insert( 'expenses', $params );
				$expense_id = $this->db->insert_id();
				$item = array(
					'relation_type' => 'expense',
					'relation' => $expense_id,
					'product_id' => '',
					'code' => '',
					'name' => $this->input->post( 'name' ),
					'description' => $description,
					'quantity' => '1',
					'unit' => '1',
					'price' => $amount,
					'tax' => '0',
					'discount' => '0',
					'total' => $amount,
				);
				$this->db->insert( 'items', $item);
				$staffname = $this->session->staffname;
				$loggedinuserid = $this->session->usr_id;
				$appconfig = get_appconfig();
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'addedanewexpense' ) . ' <a href="expenses/receipt/' . $expense_id . '">' . $appconfig['expense_prefix'] . '-' . $expense_id.$appconfig['expense_suffix'] . '</a>.' ),
					'staff_id' => $loggedinuserid,
					'project_id' => $id,
					'customer_id' => $this->input->post( 'customer' )
				));
				
				$template = $this->Emails_Model->get_template('expense', 'expense_created');
				if ($template['status'] == 1) {
					$expense = $this->Expenses_Model->get_expenses( $expense_id );
					if ( $expense[ 'individual' ] ) {
						$customer = $expense[ 'individual' ];
					} else {
						$customer = $expense[ 'customer' ];
					}
					$message_vars = array(
						'{customer}' => $customer,
						'{expense_number}' => $appconfig['expense_prefix'].'-'.str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'],
						'{expense_title}' => $expense[ 'title' ],
						'{expense_category}' => $expense[ 'category' ],
						'{expense_date}' => $expense[ 'date' ],
						'{expense_description}' => $expense[ 'description' ],
						'{expense_amount}' => $expense[ 'amount' ],
						'{name}' => $this->session->userdata('staffname'),
						'{email_signature}' => $this->session->userdata('email'),
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $expense['customeremail'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" )
					);
					if ($expense['customeremail']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				$data['success'] = true;
				$data['message'] = lang('expense'). ' ' .lang('createmessage');
				echo json_encode($data);
			}
				
		}
	}

	function convert( $id ) {
		$project = $this->Projects_Model->get_projects( $id );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) { 
			$services = $this->Projects_Model->get_project_services($id);
			$params = array(
				'token' => md5( uniqid() ),
				'staff_id' => $project[ 'staff_id' ],
				'customer_id' => $project[ 'customer_id' ],
				'created' => date( 'Y-m-d H:i:s' ),
				'status_id' => 3,
				'total_discount' => 0,
				'total_tax' => 0,
				'total' => $this->input->post( 'total' ),
				'project_id' => $id,
				'sub_total' => $this->input->post( 'total' ),
			);
			$this->db->insert( 'invoices', $params );
			$invoice = $this->db->insert_id();
			$total = 0;
			$total_tax = 0;
			$sub_total = 0;
			foreach ( $services as $service ) {
				$this->db->insert( 'items', array(
					'relation_type' => 'invoice',
					'relation' => $invoice,
					'name' => $service[ 'servicename' ],
					'description' => $service[ 'servicedescription' ],
					'quantity' => $service[ 'quantity' ],
					'unit' => $service[ 'unit' ],
					'price' => $service[ 'serviceprice' ],
					'tax' => $service[ 'servicetax' ],
					'discount' => 0,
					'total' => $service[ 'quantity' ] * $service[ 'serviceprice' ] + ( ( $service[ 'servicetax' ] ) / 100 * $service[ 'quantity' ] * $service[ 'serviceprice' ] ),
				) );
				$total += $service[ 'quantity' ] * $service[ 'serviceprice' ] + ( ( $service[ 'servicetax' ] ) / 100 * $service[ 'quantity' ] * $service[ 'serviceprice' ] );
				$total_tax += ( $service[ 'servicetax' ] ) / 100 * $service[ 'quantity' ] * $service[ 'serviceprice' ];
			};
			$sub_total = $total - $total_tax;
			$response = $this->db->where( 'id', $invoice )->update( 'invoices', array( 'total' => $total, 'sub_total' => $sub_total, 'total_tax' => $total_tax ) );
			$this->db->insert( $this->db->dbprefix . 'sales', array(
				'invoice_id' => '' . $invoice . '',
				'status_id' => 3,
				'staff_id' => $this->session->usr_id,
				'customer_id' => $project[ 'customer_id' ],
				'total' => $total,
				'date' => date( 'Y-m-d H:i:s' )
			) );
			$staffname = $this->session->staffname;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $message = sprintf( lang( 'projecttoinvoicelog' ), $staffname, $project[ 'id' ] ) . '' ),
				'staff_id' => $this->session->usr_id,
				'customer_id' => $project[ 'customer_id' ],
			) );
			$response = $this->db->where( 'id', $id )->update( 'projects', array( 'invoice_id' => $invoice ) );
			echo $invoice;
		}
	}

	function convertwithcost( $id ) {
		$project = $this->Projects_Model->get_projects( $id );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) { 
			$services = $this->Projects_Model->get_project_services($id);
			$params = array(
				'token' => md5( uniqid() ),
				'staff_id' => $project[ 'staff_id' ],
				'customer_id' => $project[ 'customer_id' ],
				'created' => date( 'Y-m-d H:i:s' ),
				'status_id' => 3,
				'total_discount' => 0,
				'total_tax' => 0,
				'total' => $this->input->post( 'total' ),
				'project_id' => $id,
				'sub_total' => $this->input->post( 'total' ),
			);
			$this->db->insert( 'invoices', $params );
			$invoice = $this->db->insert_id();

			$this->db->insert( 'items', array(
				'relation_type' => 'invoice',
				'relation' => $invoice,
				'name' => $this->input->post( 'name' ),
				'description' => $this->input->post( 'description' ),
				'quantity' => 1,
				'unit' => 'Unit',
				'price' => $this->input->post( 'cost' ),
				'tax' => $this->input->post( 'tax' ),
				'discount' => 0,
				'total' => 1 * $this->input->post( 'cost' ) + ( ( $this->input->post( 'tax' ) ) / 100 * 1 * $this->input->post( 'cost' ) ),
				) );

			$total = 0;
			$sub_total = 0;
			$total_tax = ( $this->input->post( 'tax' ) ) / 100 * 1 * $this->input->post( 'cost' );
			$total = 1 * $this->input->post( 'cost' ) + ( ( $this->input->post( 'tax' ) ) / 100 * 1 * $this->input->post( 'cost' ) );
			$sub_total = $total - $total_tax;
			$response = $this->db->where( 'id', $invoice )->update( 'invoices', array( 'total' => $total, 'sub_total' => $total, 'total_tax' => $total_tax ) );

			foreach ( $services as $service ) {
				$this->db->insert( 'items', array(
					'relation_type' => 'invoice',
					'relation' => $invoice,
					'name' => $service[ 'servicename' ],
					'description' => $service[ 'servicedescription' ],
					'quantity' => $service[ 'quantity' ],
					'unit' => $service[ 'unit' ],
					'price' => 0,
					'tax' => 0,
					'discount' => 0,
					'total' => 0,
				) );
			};

			$this->db->insert( $this->db->dbprefix . 'sales', array(
				'invoice_id' => '' . $invoice . '',
				'status_id' => 3,
				'staff_id' => $this->session->usr_id,
				'customer_id' => $project[ 'customer_id' ],
				'total' => $total,
				'date' => date( 'Y-m-d H:i:s' )
			) );
			$staffname = $this->session->staffname;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $message = sprintf( lang( 'projecttoinvoicelog' ), $staffname, $project[ 'id' ] ) . '' ),
				'staff_id' => $this->session->usr_id,
				'customer_id' => $project[ 'customer_id' ],
			) );
			$response = $this->db->where( 'id', $id )->update( 'projects', array( 'invoice_id' => $invoice ) );
			echo $invoice;
		}
	}

	function removeService( $id ) {
		$services = $this->Projects_Model->get_project_service( $id );
		if ( isset( $services[ 'id' ] ) ) {
			$this->Projects_Model->delete_service( $id );
			$data['success'] = true;
			$data['message'] = lang('service') . ' ' . lang('deletemessage');
		} else {
			$data['success'] = false;
			$data['message'] = lang('servicedoesnotexist');
		}
	}

	/* Remove Project */
	function remove( $id ) {
		$projects = $this->Projects_Model->get_projects( $id );
		if ( isset( $projects[ 'id' ] ) ) {
			$this->Projects_Model->delete_projects( $id );
			redirect( 'projects/index' );
		} else
			show_error( 'The projects you are trying to delete does not exist.' );
	}

}