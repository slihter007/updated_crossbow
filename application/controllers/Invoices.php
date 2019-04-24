<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Invoices extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'invoices' );
		$data[ 'off' ] = $this->Report_Model->pff();
		$data[ 'ofv' ] = $this->Report_Model->ofv();
		$data[ 'oft' ] = $this->Report_Model->oft();
		$data[ 'vgf' ] = $this->Report_Model->vgf();
		$data[ 'tfa' ] = $this->Report_Model->tfa();
		$data[ 'pfs' ] = $this->Report_Model->pfs();
		$data[ 'otf' ] = $this->Report_Model->otf();
		$data[ 'tef' ] = $this->Report_Model->tef();
		$data[ 'vdf' ] = $this->Report_Model->vdf();
		$data[ 'fam' ] = $this->Report_Model->fam();
		$data[ 'ofy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'tef' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'ofx' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'otf' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'vgy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'vdf' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'invoices' ] = $this->Invoices_Model->get_all_invoices();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'invoices/index', $data );
	}

	function create() {
		$data[ 'title' ] = lang( 'newinvoice' );
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		$products = $this->Products_Model->get_all_products();
		$settings = $this->Settings_Model->get_settings_ciuis();
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$customer = $this->input->post( 'customer' );
			$created = $this->input->post('created');
			$duedate = $this->input->post( 'duedate' );
			$datepayment = $this->input->post( 'datepayment' );
			$account = $this->input->post( 'account' );
			$totalItems = $this->input->post( 'totalItems' );
			$recurring_period = $this->input->post( 'recurring_period' );
			$recurring = $this->input->post( 'recurring' );
			$status = $this->input->post( 'status' );
			$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);
			$paid = ($status == 'true')? '1' : '0';

			$hasError = false;

			if ($customer == '') {
				$hasError = true;
				$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
			} else if (($created == '')) {
				$hasError = true;
				$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('dateofissuance');
			} else if (($paid == '1') && ($this->input->post('account') == '')) {
				$hasError = true;
				$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
			} else if (($paid == '1') && ($this->input->post('datepayment') == '')) {
				$hasError = true;
				$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('datepaid');
			} else if (($paid == '0') && ($duedate == '')) {
				$hasError = true;
				$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('duedate');
			} else if (($paid == '0') && (strtotime($duedate) < strtotime($created))) {
				$hasError = true;
				$datas['message'] = lang('dateofissuance').' '.lang('date_error'). ' ' .lang('duedate');
			} else if (($recurring == '1') && ($this->input->post('recurring_period') == '')) {
				$hasError = true;
				$datas['message'] = lang('invalidmessage'). ' ' .lang('recurring_period');
			} else if (((int)($this->input->post('totalItems'))) == 0) {
				$hasError = true;
				$datas['message'] = lang('invalid_items');
			} else if ($total == 0) {
				$hasError = true;
				$datas['message'] = lang('invalid_total');
			}
			if ($hasError) {
				$datas['success'] = false;
				echo json_encode($datas);
			}
			if (!$hasError) {
				$status_value = $this->input->post( 'status' );
				if ( $status_value == 'true' ) {
					$datepayment = $this->input->post( 'datepayment' );
					$duenote = null;
					$duedate = null;
					$status = 2;
				} else {
					$duedate = $this->input->post( 'duedate' );
					$duenote = $this->input->post( 'duenote' );
					$datepayment = null;
					$status = 3;
				}
				$params = array(
					'token' => md5( uniqid() ),
					'no' => $this->input->post( 'no' ),
					'serie' => $this->input->post( 'serie' ),
					'customer_id' => $this->input->post( 'customer' ),
					'staff_id' => $this->session->usr_id,
					'status_id' => $status,
					'created' => $this->input->post( 'created' ),
					'last_recurring' => $this->input->post( 'created' ),
					'duedate' => $duedate,
					'datepayment' => $datepayment,
					'duenote' => $duenote,
					'sub_total' => $this->input->post( 'sub_total' ),
					'total_discount' => $this->input->post( 'total_discount' ),
					'total_tax' => $this->input->post( 'total_tax' ),
					'total' => $this->input->post( 'total' ),
					'billing_street' => $this->input->post( 'billing_street' ),
					'billing_city' => $this->input->post( 'billing_city' ),
					'billing_state' => $this->input->post( 'billing_state' ),
					'billing_zip' => $this->input->post( 'billing_zip' ),
					'billing_country' => $this->input->post( 'billing_country' ),
					'shipping_street' => $this->input->post( 'shipping_street' ),
					'shipping_city' => $this->input->post( 'shipping_city' ),
					'shipping_state' => $this->input->post( 'shipping_state' ),
					'shipping_zip' => $this->input->post( 'shipping_zip' ),
					'shipping_country' => $this->input->post( 'shipping_country' ),
					'default_payment_method' => $this->input->post('default_payment_method'),
				);
				$invoices_id = $this->Invoices_Model->invoice_add( $params );
				// Custom Field Post
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' ) 
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'invoice', $invoices_id );
				}

				$template = $this->Emails_Model->get_template('invoice', 'invoice_message');
				if ($template['status'] == 1) {
					$appconfig = get_appconfig();
					$invoice = $this->Invoices_Model->get_invoice_detail( $invoices_id );
					$inv_number = $appconfig['inv_prefix'] . '' . str_pad( $invoices_id, 6, '0', STR_PAD_LEFT ).$appconfig['inv_suffix'];
					$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
					$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );
					if ( $status == 1 ) {
						$invoicestatus = lang( 'draft' );
					}
					if ( $status == 3 ) {
						$invoicestatus = lang( 'unpaid' );
					}
					if ( $status == 4 ) {
						$invoicestatus = lang( 'cancelled' );
					}
					if ( $status == 2 ) {
						$invoicestatus = lang( 'partial' );
					}
					$message_vars = array(
						'{invoice_number}' => $inv_number,
						'{invoice_link}' => $link,
						'{invoice_status}' => $invoicestatus,
						'{email_signature}' => $this->session->userdata( 'email' ),
						'{name}' => $this->session->userdata( 'staffname' ),
						'{customer}' => $name
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);
					$param = array(
						'from_name' => $template['from_name'],
						'email' => $invoice['email'],
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" ),
					);
					if ($invoice['email']) {
						$this->db->insert( 'email_queue', $param );
					}
				}

				if ( $this->input->post( 'recurring' ) == 'true'  || $this->input->post('recurring') == '1') {
					$SHXparams = array(
						'relation_type' => 'invoice',
						'relation' => $invoices_id,
						'period' => $this->input->post( 'recurring_period' ),
						'end_date' => $this->input->post( 'end_recurring' ),
						'type' => $this->input->post( 'recurring_type' ),
					);
					$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
				}
				$datas['success'] = true;
				$datas['id'] = $invoices_id;
				$datas['message'] = lang('invoice').' '.lang('createmessasge');
				echo json_encode($datas);
			}
		} else {
			$data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
			$data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$this->load->view( 'inc/header', $data );
			$this->load->view( 'invoices/create', $data );
			$this->load->view( 'inc/footer', $data );
		}
	}

	function update( $id ) {
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		if (!$this->session->userdata('other')) {
			$appconfig = get_appconfig();
			$invoices = $this->Invoices_Model->get_invoices( $id );
			$data[ 'title' ] = '' . lang( 'updateinvoicetitle' ) . ' ' . $appconfig['inv_prefix'] . '' . str_pad( $invoices[ 'id' ], 6, '0', STR_PAD_LEFT ) . $appconfig['inv_suffix'].'';
			$data[ 'invoices' ] = $this->Invoices_Model->get_invoice_detail( $id );
			if ( isset( $invoices[ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$customer = $this->input->post( 'customer' );
					$created = $this->input->post('created');
					$duedate = $this->input->post( 'duedate' );
					$datepayment = $this->input->post( 'datepayment' );
					$account = $this->input->post( 'account' );
					$totalItems = $this->input->post( 'totalItems' );
					$recurring_period = $this->input->post( 'recurring_period' );
					$recurring_status = $this->input->post( 'recurring_status' );
					$status = $this->input->post( 'status' );
					$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);
					$paid = ($status == 'true')? '1' : '0';

					$hasError = false;

					if ($customer == '') {
						$hasError = true;
						$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
					} else if (($created == '')) {
						$hasError = true;
						$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('dateofissuance');
					} else if (($paid == '1') && ($this->input->post('account') == '')) {
						$hasError = true;
						$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
					} else if (($paid == '1') && ($this->input->post('datepayment') == '')) {
						$hasError = true;
						$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('datepaid');
					} else if (($paid == '0') && ($duedate == '')) {
						$hasError = true;
						$datas['message'] = lang('selectinvalidmessage'). ' ' .lang('duedate');
					} else if (($paid == '0') && (strtotime($duedate) < strtotime($created))) {
						$hasError = true;
						$datas['message'] = lang('dateofissuance').' '.lang('date_error'). ' ' .lang('duedate');
					} else if (($recurring_status == 'true') && ($this->input->post('recurring_period') == '')) {
						$hasError = true;
						$datas['message'] = lang('invalidmessage'). ' ' .lang('recurring_period');
					} else if (((int)($this->input->post('totalItems'))) == 0) {
						$hasError = true;
						$datas['message'] = lang('invalid_items');
					} else if ($total == 0) {
						$hasError = true;
						$datas['message'] = lang('invalid_total');
					}
					if ($hasError) {
						$datas['success'] = false;
						echo json_encode($datas);
					}
					if (!$hasError) {
						if ( $invoices[ 'status_id' ] == 2 ) {
							$datepayment = $this->input->post( 'datepayment' );
							$duenote = null;
							$duedate = null;
						} else {
							$duedate = $this->input->post( 'duedate' );
							$duenote = $this->input->post( 'duenote' );
							$datepayment = null;
						}
						$params = array(
							'no' => $this->input->post( 'no' ),
							'serie' => $this->input->post( 'serie' ),
							'customer_id' => $this->input->post( 'customer' ),
							'created' => $this->input->post( 'created' ),
							'last_recurring' => $this->input->post( 'created' ),
							'duedate' => $duedate,
							'duenote' => $duenote,
							'sub_total' => $this->input->post( 'sub_total' ),
							'total_discount' => $this->input->post( 'total_discount' ),
							'total_tax' => $this->input->post( 'total_tax' ),
							'total' => $this->input->post( 'total' ),
							'default_payment_method' => $this->input->post('default_payment_method'),
						);
						$this->Invoices_Model->update_invoices( $id, $params );
						// Custom Field Post
						if ( $this->input->post( 'custom_fields' ) ) {
							$custom_fields = array(
								'custom_fields' => $this->input->post( 'custom_fields' )
							);
							$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'invoice', $id );
						}

						// START Recurring Invoice
						if ($this->input->post('recurring') == 'true') {
							$SHXparams = array(
								'period' => $this->input->post( 'recurring_period' ),
								'end_date' => $this->input->post( 'end_recurring' ),
								'type' => $this->input->post( 'recurring_type' ),
								'status' => 0,
							);
							$recurring_invoices_id = $this->Invoices_Model->recurring_update( $id, $SHXparams );
						} else {
							$SHXparams = array(
								'period' => $this->input->post( 'recurring_period' ),
								'end_date' => $this->input->post( 'end_recurring' ),
								'type' => $this->input->post( 'recurring_type' ),
								'status' => 1,
							);
							$recurring_invoices_id = $this->Invoices_Model->recurring_update( $id, $SHXparams );
						}
						if ( !is_numeric( $this->input->post( 'recurring_id' ) ) && ($this->input->post('recurring_status') == 'true') ) { // NEW Recurring From Update
							$SHXparams = array(
								'relation_type' => 'invoice',
								'relation' => $id,
								'period' => $this->input->post( 'recurring_period' ),
								'end_date' => $this->input->post( 'end_recurring' ),
								'type' => $this->input->post( 'recurring_type' ),
							);
							$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
						}
						$datas['success'] = true;
						$datas['id'] = $id;
						$datas['message'] = lang('invoice').' '.lang('updatemessasge');
						echo json_encode($datas);
					}
					// END Recurring Invoice
				} else {
					$this->load->view( 'invoices/update', $data );
				}
			} else
				$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'error' ) );
		} else {
			$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
			redirect('invoices');
		}
	}

	function invoice( $id ) {
		$appconfig = get_appconfig();
		$invoices = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'title' ] = '' . $appconfig['inv_prefix'] . '' . str_pad( $invoices[ 'id' ], 6, '0', STR_PAD_LEFT ) .$appconfig['inv_suffix']. ' ' . lang( 'detail' ) . '';
		$data[ 'invoices' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'invoices/invoice', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function record_payment() {
		$amount = $this->input->post( 'amount' );
		$invoicetotal = $this->input->post( 'invoicetotal' );
		if ( $amount > $invoicetotal ) { 
			echo lang( 'paymentamounthigh' );
		} else {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'invoice_id' => $this->input->post( 'invoice' ),
					'amount' => $amount,
					'account_id' => $this->input->post( 'account' ),
					'date' => $this->input->post( 'date' ),
					'not' => $this->input->post( 'not' ),
					'attachment' => $this->input->post( 'attachment' ),
					'customer_id' => $this->input->post( 'customer' ),
					'staff_id' => $this->input->post( 'staff' ),
				);
				$payments = $this->Payments_Model->addpayment( $params );
				$this->invoice_payment_email($params);
				echo lang( 'paymentaddedsuccessfully' );
			}
		}
	}

	function invoice_payment_email($params) {
		$invoice = $this->Invoices_Model->get_invoice_detail( $params['invoice_id'] );
		$template = $this->Emails_Model->get_template('invoice', 'invoice_payment');
		if ($template['status'] == 1) {
			$appconfig = get_appconfig();
			$inv_number = $appconfig['inv_prefix'] . '' . str_pad( $params['invoice_id'], 6, '0', STR_PAD_LEFT ).$appconfig['inv_suffix'];
			
			$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
			$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );

			$message_vars = array(
				'{invoice_number}' => $inv_number,
				'{invoice_link}' => $link,
				'{payment_total}' => $params['amount'],
				'{payment_date}' => $params['date'],
				'{email_signature}' => $this->session->userdata( 'email' ),
				'{name}' => $this->session->userdata( 'staffname' ),
				'{customer}' => $name
			);
			$subject = strtr($template['subject'], $message_vars);
			$message = strtr($template['message'], $message_vars);
			$param = array(
				'from_name' => $template['from_name'],
				'email' => $invoice['email'],
				'subject' => $subject,
				'message' => $message,
				'created' => date( "Y.m.d H:i:s" ),
			);
			if ($invoice['email']) {
				$this->db->insert( 'email_queue', $param );
			}
		}
	}

	function create_pdf($id) {
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$dafault_payment_method = $data['invoice']['default_payment_method'];
		if ($dafault_payment_method == 'bank') {
			$modes = $this->Settings_Model->get_payment_gateway_data();
			$method = $modes['bank'];
		} else {
			$method = lang($data['invoice']['default_payment_method']);
		}
		$data['default_payment'] = $method;
		$data[ 'payments' ] = $this->Invoices_Model->get_invoices_payment($id);
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
		$this->load->view( 'invoices/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['inv_prefix'] . '' . str_pad( $id, 6, '0', STR_PAD_LEFT ) .$appconfig['inv_suffix']. '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'assets/files/generated_pdf_files/invoices/' . $file_name . '', $output );
		//$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
		if ( $output ) {
			redirect( base_url( 'invoices/pdf_generated/' . $file_name . '' ) );
		} else {
			redirect( base_url( 'invoices/pdf_fault/' ) );
		}
	}

	function print_( $id ) {
		$data[ 'payments' ] = $this->Invoices_Model->get_invoices_payment( $id );
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$dafault_payment_method = $data['invoice']['default_payment_method'];
		if ($dafault_payment_method == 'bank') {
			$modes = $this->Settings_Model->get_payment_gateway_data();
			$method = $modes['bank'];
		} else {
			$method = lang($data['invoice']['default_payment_method']);
		}
		$data['default_payment'] = $method;
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
		$this->load->view( 'invoices/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['inv_prefix'] . '' . str_pad( $id, 6, '0', STR_PAD_LEFT ) .$appconfig['inv_suffix']. '.pdf';
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

	function pdf_generated( $file ) {
		$result = array(
			'status' => true,
			'file_name' => $file,
		);
		echo json_encode( $result );
	}

	function pdf_fault() {
		$result = array(
			'status' => false,
		);
		echo json_encode( $result );
	}

	function dp( $id ) {
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoice_detail( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
		$this->load->view( 'invoices/pdf', $data );
	}

	function send_invoice_email($id) {
		$invoice = $this->Invoices_Model->get_invoice_detail( $id );
		$template = $this->Emails_Model->get_template('invoice', 'invoice_message');
		$appconfig = get_appconfig();
		$inv_number = $appconfig['inv_prefix'] . '' . str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['inv_suffix'];
		if ( $invoice[ 'status_id' ] == 1 ) {
			$invoicestatus = lang( 'draft' );
		}
		if ( $invoice[ 'status_id' ] == 3 ) {
			$invoicestatus = lang( 'unpaid' );
		}
		if ( $invoice[ 'status_id' ] == 4 ) {
			$invoicestatus = lang( 'cancelled' );
		}
		if ( $invoice[ 'status_id' ] == 2 ) {
			$invoicestatus = lang( 'partial' );
		}
		$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
		$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );

		$message_vars = array(
			'{invoice_number}' => $inv_number,
			'{invoice_link}' => $link,
			'{invoice_status}' => $invoicestatus,
			'{email_signature}' => $this->session->userdata( 'email' ),
			'{name}' => $this->session->userdata( 'staffname' ),
			'{customer}' => $name
		);
		$subject = strtr($template['subject'], $message_vars);
		$message = strtr($template['message'], $message_vars);

		$param = array(
			'from_name' => $template['from_name'],
			'email' => $invoice['email'],
			'subject' => $subject,
			'message' => $message,
			'created' => date( "Y.m.d H:i:s" ),
			'status' => 0
		);

		$this->load->library('mail'); 
		$data = $this->mail->send_email($invoice['email'], $template['from_name'], $subject, $message);
		if ($data['success'] == true) {
			$return['status'] = true;
			$return['message'] = $data['message'];
			if ($invoice['email']) {
				$this->db->insert( 'email_queue', $param );
			}
			echo json_encode($return);
		} else {
			$return['status'] = false;
			$return['message'] = lang('errormessage');
			echo json_encode($return);
		}
	}

	function share( $id ) {
		$inv = $this->Invoices_Model->get_invoice_detail( $id );
		// SEND EMAIL SETTINGS
		switch ( $inv[ 'type' ] ) {
			case '0':
				$invcustomer = $inv[ 'customercompany' ];
				break;
			case '1':
				$invcustomer = $inv[ 'namesurname' ];
				break;
		}
		$subject = lang( 'yourinvoicedetails' );
		$to = $inv[ 'email' ];
		$data = array(
			'customer' => $invcustomer,
			'customermail' => $inv[ 'email' ],
			'invoicelink' => '' . base_url( 'share/invoice/' . $inv[ 'token' ] . '' ) . ''
		);
		$body = $this->load->view( 'email/invoices/sendinvoice.php', $data, TRUE );
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			$response = $this->db->where( 'id', $id )->update( 'invoices', array( 'datesend' => date( 'Y-m-d H:i:s' ) ) );
			$this->session->set_flashdata( 'ntf1', '<b>' . $inv[ 'email' ], lang( 'sendmailcustomer' ) . '</b>' );
			redirect( 'invoices/invoice/' . $id . '' );

		} else {
			$this->session->set_flashdata( 'ntf4', '<b>' . lang( 'sendmailcustomereror' ) . '</b>' );
			redirect( 'invoices/invoice/' . $id . '' );
		}


	}

	function mark_as_draft( $id ) {
		$response = $this->db->where( 'id', $id )->update( 'invoices', array( 'status_id' => 1 ) );
		$response = $this->db->update( 'sales', array( 'invoice_id' => $id, 'status_id' => 1 ) );
		echo lang( 'markedasdraft' );
	}

	function mark_as_cancelled( $id ) {
		$response = $this->db->where( 'id', $id )->update( 'invoices', array( 'status_id' => 4 ) );
		$response = $this->db->delete( 'sales', array( 'invoice_id' => $id ) );
		$response = $this->db->delete( 'payments', array( 'invoice_id' => $id ) );
		echo lang( 'markedascancelled' );
	}

	function remove( $id ) {
		$invoices = $this->Invoices_Model->get_invoices( $id );
		if ( isset( $invoices[ 'id' ] ) ) {
			$this->session->set_flashdata( 'ntf4', lang( 'invoicedeleted' ) );
			$this->Invoices_Model->delete_invoices( $id );
			redirect( 'invoices/index' );
		} else
			show_error( 'The invoices you are trying to delete does not exist.' );
	}

	function remove_item( $id ) {
		$response = $this->db->delete( 'items', array( 'id' => $id ) );
	}

}