<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Share extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model( 'Settings_Model' );
		define( 'LANG', $this->Settings_Model->get_crm_lang() );
		define( 'currency', $this->Settings_Model->get_currency() );
		$this->lang->load( LANG, LANG );
		$this->load->model( 'Invoices_Model' );
		$this->load->model( 'Proposals_Model' );
		$this->load->model( 'Report_Model' );
		$this->load->model( 'Emails_Model' );
	}

	function invoice( $token ) {
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoices_by_token( $token );
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $invoice[ 'id' ] ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'title' ] = 'INV-' . $invoice[ 'id' ] . ' Detail';
		$this->load->view( 'share/invoice', $data );
	}

	function proposal( $token ) {
		$proposal = $this->Proposals_Model->get_proposal_by_token( $token );
		$id = $proposal[ 'id' ];
		$data[ 'title' ] = 'PRO-' . $id . ' Detail';
		$this->load->model( 'Proposals_Model' );
		$this->load->model( 'Settings_Model' );
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'proposals' ] = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'proposal', 'relation' => $id ) )->result_array();
		$data[ 'comments' ] = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'proposal' ) )->result_array();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'share/proposal', $data );
	}

	function pdf( $token ) {
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoices_by_token( $token );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$dafault_payment_method = $data['invoice']['default_payment_method'];
		if ($dafault_payment_method == 'bank') {
			$modes = $this->Settings_Model->get_payment_gateway_data();
			$method = $modes['bank'];
		} else {
			$method = lang($data['invoice']['default_payment_method']);
		}
		$data['default_payment'] = $method;
		$data[ 'payments' ] = $this->Invoices_Model->get_invoices_payment( $invoice['id'] );
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $invoice[ 'id' ] ) )->result_array();
		$this->load->view( 'invoices/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['inv_prefix'] . '-' . str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ) .$appconfig['inv_suffix']. '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'assets/files/generated_pdf_files/invoices/' . $file_name . '', $output );
		$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
	}

	function pdf_proposal( $token ) {
		$proposal = $this->Proposals_Model->get_proposal_by_token( $token );
		$id = $proposal[ 'id' ];
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'proposals' ] = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'proposal', 'relation' => $id ) )->result_array();
		$this->load->view( 'proposals/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['proposal_prefix'] . '-' . str_pad( $id, 6, '0', STR_PAD_LEFT ).$appconfig['proposal_suffix'] . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'assets/files/generated_pdf_files/proposals/' . $file_name . '', $output );
		$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
	}

	function customercomment() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'content' => $this->input->post( 'content' ),
				'relation' => $this->input->post( 'relation' ),
				'relation_type' => 'proposal',
				'staff_id' => $this->session->userdata( 'usr_id' ),
				'created' => date( 'Y-m-d H:i:s' ),
			);
			$action = $this->db->insert( 'comments', $params );
			$proposals = $this->Proposals_Model->get_pro_rel_type( $this->input->post( 'relation' ) );
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => $message = sprintf( lang( 'newcommentforproposal' ), str_pad( $proposals[ 'id' ], 6, '0', STR_PAD_LEFT ) ),
				'staff_id' => $proposals[ 'assigned' ],
				'perres' => 'customer_avatar_comment.png',
				'target' => '' . base_url( 'proposals/proposal/' . $proposals[ 'id' ] . '' ) . ''
			) );
			$this->session->set_flashdata( 'ntf1', '' . lang( 'commentadded' ) . '' );
			redirect( 'share/proposal/' . $proposals[ 'token' ] . '' );
		} else {
			redirect( 'proposals/index' );
		}
	}

	function markasproposal() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'proposal_id' => $_POST[ 'proposal_id' ],
				'status_id' => $_POST[ 'status_id' ],
			);
			if ( $_POST[ 'status_id' ] == 5 ) {
				$notificationmessage = lang( 'proposaldeclined' );
				$templateStaff = $this->Emails_Model->get_template('proposal', 'customer_rejected_proposal');
				$template = $this->Emails_Model->get_template('proposal', 'thankyou_email');
			}
			if ( $_POST[ 'status_id' ] == 6 ) {
				$notificationmessage = lang( 'proposalaccepted' );
				$templateStaff = $this->Emails_Model->get_template('proposal', 'customer_accepted_proposal');
				$template = $this->Emails_Model->get_template('proposal', 'thankyou_email');
			}
			$proposals = $this->Proposals_Model->get_proposal( $_POST[ 'proposal_id' ] );
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => $message = sprintf( $notificationmessage, str_pad( $proposals[ 'id' ], 6, '0', STR_PAD_LEFT ) ),
				'staff_id' => $proposals[ 'assigned' ],
				'perres' => 'customer_avatar_comment.png',
				'target' => '' . base_url( 'proposals/proposal/' . $proposals[ 'id' ] . '' ) . ''
			) );
			if ($template['status'] == 1 || $templateStaff['status'] == 1) {
				$pro = $this->Proposals_Model->get_pro_rel_type( $proposals[ 'id' ] );
				$rel_type = $pro[ 'relation_type' ];
				$proposal = $this->Proposals_Model->get_proposals( $proposals[ 'id' ], $rel_type );
				if ($rel_type == 'customer') {
					$name = $proposal['namesurname'];
				} else {
					$name = $proposal['leadname'];
				}
				$message_vars = array(
					'{proposal_to}' => $name,
					'{proposal_number}' => $proposals[ 'id' ],
					'{subject}' => $proposal['subject'],
					'{details}' => $proposal['content'],
					'{proposal_total}' => $proposal['total'],
					'{name}' => $proposal['staffmembername'],
					'{email_signature}' => $proposal['staffemail'],
				);
			}
			if ($template['status'] == 1 && $_POST[ 'status_id' ] == 6) {
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$subjectStaff = strtr($templateStaff['subject'], $message_vars);
				$messageStaff = strtr($templateStaff['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $_SESSION['email'],
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" ),
					'status' => 1
				);
				if ($proposal['toemail']) {
					$this->db->insert( 'email_queue', $param );
				}
			}
			if ($templateStaff['status'] == 1) {
				$subjectStaff = strtr($templateStaff['subject'], $message_vars);
				$messageStaff = strtr($templateStaff['message'], $message_vars);
				$paramStaff = array(
					'from_name' => $templateStaff['from_name'],
					'email' => $proposal['staffemail'],
					'subject' => $subjectStaff,
					'message' => $messageStaff,
					'created' => date( "Y.m.d H:i:s" ),
					'status' => 1
				);
				if ($proposal['toemail']) {
					$this->db->insert( 'email_queue', $paramStaff );
				}
			}

			$actionpro = $this->Proposals_Model->markas();
		}
	}

}