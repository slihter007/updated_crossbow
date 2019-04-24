<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Tickets extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'tickets' );
		$data[ 'tickets' ] = $this->Tickets_Model->get_all_tickets();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'tickets/index', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function create() {
		$data[ 'title' ] = lang( 'addticket' );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$config[ 'upload_path' ] = './uploads/attachments/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'attachment' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'contact_id' => $this->input->post( 'contact' ),
				'customer_id' => $this->input->post( 'customer' ),
				'department_id' => $this->input->post( 'department' ),
				'priority' => $this->input->post( 'priority' ),
				'status_id' => 1,
				'subject' => $this->input->post( 'subject' ),
				'message' => $this->input->post( 'message' ),
				'attachment' => $image_data[ 'file_name' ],
				'date' => date( " Y.m.d H:i:s " ),
			);
			$this->session->set_flashdata( 'ntf1', lang( 'ticketadded' ) );
			$tickets_id = $this->Tickets_Model->add_tickets( $params );
			// Custom Field Post
			if ( $this->input->post( 'custom_fields' ) ) {
				$custom_fields = array(
					'custom_fields' => $this->input->post( 'custom_fields' )
				);
				$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'ticket', $tickets_id );
			} 

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
				//$message = ($message, FALSE);

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
			redirect( 'tickets/ticket/' . $tickets_id . '' );
		}
	}

	function ticket( $id ) {
		$ticket = $this->Tickets_Model->get_tickets( $id );
		$data[ 'title' ] = $ticket[ 'subject' ];
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'ticket' ] = $this->Tickets_Model->get_tickets( $id );
		$data[ 'all_staff' ] = $this->Staff_Model->get_all_staff();
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'tickets/ticket', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function assign_staff( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'ticket_id' => $id,
				'staff_id' => $this->input->post( 'staff' ),
			);
			$response = $this->db->where( 'id', $id )->update( 'tickets', array( 'staff_id' => $this->input->post( 'staff' ) ) );
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $this->session->staffname . ' assigned you a ' . lang( 'ticket' ) . '-' . $id . '' ),
				'staff_id' => $this->input->post( 'staff' ),
				'perres' => $this->session->staffavatar,
				'target' => '' . base_url( 'tickets/ticket/' . $id . '' ) . ''
			) );
			$user = $this->Staff_Model->get_staff( $this->input->post( 'staff' ) );

			$template = $this->Emails_Model->get_template('ticket', 'ticket_assigned');
			if ($template['status'] == 1) {
				$ticket = $this->Tickets_Model->get_tickets( $id );
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
					'{assigned}' => $ticket['staffmembername'],
					'{customer}' => $customer,
					'{name}' => $this->session->userdata('staffname'),
					'{email_signature}' => $this->session->userdata('email'),
					'{ticket_subject}' => $ticket['subject'],
					'{ticket_message}' => $ticket['message'],
					'{ticket_priority}' => $priority,
					'{ticket_department}' => $ticket['department'],
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $ticket['staffemail'],
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" )
				);
				if ($ticket['staffemail']) {
					$this->db->insert( 'email_queue', $param );
				}
			}
			echo $user[ 'staffname' ];
		}
	}

	function reply( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$ticket = $this->Tickets_Model->get_tickets( $id );
			$config[ 'upload_path' ] = './uploads/attachments/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'attachment' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'ticket_id' => $id,
				'staff_id' => $this->session->userdata( 'usr_id' ),
				'contact_id' => $ticket[ 'contact_id' ],
				'date' => date( " Y-m-d h:i:sa" ),
				'name' => $this->session->userdata( 'staffname' ),
				'message' => $this->input->post( 'message' ),
				'attachment' => $image_data[ 'file_name' ],
			);
			$this->db->insert( 'ticketreplies', $params );
			$staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'replied' ) . ' <a href="tickets/ticket/' . $id . '"> ' . lang( 'ticket' ) . '-' . $id . '</a>' ),
				'staff_id' => $loggedinuserid
			) );
			$staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$staffavatar = $this->session->staffavatar;
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $staffname . ' '. lang( 'replied' ).' ' . lang( 'ticket' ) . '-' . $id . '' ),
				'contact_id' => $ticket[ 'contact_id' ],
				'perres' => $staffavatar,
				'target' => '' . base_url( 'area/tickets/ticket/' . $id . '' ) . ''
			) );
			$response = $this->db->where( 'id', $id )->update( 'tickets', array(
				'status_id' => 3,
				'lastreply' => date( "Y.m.d H:i:s " ),
				'staff_id' => $loggedinuserid,
			));

			$template = $this->Emails_Model->get_template('ticket', 'ticket_reply_to_customer');
			if ($template['status'] == 1) {
				if ( $ticket[ 'type' ] == 0 ) {
					$customer = $ticket[ 'company' ];
				} else {
					$customer = $ticket[ 'namesurname' ];
				} 

				$message_vars = array(
					'{customer}' => $customer,
					'{name}' => $this->session->userdata('staffname'),
					'{email_signature}' => $this->session->userdata('email'),
					'{ticket_subject}' => $ticket['subject'],
					'{ticket_message}' => $ticket['message'],
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
			redirect( 'tickets/ticket/' . $id . '' );
		}
	}

	function attachments($file) {
		if (is_file('./uploads/attachments/' . $file)) {
    		$this->load->helper('file');
    		$this->load->helper('download');
    		$data = file_get_contents('./uploads/attachments/' . $file);
    		force_download($file, $data);
    	} else {
    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
    		redirect('tickets/index');
    	}
	}

	function markas() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'ticket_id' => $_POST[ 'ticket_id' ],
				'status_id' => $_POST[ 'status_id' ],
			);
			$tickets = $this->Tickets_Model->markas();
		}
	}

	function remove( $id ) {
		$this->session->set_flashdata( 'ntf4', lang( 'ticketdeleted' ) );
		$tickets = $this->Tickets_Model->get_tickets( $id );
		if ( isset( $tickets[ 'id' ] ) ) {
			$this->Tickets_Model->delete_tickets( $id );
		} else
			show_error( 'Eror' );
	}
}