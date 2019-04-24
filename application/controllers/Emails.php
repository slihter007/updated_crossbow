<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );
class Emails extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'email_templates' );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'emails/index', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function template($id) {
		$template = $this->Emails_Model->get_email_template($id);
		if ($template['id'] == $id) {
			$data['TEMPLATEID'] = $id;
			$data[ 'title' ] = (lang( $template['name'] ))?(lang( $template['name'] )):($template['name']).' | '. lang( 'email_template' );
			$this->load->view( 'inc/header', $data );
			$this->load->view( 'emails/template', $data );
		}
	}

	function create() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$from_name = $this->input->post( 'from_name' );
			$subject = $this->input->post( 'subject' );
			$message = $this->input->post( 'message' );
			$relation = $this->input->post( 'relation' );
			if ($name == '' || $from_name == '' || $subject == '' || $message == '' || $relation == '') {
				$data['message'] = lang('invalidinput');
				$data['success'] = false;
				echo json_encode($data);
			} else {
				$params = array(
					'name' => $name,
					'from_name' => $from_name,
					'subject' => $subject,
					'message' => $message,
					'relation' => $relation
				);
				$this->db->insert( 'email_templates', $params );
				$template_id = $this->db->insert_id();
				if ($template_id) {
					$data['message'] = lang('email_template'). ' ' .lang('createmessage');
					$data['success'] = true;
					echo json_encode($data);
				}
			}
		}
	}

	function create_field() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$name = $this->input->post( 'name' );
			$value = $this->input->post( 'value' );
			$id = $this->input->post( 'id' );
			if ($name == '' || $value == '' || $id == '') {
				$data['message'] = lang('invalidinput');
				$data['success'] = false;
				echo json_encode($data);
			} else {
				$params = array(
					'template_id' => $id,
					'field_name' => $name,
					'field_value' => $value,
				);
				$this->db->insert( 'email_template_fields', $params );
				$template_id = $this->db->insert_id();
				if ($template_id) {
					$data['message'] = lang('email_template_field'). ' ' .lang('createmessage');
					$data['success'] = true;
					echo json_encode($data);
				}
			}
		}
	}

	function get_email_templates() {
		$templates = $this->Emails_Model->get_email_templates();
		$data_templates = array();
		foreach ( $templates as $template ) {
			$data_templates[] = array(
				'id' => $template[ 'id' ],
				'from_name' => $template[ 'from_name' ],
				'name' => lang($template['name'])? lang($template['name']) : $template['name'],
				'relation' => $template[ 'relation' ],
				'subject' => $template[ 'subject' ],
				'message' => $template[ 'message' ],
				'status' => $template[ 'status' ]
			);
		}
		echo json_encode( $data_templates );
	}

	function get_emails() {
		$emails = $this->Emails_Model->get_sent_emails();
		$data_emails = array();
		foreach ( $emails as $email ) {
			$data_email = @unserialize($email['email']);
				if ($data_email !== false) {
					$recipient = unserialize($email['email']);
					$recipient = implode(", ", $recipient);
				} else {
					$recipient = $email['email'];
				}
			$data_emails[] = array(
				'id' => $email[ 'id' ],
				'to' => $email['from_name'] ? $email['from_name'] : $email['email'],
				'email' => $recipient,
				'subject' => $email['subject'],
				'message' => $email['message'],
				'time' => $email['created'],
				'attachment' => $email['attachments']
			);
		}
		echo json_encode( $data_emails );
	}

	function template_fields($id) {
		$fields = $this->Emails_Model->template_fields($id);
		$data_templates = array();
		foreach ( $fields as $field ) {
			$data_templates[] = array(
				'name' => lang($field['field_name'])? lang($field['field_name']) : $field['field_name'],
				'value' => $field[ 'field_value' ]
			);
		}
		echo json_encode( $data_templates );
	}

	function get_email_template($id) {
		$template = $this->Emails_Model->get_email_template($id);
		if ($template['id'] == $id) {
			$template = $this->Emails_Model->get_email_template($id);
			$status = $template[ 'status' ];
			if ($status == 1) {
				$status = true;
			} else {
				$status = false;
			}
			if (($template['name'] == 'expense_consultant') || ($template['name'] == 'expense_recurring') || ($template['name'] == 'expense_created') || ($template['name'] == 'new_file_uploaded_by_customer')) {
				$attachment = true;
			} else {
				$attachment = false;
			}
			$data_templates = array(
				'id' => $template[ 'id' ],
				'from_name' => $template[ 'from_name' ],
				'name' => lang($template['name'])?lang($template['name']):($template['name']),
				'relation' => $template[ 'relation' ],
				'subject' => $template[ 'subject' ],
				'message' => ($template[ 'message' ]),
				'attachment' => ($template[ 'attachment' ] == '1')?true:false,
				'status' => $status,
				'isAttachment' => $attachment
			);
			echo json_encode( $data_templates );
		}
	}

	function update_template($id) {
		$template = $this->Emails_Model->get_email_template($id);
		if ($template['id'] == $id) {
			$params = array(
				'from_name' => $this->input->post( 'from_name' ),
				'subject' => $this->input->post( 'subject' ),
				'message' => $this->input->post('message', FALSE),
				'status' => $this->input->post( 'status' ),
				'attachment' => $this->input->post( 'attachment' )
			);
			$response = $this->Emails_Model->update_template($id, $params);
			if ($response) {
				$data['success'] = true;
				$data['message'] = lang('email_template').' '. lang('updatemessage');
				echo json_encode($data);
			} else {
				$data['success'] = false;
				$data['message'] = lang('errormessage');
				echo json_encode($data);
			}
		}
	}
}