<?php
class Expenses_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	/* Get Expense by ID */
	
	function get_expenses( $id ) {
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,customers.email as customeremail,accounts.name as account,expensecat.name as category,staff.staffname as staff,expenses.description as desc, expenses.id as id, expenses.staff_id as staff_id, staff.email as staffemail' );
		$this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
		$this->db->join( 'accounts', 'expenses.account_id = accounts.id', 'left' );
		$this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
		$this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
		$this->db->order_by( 'expenses.id', 'desc' );
		return $this->db->get_where( 'expenses', array( 'expenses.id' => $id ) )->row_array();
	}

	function all_expenses( $id ) {
		$this->db->select( '*,expenses.created,customers.company as customer,customers.type as type,customers.namesurname as individual,customers.email as customeremail,customers.phone as customer_phone, accounts.name as account,expensecat.name as category,staff.staffname as staff,staff.id as staff_id, expenses.description as desc, expenses.id as id, recurring.id as recurring_id, recurring.status as recurring_status, recurring.relation_type as recurring_relation_type, recurring.period as recurring_period, recurring.type as recurring_type, recurring.end_date as recurring_endDate, customers.billing_street, customers.billing_city, customers.billing_state, customers.billing_zip, customers.taxoffice as customer_tax, customers.taxnumber as customer_taxnum' );
		$this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
		$this->db->join( 'accounts', 'expenses.account_id = accounts.id', 'left' );
		$this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
		$this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
		$this->db->join( 'recurring', 'expenses.id = recurring.relation AND recurring.relation_type = "expense"', 'left' );
		$this->db->order_by( 'expenses.id', 'desc' );
		return $this->db->get_where( 'expenses', array( 'expenses.id' => $id ) )->row_array();
	}

	function generate_pdf($id) {
		ini_set('max_execution_time', 0); 
		ini_set('memory_limit','2048M');
		if (!is_dir('uploads/files/expenses/'.$id)) {
			mkdir('./uploads/files/expenses/'.$id, 0777, true);
		}
		$data[ 'expense' ] = $this->all_expenses( $id );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
			$files = $this->get_files( $id );
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
		$file_name = '' . $appconfig['expense_prefix'] . '-' . str_pad( $id, 6, '0', STR_PAD_LEFT ). $appconfig['expense_suffix'] . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option('isRemoteEnabled', TRUE );
		$this->dompdf->setPaper('A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'uploads/files/expenses/'.$id.'/' . $file_name . '', $output ); 
		$this->update_pdf_status($id, '1');
		return true;
	}

	function get_consultants() {
		$this->db->select( '*' );
		$this->db->order_by('id', 'asc');
		return $this->db->get_where( 'staff', array( 'other' => '1', 'inactive =' => NULL ) )->result_array();
	}

	function get_all_recurring() { 
		$this->db->select( '*' );
		$this->db->order_by('id', 'asc');
		return $this->db->get_where( 'recurring', array( 'status' => '0', 'relation_type' => 'expense' ) )->result_array();
	}

	function update_recurring_date($id) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'expenses', array('last_recurring' => date('Y-m-d')) );
	}

	function recurring_expense( $params, $items ) {
		$this->db->insert( 'expenses', $params );
		$expense = $this->db->insert_id();
		$loggedinuserid = 0;
		$this->db->insert( 'payments', array(
			'transactiontype' => 1,
			'is_transfer' => 0,
			'expense_id' => $expense,
			'staff_id' => $loggedinuserid,
			'amount' => $params['amount'],
			'account_id' => $params['account_id'],
			'customer_id' => $params['customer_id']?$params['customer_id']:0,
			'not' => 'Outgoings for <a href="' . base_url( 'expenses/receipt/' . $expense . '' ) . '">EXP-' . $expense . '</a>',
			'date' => _pdate( date('Y-m-d') ),
		) );
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'expense',
				'relation' => $expense,
				'product_id' => $item[ 'product_id' ],
				'code' => $item[ 'code' ],
				'name' => $item[ 'name' ],
				'description' => $item[ 'description' ],
				'quantity' => $item[ 'quantity' ],
				'unit' => $item[ 'unit' ],
				'price' => $item[ 'price' ],
				'tax' => $item[ 'tax' ],
				'discount' => $item[ 'discount' ],
				'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
			));
			$i++;
		};
		//LOG
		$staffname = lang( 'recurring_expense' );
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'addedanewexpense' ) . ' <a href="expenses/receipt/' . $expense . '">' . $appconfig['expense_prefix'] . '' . $expense.$appconfig['expense_suffix'] . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
		return $expense;
	}

	// All Expenses Count
	
	function get_all_expenses_count() {
		$this->db->from( 'expenses' );
		return $this->db->count_all_results();
	}

	function get_items_invoices( $id ) {
		$this->db->select_sum( 'total' );
		$this->db->from( 'items' );
		$this->db->where( '(relation_type = "expense" AND relation = ' . $id . ')' );
		return $this->db->get();
	}

	function get_files($id) {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'files', array( 'relation' => $id, 'relation_type' => 'expense' ) )->result_array();
	}

	function get_file($id) {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'files', array( 'id' => $id) )->row_array();
	}
	
	/* Get All Expenses */
	
	function get_all_expenses() {
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,expensecat.name as category,staff.staffname as staff,expenses.description as desc, expenses.id as id' );
		$this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
		$this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
		$this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
		$this->db->order_by( 'expenses.id', 'desc' );
		return $this->db->get( 'expenses' )->result_array();
	}
	
	function get_all_expenses_by_relation($relation_type,$relation_id) {
		$this->db->select( '*,customers.company as customer,customers.type as type,customers.namesurname as individual,expensecat.name as category,staff.staffname as staff,expenses.description as desc, expenses.id as id' );
		$this->db->join( 'customers', 'expenses.customer_id = customers.id', 'left' );
		$this->db->join( 'expensecat', 'expenses.category_id = expensecat.id', 'left' );
		$this->db->join( 'staff', 'expenses.staff_id = staff.id', 'left' );
		$this->db->order_by( 'expenses.id', 'desc' );
		return $this->db->get_where( 'expenses', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
	}
	
	// Function to add new expenses
	
	function create( $params ) {
		$this->db->insert( 'expenses', $params );
		$expense = $this->db->insert_id();
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'payments', array(
			'transactiontype' => 1,
			'is_transfer' => 0,
			'expense_id' => $expense,
			'staff_id' => $loggedinuserid,
			'amount' => $this->input->post( 'total' ),
			'account_id' => $this->input->post( 'account' ),
			'customer_id' => $this->input->post( 'customer' ),
			'not' => 'Outgoings for <a href="' . base_url( 'expenses/receipt/' . $expense . '' ) . '">EXP-' . $expense . '</a>',
			'date' => _pdate( $this->input->post( 'date' ) ),
		) );
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			$this->db->insert( 'items', array(
				'relation_type' => 'expense',
				'relation' => $expense,
				'product_id' => $item[ 'product_id' ],
				'code' => $item[ 'code' ],
				'name' => $item[ 'name' ],
				'description' => $item[ 'description' ],
				'quantity' => $item[ 'quantity' ],
				'unit' => $item[ 'unit' ],
				'price' => $item[ 'price' ],
				'tax' => $item[ 'tax' ],
				'discount' => $item[ 'discount' ],
				'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
			));
			$i++;
		};
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'addedanewexpense' ) . ' <a href="expenses/receipt/' . $expense . '">' . $appconfig['expense_prefix'] . '' . $expense.$appconfig['expense_suffix'] . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
		return $expense;
	}

	function recurring_update( $id, $params ) {
		$this->db->where( 'relation', $id )->where( 'relation_type', 'expense' );
		$sharax = $this->db->update( 'recurring', $params );
		return $sharax;
	}

	function update_pdf_status($id, $value) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'expenses', array('pdf_status' => $value));
	}

	// Function to update expenses
	
	function update_expenses( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'expenses', $params );
		$items = $this->input->post( 'items' );
		$i = 0;
		foreach ( $items as $item ) {
			if ( isset( $item[ 'id' ] ) ) {
				$params = array(
					'relation_type' => 'expense',
					'relation' => $id,
					'product_id' => $item[ 'product_id' ],
					'code' => $item[ 'code' ],
					'name' => $item[ 'name' ],
					'description' => $item[ 'description' ],
					'quantity' => $item[ 'quantity' ],
					'unit' => $item[ 'unit' ],
					'price' => $item[ 'price' ],
					'tax' => $item[ 'tax' ],
					'discount' => $item[ 'discount' ],
					'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
				);
				$this->db->where( 'id', $item[ 'id' ] );
				$response = $this->db->update( 'items', $params );
			}
			if ( empty( $item[ 'id' ] ) ) {
				$this->db->insert( 'items', array(
					'relation_type' => 'expense',
					'relation' => $id,
					'product_id' => $item[ 'product_id' ],
					'code' => $item[ 'code' ],
					'name' => $item[ 'name' ],
					'description' => $item[ 'description' ],
					'quantity' => $item[ 'quantity' ],
					'unit' => $item[ 'unit' ],
					'price' => $item[ 'price' ],
					'tax' => $item[ 'tax' ],
					'discount' => $item[ 'discount' ],
					'total' => $item[ 'quantity' ] * $item[ 'price' ] + ( ( $item[ 'tax' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ) - ( ( $item[ 'discount' ] ) / 100 * $item[ 'quantity' ] * $item[ 'price' ] ),
				) );
			}
			$i++;
		};
		$response = $this->db->where( 'expense_id', $id )->update( 'payments', array(
			'transactiontype' => 1,
			'amount' => $this->input->post( 'amount' ),
			'account_id' => $this->input->post( 'account' ),
			'customer_id' => $this->input->post( 'customer' ),
			'not' => 'Payment for <a href="' . base_url( 'expenses/edit/' . $id . '' ) . '">EXP-' . $id . '</a>',
			'date' => _pdate( $this->input->post( 'date' ) ),
		) );
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updated' ) . ' <a href="expenses/receipt/' . $id . '">' . $appconfig['expense_prefix'] . '' . $id.$appconfig['expense_suffix'] . '</a>.' ),
			'staff_id' => $loggedinuserid,
			'customer_id' => $this->input->post( 'customer' )
		) );
	}

	// Function to delete expenses
	
	function delete_expenses( $id ) {
		$response = $this->db->delete( 'expenses', array( 'id' => $id ) );
		$response = $this->db->delete( 'payments', array( 'expense_id' => $id ) );
		$response = $this->db->delete( 'sales', array( 'invoice_id' => $id ) );
		$response = $this->db->delete( 'files', array( 'relation' => $id, 'relation_type' => 'expense' ) );
		// LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$appconfig = get_appconfig();
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'deleted' ) . ' ' . $appconfig['expense_prefix'] . '' . $id .$appconfig['expense_suffix']. '' ),
			'staff_id' => $loggedinuserid
		) );
	}

	function get_expensecategory( $id ) {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'expensecat', array( 'id' => $id ) )->row_array();
	}

	/* Get All Expense Categories */

	function get_all_expensecat() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get( 'expensecat' )->result_array();
	}

	/* Add Expense Category */

	function add_category( $params ) {
		$this->db->insert( 'expensecat', $params );
		return $this->db->insert_id();
	}

	/* Update Expense Category */

	function update_category( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'expensecat', $params );
	}

	/* Delete Expense Category */

	function delete_category( $id ) {
		return $this->db->delete( 'expensecat', array( 'id' => $id ) );
	}

	function expensesTotalAmount() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function billed_expenses() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$this->db->where(array('internal' => 0, 'invoice_id !=' => NULL));
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function not_billed_expenses() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$this->db->where(array('internal' => 0, 'invoice_id' => NULL));
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function internal_expenses() {
		$this->db->select_sum( 'amount' );
		$this->db->from( 'expenses' );
		$this->db->where(array('internal' => 1));
		$total_value = $this->db->get()->row()->amount;
		if ( !empty( $total_value ) ) {
			$total = $total_value;
		} else {
			$total = 0;
		}
		return $total;
	}

	function expenses_num() {
		$this->db->from( 'expenses' );
		return $this->db->get()->num_rows();
	}

	function billed_expenses_num() {
		$this->db->from( 'expenses' );
		$this->db->where(array('internal' => 0, 'invoice_id !=' => NULL));
		return $this->db->get()->num_rows();
	}

	function not_billed_expenses_num() {
		$this->db->from( 'expenses' );
		$this->db->where(array('internal' => 0, 'invoice_id' => NULL));
		return $this->db->get()->num_rows();
	}

	function internal_expenses_num() {
		$this->db->from( 'expenses' );
		$this->db->where(array('internal' => 1));
		return $this->db->get()->num_rows();
	}
}