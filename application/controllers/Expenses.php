<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Expenses extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'expenses' );
		$data[ 'expenses' ] = $this->Expenses_Model->get_all_expenses();
		$data[ 'expensesAmount' ] = $this->Expenses_Model->expensesTotalAmount();
		$data[ 'billed_expenses' ] = $this->Expenses_Model->billed_expenses();
		$data[ 'not_billed_expenses' ] = $this->Expenses_Model->not_billed_expenses();
		$data[ 'internal_expenses' ] = $this->Expenses_Model->internal_expenses();
		$data[ 'expenses_num' ] = $this->Expenses_Model->expenses_num();
		$data[ 'billed_expenses_num' ] = $this->Expenses_Model->billed_expenses_num();
		$data[ 'not_billed_expenses_num' ] = $this->Expenses_Model->not_billed_expenses_num();
		$data[ 'internal_expenses_num' ] = $this->Expenses_Model->internal_expenses_num();
		$data[ 'billed' ] = ( $data[ 'expenses_num' ] > 0 ? number_format( ( $data[ 'billed_expenses_num' ] * 100 ) / $data[ 'expenses_num' ] ) : 0 );
		$data[ 'not_billed' ] = ( $data[ 'expenses_num' ] > 0 ? number_format( ( $data[ 'not_billed_expenses_num' ] * 100 ) / $data[ 'expenses_num' ] ) : 0 );
		$data[ 'internal' ] = ( $data[ 'expenses_num' ] > 0 ? number_format( ( $data[ 'internal_expenses_num' ] * 100 ) / $data[ 'expenses_num' ] ) : 0 );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'expenses/index', $data );
	}

	function create() { 
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$hash = '';
			$hash = ciuis_Hash();
			$category_id = $this->input->post( 'category' );
			$customer_id = $this->input->post( 'customer' );
			$account_id = $this->input->post( 'account' );
			$title = $this->input->post( 'title' );
			$date = $this->input->post( 'date' );
			$amount = $this->input->post( 'amount' );
			$description = $this->input->post( 'description' );
			$internal = $this->input->post( 'internal' );
			$internal = ($internal == 'true')? '1' : '0';
			$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);

			$hasError = false;

			if ($title == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('title');
			} else if ($category_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
			} else if (($customer_id == '') && ($internal == '0')) {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
			} else if (($internal == '1') && ($this->input->post('staff') == '')) {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staff');
			} else if ($account_id == '') {
				$hasError = true;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
			} else if ($this->input->post('totalItems') < 1) {
				$hasError = true;
				$data['message'] = lang('invalid_expense_items');
			} else if (((int)($this->input->post('totalItems'))) == 0) {
				$hasError = true;
				$data['message'] = lang('invalid_expense_items_value');
			} else if ($total == 0) {
				$hasError = true;
				$data['message'] = lang('invalid_items_total');
			}

			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}

			if (!$hasError) {
				if ($internal == '1') {
					$staff = $this->input->post( 'staff' );
					$customer = NULL;
				} else {
					$staff = $this->session->usr_id;
					$customer = $customer_id;
				}
				$params = array(
					'hash' => $hash,
					'category_id' => $category_id,
					'staff_id' => $staff,
					'customer_id' => $customer,
					'account_id' => $account_id,
					'title' => $title,
					'number' => $this->input->post('number'),
					'date' => $date,
					'created' => date( 'Y-m-d H:i:s' ),
					'amount' => $this->input->post( 'total' ),
					'total_tax' => $this->input->post( 'total_tax' ),
					'total_discount' => $this->input->post( 'total_discount' ),
					'sub_total' => $this->input->post( 'sub_total' ),
					'description' => $description,
					'internal' => $internal,
					'last_recurring' => $date
				);
				$expenses_id = $this->Expenses_Model->create( $params );
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' )
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'expense', $expenses_id );
				}
				$template = $this->Emails_Model->get_template('expense', 'expense_created');
				if ($template['status'] == '1') {
					$expense = $this->Expenses_Model->get_expenses( $expenses_id );
					if ( $expense[ 'namesurname' ] ) {
						$customer = $expense[ 'namesurname' ];
					} else {
						$customer = $expense[ 'customer' ];
					}
					$appconfig = get_appconfig();
					$message_vars = array(
						'{customer}' => $customer,
						'{expense_number}' => $appconfig['expense_prefix'].''.str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'],
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

					$email = $expense['customeremail'] ? $expense['customeremail'] : $expense['staffemail'];
					$consultants = $this->Expenses_Model->get_consultants();
					$recipients = array();
					foreach ($consultants as $consultant) {
						$recipients[] = $consultant['email'];
					}
					$recipients[] = $email;
					if ($email) {
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
				if ( $this->input->post( 'recurring' ) == 'true'  || $this->input->post('recurring') == '1') {
					$SHXparams = array(
						'relation_type' => 'expense',
						'relation' => $expenses_id,
						'period' => $this->input->post( 'recurring_period' ),
						'end_date' => $this->input->post( 'end_recurring' ),
						'type' => $this->input->post( 'recurring_type' ),
					);
					$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
				}
				$data['success'] = true;
				$data['message'] = lang('expense'). ' ' .lang('createmessage');
				$data['id'] = $expenses_id;
				echo json_encode($data);
			}
		} else {
			$data['title'] = lang('newexpense');
			$data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
			$data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
			$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
			$this->load->view( 'inc/header', $data );
			$this->load->view( 'expenses/create', $data );
			$this->load->view( 'inc/footer', $data );
		}
	}

	function update( $id ) {
		if (!$this->session->userdata('other')) {
			$data[ 'expenses' ] = $this->Expenses_Model->get_expenses( $id );
			if ( isset( $data[ 'expenses' ][ 'id' ] ) ) {
				if ( isset( $_POST ) && count( $_POST ) > 0 ) {
					$category_id = $this->input->post( 'category' );
					$customer_id = $this->input->post( 'customer' );
					$account_id = $this->input->post( 'account' );
					$title = $this->input->post( 'title' );
					$date = $this->input->post( 'date' );
					$amount = $this->input->post( 'amount' );
					$description = $this->input->post( 'description' );
					$internal = $this->input->post( 'internal' );
					$internal = ($internal == 'true')? '1' : '0';
					$total = filter_var($this->input->post('total'), FILTER_SANITIZE_NUMBER_INT);

					$hasError = false;

					if ($title == '') {
						$hasError = true;
						$data['message'] = lang('invalidmessage'). ' ' .lang('title');
					} else if ($category_id == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('category');
					} else if (($customer_id == '') && ($internal == '0')) {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('customer');
					} else if (($internal == '1') && ($this->input->post('staff') == '')) {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('staff');
					} else if ($account_id == '') {
						$hasError = true;
						$data['message'] = lang('selectinvalidmessage'). ' ' .lang('account');
					} else if ($this->input->post('totalItems') < 1) {
						$hasError = true;
						$data['message'] = lang('invalid_expense_items');
					} else if (((int)($this->input->post('totalItems'))) == 0) {
						$hasError = true;
						$data['message'] = lang('invalid_expense_items_value');
					} else if ($total == 0) {
						$hasError = true;
						$data['message'] = lang('invalid_items_total');
					}

					if ($hasError) {
						$data['success'] = false;
						echo json_encode($data);
					}

					if (!$hasError) {
						if ($internal == '1') {
							$staff = $this->input->post( 'staff' );
							$customer = NULL;
						} else {
							$staff = $this->session->usr_id;
							$customer = $customer_id;
						}
						$params = array(
							'category_id' => $category_id,
							'staff_id' => $staff,
							'customer_id' => $customer,
							'account_id' => $account_id,
							'title' => $title,
							'number' => $this->input->post('number'),
							'date' => $date,
							'amount' => $this->input->post( 'total' ),
							'total_tax' => $this->input->post( 'total_tax' ),
							'sub_total' => $this->input->post( 'sub_total' ),
							'description' => $description,
							'internal' => $internal,
							'last_recurring' => $date
						);
						$this->Expenses_Model->update_expenses( $id, $params );
						// Custom Field Post
						if ( $this->input->post( 'custom_fields' ) ) {
							$custom_fields = array(
								'custom_fields' => $this->input->post( 'custom_fields' )
							);
							$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'expense', $id );
						}
						if ($this->input->post('recurring') == 'true'  || $this->input->post('recurring') == '1') {
							$SHXparams = array(
								'period' => $this->input->post( 'recurring_period' ),
								'end_date' => $this->input->post( 'end_recurring' ),
								'type' => $this->input->post( 'recurring_type' ),
								'status' => 0,
							);
							$recurring_invoices_id = $this->Expenses_Model->recurring_update( $id, $SHXparams );
						} else {
							$SHXparams = array(
								'period' => $this->input->post( 'recurring_period' ),
								'end_date' => $this->input->post( 'end_recurring' ),
								'type' => $this->input->post( 'recurring_type' ),
								'status' => 1,
							);
							$recurring_invoices_id = $this->Expenses_Model->recurring_update( $id, $SHXparams );
						}
						if ( !is_numeric( $this->input->post( 'recurring_id' ) ) && ($this->input->post('recurring') == 'true'  || $this->input->post('recurring') == '1') ) {
							$SHXparams = array(
								'relation_type' => 'expense',
								'relation' => $id,
								'period' => $this->input->post( 'recurring_period' ),
								'end_date' => $this->input->post( 'end_recurring' ),
								'type' => $this->input->post( 'recurring_type' ),
							);
							$recurring_invoices_id = $this->Invoices_Model->recurring_add( $SHXparams );
						}
						$this->Expenses_Model->update_pdf_status($id, '0');
						$data['success'] = true;
						$data['message'] = lang('expense'). ' '.lang('updatemessage');
						$data['id'] = $id;
						echo json_encode($data);
					}
				} else {
					$data[ 'title' ] = lang('update'). ' '. lang('expense');
					$data[ 'all_customers' ] = $this->Customers_Model->get_all_customers();
					$data[ 'all_accounts' ] = $this->Accounts_Model->get_all_accounts();
					$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
					$this->load->view( 'inc/header', $data );
					$this->load->view( 'expenses/update', $data );
					$this->load->view( 'inc/footer', $data );
				}
			} else {
				$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'error' ) );
				redirect('expenses');
			}
		} else {
			$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'you_dont_have_permission' ) );
			redirect('expenses');
		}
	}

	function receipt( $id ) {
		$data[ 'title' ] = lang( 'expense' );
		$data[ 'expenses' ] = $this->Expenses_Model->get_expenses( $id );
		if ( isset( $data[ 'expenses' ][ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'category_id' => $this->input->post( 'category' ),
					'staff_id' => $this->input->post( 'staff' ),
					'customer_id' => $this->input->post( 'customer' ),
					'account_id' => $this->input->post( 'account' ),
					'title' => $this->input->post( 'title' ),
					'date' => _pdate( $this->input->post( 'date' ) ),
					'created' => $this->input->post( 'created' ),
					'amount' => $this->input->post( 'amount' ),
					'description' => $this->input->post( 'description' ),
				);
				$this->Expenses_Model->update_expenses( $id, $params );
				redirect( 'expenses/index' );
			} else {
				$this->load->view( 'inc/header', $data );
				$this->load->view( 'expenses/receipt', $data );
				$this->load->view( 'inc/footer', $data );
			}
		} else
			show_error( 'The expenses you are trying to edit does not exist.' );
	}

	function add_file( $id ) {
		if ( isset( $id ) ) {
			if ( isset( $_POST ) ) {
				if (!is_dir('uploads/files/expenses/'.$id)) { 
					mkdir('./uploads/files/expenses/'.$id, 0777, true);
				}
				$config[ 'upload_path' ] = './uploads/files/expenses/'.$id.'';
				$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|gif|pdf|doc|docx|xls|xlsx|txt|csv|ppt|opt';
				$config['max_size'] = '9000';
				$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["file_name"]['name']));
				$config['file_name'] = $new_name;
				$this->load->library( 'upload', $config );
				$this->upload->do_upload( 'file_name' );
				$data_upload_files = $this->upload->data();
				$image_data = $this->upload->data();
				if (is_file('./uploads/files/expenses/'.$id.'/'.$image_data[ 'file_name' ])) {
					$params = array(
						'relation_type' => 'expense',
						'relation' => $id,
						'file_name' => $image_data[ 'file_name' ],
						'created' => date( " Y.m.d H:i:s " ),
					);
					$this->db->insert( 'files', $params );
				}
				$this->Expenses_Model->update_pdf_status($id, '0');
				redirect( 'expenses/receipt/' . $id . '' );
			}
		}
	}

	function delete_file($id) {
		if (isset($id)) {
			$fileData = $this->Expenses_Model->get_file($id);
			$response = $this->db->where( 'id', $id )->delete( 'files', array( 'id' => $id ) );
			if (is_file('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name'])) {
	    		unlink('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name']);
	    	}
	    	$this->Expenses_Model->update_pdf_status($fileData['relation'], '0');
	    	if ($response) {
	    		$data['success'] = true;
	    		$data['message'] = lang('file'). ' '.lang('deletemessage');
	    	} else {
	    		$data['success'] = false;
	    		$data['message'] = lang('errormessage');
	    	}
	    	echo json_encode($data);
		} else {
			redirect('expenses');
		}
	}

	function download_file($id) {
		if (isset($id)) {
			$fileData = $this->Expenses_Model->get_file( $id );
			if (is_file('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name'])) {
	    		$this->load->helper('file');
	    		$this->load->helper('download');
	    		$data = file_get_contents('./uploads/files/expenses/'.$fileData['relation'].'/' . $fileData['file_name']);
	    		force_download($fileData['file_name'], $data);
	    	} else {
	    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
	    		redirect('expenses/receipt/'.$fileData['relation']);
	    	}
		}
	}

	function download_pdf($id) { 
		$expense = $this->Expenses_Model->get_file( $id );
		if (isset($id)) {
			$appconfig = get_appconfig();
			$file_name = '' . $appconfig['expense_prefix'] . '' . str_pad( $id, 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'] . '.pdf';
			if (is_file('./uploads/files/expenses/'.$id.'/' . $file_name)) {
	    		$this->load->helper('file');
	    		$this->load->helper('download');
	    		$data = file_get_contents('./uploads/files/expenses/'.$id.'/' . $file_name);
	    		force_download($file_name, $data);
	    	} else {
	    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
	    		redirect('expenses/receipt/'.$id);
	    	}
		} else {
			redirect('expenses/receipt/'.$id);
		}
	}

	function create_pdf( $id ) {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/expenses/'.$id)) {
			mkdir('./uploads/files/expenses/'.$id, 0777, true);
		}
		$data[ 'expense' ] = $this->Expenses_Model->all_expenses( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
			$files = $this->Expenses_Model->get_files( $id );
			$images = array();
			$otherFiles = array();
			foreach ($files as $file) {
				$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$display = true;
					$images[] = array(
						'id' => $file['id'],
						'expense_id' => $file['relation'],
						'file_name' => $file['file_name'],
						'created' => $file['created'],
						'display' => $display,
						'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
					);
				} else {
					$display = false;
					$otherFiles[] = array(
						'id' => $file['id'],
						'expense_id' => $file['relation'],
						'file_name' => $file['file_name'],
						'created' => $file['created'],
						'display' => $display,
						'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
					);
				}
			}
		$data['images'] = $images;
		$data['otherFiles'] = $otherFiles;
		$this->load->view( 'expenses/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['expense_prefix'] . '-' . str_pad( $id, 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'] . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option('isRemoteEnabled', TRUE );
		$this->dompdf->setPaper('A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output ); 
		$this->Expenses_Model->update_pdf_status($id, '1');
		if ($output) {
			redirect(base_url('expenses/pdf_generated/'.$file_name.''));
		} else {
			redirect( base_url('expenses/pdf_fault/'));
		}
	}

	function print_pdf( $id ) {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/expenses/'.$id)) {
			mkdir('./uploads/files/expenses/'.$id, 0777, true);
		}
		$data[ 'expense' ] = $this->Expenses_Model->all_expenses( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
			$files = $this->Expenses_Model->get_files( $id );
			$images = array();
			$otherFiles = array();
			foreach ($files as $file) {
				$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$display = true;
					$images[] = array(
						'id' => $file['id'],
						'expense_id' => $file['relation'],
						'file_name' => $file['file_name'],
						'created' => $file['created'],
						'display' => $display,
						'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
					);
				} else {
					$display = false;
					$otherFiles[] = array(
						'id' => $file['id'],
						'expense_id' => $file['relation'],
						'file_name' => $file['file_name'],
						'created' => $file['created'],
						'display' => $display,
						'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
					);
				}
			}
		$data['images'] = $images;
		$data['otherFiles'] = $otherFiles;
		$this->load->view( 'expenses/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['expense_prefix'] . '' . str_pad( $id, 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'] . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option('isRemoteEnabled', TRUE );
		$this->dompdf->setPaper('A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output );
		if ($output) {
			$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
		} else {
			redirect( base_url( 'expenses/pdf_fault/' ) );
		}
	}

	function generate_pdf($id) {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/expenses/'.$id)) {
			mkdir('./uploads/files/expenses/'.$id, 0777, true);
		}
		$data[ 'expense' ] = $this->Expenses_Model->all_expenses( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
			$files = $this->Expenses_Model->get_files( $id );
			$images = array();
			$otherFiles = array();
			foreach ($files as $file) {
				$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$display = true;
					$images[] = array(
						'id' => $file['id'],
						'expense_id' => $file['relation'],
						'file_name' => $file['file_name'],
						'created' => $file['created'],
						'display' => $display,
						'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
					);
				} else {
					$display = false;
					$otherFiles[] = array(
						'id' => $file['id'],
						'expense_id' => $file['relation'],
						'file_name' => $file['file_name'],
						'created' => $file['created'],
						'display' => $display,
						'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
					);
				}
			}
		$data['images'] = $images;
		$data['otherFiles'] = $otherFiles;
		$this->load->view( 'expenses/pdf', $data );
		$appconfig = get_appconfig();
		$file_name = '' . $appconfig['expense_prefix'] . '' . str_pad( $id, 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'] . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option('isRemoteEnabled', TRUE );
		$this->dompdf->setPaper('A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output ); 
		$this->Expenses_Model->update_pdf_status($id, '1');
		return true;
		//redirect(base_url('expenses/pdf_generates/'.$file_name.''));
	}

	function send_expense_email($id) {
		$template = $this->Emails_Model->get_template('expense', 'expense_created');
		$expense = $this->Expenses_Model->get_expenses( $id );
		$path = '';
		if ($template['attachment'] == '1') {
			$appconfig = get_appconfig();
			if ($expense['pdf_status'] == '0') {
				$this->Expenses_Model->generate_pdf($id);
				$file = $appconfig['expense_prefix'].''.str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'];
				$path = base_url('uploads/files/expenses/'.$id.'/'.$file.'.pdf');
			} else {
				$file = $appconfig['expense_prefix'].''.str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'];
				$path = base_url('uploads/files/expenses/'.$id.'/'.$file.'.pdf');
			}
		}
		$customer = '';
		if ($expense[ 'namesurname' ] || $expense[ 'customer' ]) {
			if ( $expense[ 'namesurname' ] ) {
				$customer = $expense[ 'namesurname' ];
			} else {
				$customer = $expense[ 'customer' ];
			}
		}
		$message_vars = array(
			'{customer}' => $customer,
			'{expense_number}' => $appconfig['expense_prefix'].''.str_pad( $expense['id'], 6, '0', STR_PAD_LEFT).$appconfig['expense_suffix'],
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

		$email = $expense['customeremail'] ? $expense['customeremail'] : $expense['staffemail'];
		if ($email) {
			$param = array(
				'from_name' => $template['from_name'],
				'email' => $email,
				'subject' => $subject,
				'message' => $message,
				'created' => date( "Y.m.d H:i:s" ),
				'status' => 0,
				'attachments' => $path?$path:NULL
			);
			$this->load->library('mail'); 
			$data = $this->mail->send_email($email, $template['from_name'], $subject, $message, $path);
			if ($data['success'] == true) {
				$return['status'] = true;
				$return['message'] = $data['message'];
				$this->db->insert( 'email_queue', $param );
				echo json_encode($return);
			} else {
				$return['status'] = false;
				$return['message'] = lang('errormessage');
				echo json_encode($return);
			}
		}
	}

	function pdf_generates( $file ) {
		return true;
	}

	function pdf_generated( $file ) {
		$result = array(
			'status' => true,
			'file_name' => $file,
		);
		echo json_encode( $result );
	}

	function files($id) {
		if (isset($id)) {
			$files = $this->Expenses_Model->get_files( $id );
			$data = array();
			foreach ($files as $file) {
				$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
				$type = 'file';
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$type = 'image';
				}
				if ($ext == 'pdf') {
					$type = 'pdf';
				}
				if ($ext == 'zip' || $ext == 'rar' || $ext == 'tar') {
					$type = 'archive';
				}
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$display = true;
				} else {
					$display = false;
				}
				if ($ext == 'pdf') {
					$pdf = true;
				} else {
					$pdf = false;
				}
				$data[] = array(
					'id' => $file['id'],
					'expense_id' => $file['relation'],
					'file_name' => $file['file_name'],
					'created' => $file['created'],
					'display' => $display,
					'pdf' => $pdf,
					'type' => $type,
					'path' => base_url('uploads/files/expenses/'.$id.'/'.$file['file_name']),
				);
			}
			echo json_encode($data);
		}
	}

	function convert( $id ) {
		$expenses = $this->Expenses_Model->get_expenses( $id );
		if ( isset( $id ) ) {
			$params = array(
				'staff_id' => $expenses[ 'staff_id' ],
				'customer_id' => $expenses[ 'customer_id' ],
				'created' => date( 'Y-m-d H:i:s' ),
				'status_id' => 3,
				'total' => $expenses[ 'amount' ],
				'sub_total' => $expenses[ 'sub_total' ],
				'total_discount' => $expenses[ 'total_discount' ],
				'total_tax' => $expenses[ 'total_tax' ],
				'serie' => $expenses[ 'number' ],
				'expense_id' => $id,
				'sub_total' => $expenses[ 'amount' ],
			);
			$this->db->insert( 'invoices', $params );
			$invoice = $this->db->insert_id();
			$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
			foreach ($items as $item) {
				$this->db->insert( 'items', array(
					'relation' => $invoice,
					'relation_type' => 'invoice',
					'name' => $item[ 'name' ],
					'total' => $item[ 'total' ],
					'price' => $item[ 'price' ],
					'quantity' => $item[ 'quantity' ],
					'unit' => $item[ 'unit' ],
					'description' => $item[ 'description' ],
				));
			}
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( $this->db->dbprefix . 'sales', array(
				'invoice_id' => '' . $invoice . '',
				'status_id' => 3,
				'staff_id' => $loggedinuserid,
				'customer_id' => $expenses[ 'customer_id' ],
				'total' => $expenses[ 'amount' ],
				'date' => date( 'Y-m-d H:i:s' )
			));
			$staffname = $this->session->staffname;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' ),
				'detail' => ( '' . $message = sprintf( lang( 'expensetoinvoicelog' ), $staffname, $expenses[ 'id' ] ) . '' ),
				'staff_id' => $loggedinuserid,
				'customer_id' => $expenses[ 'customer_id' ],
			) );
			$response = $this->db->where( 'id', $id )->update( 'expenses', array( 'invoice_id' => $invoice ) );
			echo $invoice;
		}
	}

	function remove( $id ) {
		$expenses = $this->Expenses_Model->get_expenses( $id );
		if ( isset( $expenses[ 'id' ] ) ) {
			$this->Expenses_Model->delete_expenses( $id );
			$this->load->helper('file');
			$folder = './uploads/files/expenses/'.$id;
			delete_files($folder, true);
			rmdir($folder);
			$this->session->set_flashdata( 'ntf1', lang('expense').' '.lang('deletemessage'));
			redirect( 'expenses/index' );
		} else
			show_error( 'The expenses you are trying to delete does not exist.' );
	}

	function add_category() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
				'description' => $this->input->post( 'description' ),
			);
			$category = $this->Expenses_Model->add_category( $params );
			$data['message'] = lang('expensecategory'). ' '. lang('createmessage');
			echo json_encode($data);
		}
	}

	function update_category( $id ) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
				'description' => $this->input->post( 'description' ),
			);
			$this->Expenses_Model->update_category( $id, $params );
		}
	}

	function remove_category( $id ) {
		$expensecategory = $this->Expenses_Model->get_expensecategory( $id );
		if ( isset( $expensecategory[ 'id' ] ) ) {
			$this->Expenses_Model->delete_category( $id );
		} else
			show_error( 'The expensecategory you are trying to delete does not exist.' );
	}

}