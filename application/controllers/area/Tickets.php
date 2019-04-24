<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Tickets extends AREA_Controller {


	function index() {
		$data[ 'title' ] = lang( 'areatitletickets' );
		$data[ 'ttc' ] = $this->Area_Model->ttc();
		$data[ 'otc' ] = $this->Area_Model->otc();
		$data[ 'ipc' ] = $this->Area_Model->ipc();
		$data[ 'atc' ] = $this->Area_Model->atc();
		$data[ 'ctc' ] = $this->Area_Model->ctc();
		$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
		$data[ 'tickets' ] = $this->db->select( '*,customers.type as type,customers.company as company,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,contacts.name as contactname,contacts.surname as contactsurname,tickets.staff_id as stid, tickets.id as id ' )->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' )->join( 'customers', 'contacts.customer_id = customers.id', 'left' )->join( 'departments', 'tickets.department_id = departments.id', 'left' )->join( 'staff', 'tickets.staff_id = staff.id', 'left' )->get_where( 'tickets', array( 'contact_id' => $_SESSION[ 'contact_id' ] ) )->result_array();
		$data[ 'departments' ] = $this->db->get_where( 'departments', array( '' ) )->result_array();
		//Detaylar 
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'area/inc/header', $data );
		$this->load->view( 'area/tickets/index', $data );
		$this->load->view( 'area/inc/footer', $data );

	}

	function create_ticket() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$config[ 'upload_path' ] = './uploads/attachments/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'attachment' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$params = array(
				'contact_id' => $_SESSION[ 'contact_id' ],
				'customer_id' => $_SESSION[ 'customer' ],
				'email' => $_SESSION[ 'email' ],
				'department_id' => $this->input->post( 'department' ),
				'priority' => $this->input->post( 'priority' ),
				'status_id' => 1,
				'subject' => $this->input->post( 'subject' ),
				'message' => $this->input->post( 'message' ),
				'attachment' => $image_data[ 'file_name' ],
				'date' => date( " Y.m.d H:i:s " ),
			);
			$this->session->set_flashdata( 'ntf1', 'Ticket added' );
			$tickets_id = $this->Area_Model->add_tickets( $params );

			$template = $this->Emails_Model->get_template('ticket', 'new_customer_ticket');
			if ($template['status'] == 1) {
				$ticket = $this->Tickets_Model->get_tickets( $tickets_id );
				$admins = $this->Staff_Model->get_all_admins(); 
				switch ( $this->input->post( 'priority' ) ) {
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
					'{customer_id}' => $_SESSION[ 'customer' ],
					'{customer}' => $_SESSION[ 'name' ],
					'{name}' => $_SESSION[ 'name' ],
					'{email_signature}' => $_SESSION[ 'email' ],
					'{ticket_subject}' => $this->input->post( 'subject' ),
					'{ticket_message}' => $this->input->post( 'message' ),
					'{ticket_department}' => $ticket['department'],
					'{ticket_priority}' => $priority,
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $admins['email'],
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" )
				);
				if ($param['email']) {
					$this->db->insert( 'email_queue', $param );
				}
			}
			$template = $this->Emails_Model->get_template('ticket', 'ticket_autoresponse');
			if ($template['status'] == 1) {
				$ticket = $this->Tickets_Model->get_tickets( $tickets_id );
				switch ( $this->input->post( 'priority' ) ) {
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
					'{customer_id}' => $_SESSION[ 'customer' ],
					'{customer}' => $_SESSION[ 'name' ],
					'{name}' => $_SESSION[ 'name' ],
					'{email_signature}' => $_SESSION[ 'email' ],
					'{ticket_subject}' => $this->input->post( 'subject' ),
					'{ticket_message}' => $this->input->post( 'message' ),
					'{ticket_department}' => $ticket['department'],
					'{ticket_priority}' => $priority,
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $_SESSION[ 'email' ],
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" )
				);
				if ($_SESSION[ 'email' ]) {
					$this->db->insert( 'email_queue', $param );
				}
			}
			redirect( 'area/tickets' );
		}
	}

	function ticket( $id ) {
		$permission = $this->Tickets_Model->check_tickets_permission($id, $_SESSION[ 'contact_id' ]);
		if ($permission) {
			$data[ 'title' ] = lang( 'areatitletickets' );
			$data[ 'ticketstatustitle' ] = lang('alltickets');
			$data[ 'ttc' ] = $this->Area_Model->ttc();
			$data[ 'otc' ] = $this->Area_Model->otc();
			$data[ 'ipc' ] = $this->Area_Model->ipc(); 
			$data[ 'atc' ] = $this->Area_Model->atc();
			$data[ 'ctc' ] = $this->Area_Model->ctc();
			$data[ 'ysy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'otc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
			$data[ 'bsy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ipc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
			$data[ 'twy' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'atc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
			$data[ 'iey' ] = ( $data[ 'ttc' ] > 0 ? number_format( ( $data[ 'ctc' ] * 100 ) / $data[ 'ttc' ] ) : 0 );
			$data[ 'ticket' ] = $this->Tickets_Model->get_tickets( $id );
			$data[ 'dtickets' ] = $this->db->select( '*,customers.type as type,customers.company as company,customers.namesurname as namesurname,departments.name as department,staff.staffname as staffmembername,contacts.name as contactname,contacts.surname as contactsurname,tickets.staff_id as stid, tickets.id as id ' )->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' )->join( 'customers', 'contacts.customer_id = customers.id', 'left' )->join( 'departments', 'tickets.department_id = departments.id', 'left' )->join( 'staff', 'tickets.staff_id = staff.id', 'left' )->get_where( 'tickets', array( 'contact_id' => $_SESSION[ 'contact_id' ] ) )->result_array();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$this->load->view( 'area/inc/header', $data );
			$this->load->view( 'area/tickets/ticket', $data );
			$this->load->view( 'area/inc/footer', $data );
		} else {
			redirect( 'area/tickets' );
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
				'staff_id' => $ticket[ 'staff_id' ],
				'contact_id' => $_SESSION[ 'contact_id' ],
				'date' => date( " Y.m.d H:i:s " ),
				'name' => $_SESSION[ 'name' ],
				'message' => $this->input->post( 'message' ),
				'attachment' => $image_data[ 'file_name' ],
			);
			$contact = $_SESSION[ 'name' ];
			$contactavatar = 'n-img.png';
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $contact . ' '. lang( 'replied' ).' ' . lang( 'ticket' ) . '-' . $id . '' ),
				'perres' => $contactavatar,
				'staff_id' => $ticket[ 'staff_id' ],
				'target' => '' . base_url( 'tickets/ticket/' . $id . '' ) . ''
			) );
			$response = $this->db->where( 'id', $id )->update( 'tickets', array(
				'status_id' => 1,
				'lastreply' => date( "Y.m.d H:i:s " ),
			) );

			$template = $this->Emails_Model->get_template('ticket', 'ticket_reply_to_staff');
			if ($template['status'] == 1) {
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

				if ($ticket['staffemail']) {
					$email = $ticket['staffemail'];
				} else {
					$admins = $this->Staff_Model->get_all_admins();
					$email = $admins['email'];
				}

				$message_vars = array(
					'{customer}' => $customer,
					'{name}' => $_SESSION[ 'name' ],
					'{email_signature}' => $_SESSION[ 'email' ],
					'{ticket_subject}' => $ticket['subject'],
					'{ticket_message}' => $this->input->post( 'message' ),
					'{ticket_department}' => $ticket['department'],
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $email,
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" )
				);
				if ($email) {
					$this->db->insert( 'email_queue', $param );
				}
			}

			$replyid = $this->Tickets_Model->add_reply_contact( $params );
			redirect( 'area/tickets/ticket/' . $id . '' );
		}
	}
}