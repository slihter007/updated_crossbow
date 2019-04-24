<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Gateway extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library( "Omni" );
		$this->lang->load( 'english', 'english' );
		$this->load->model( 'Invoices_Model' );
		$this->load->model( 'Customers_Model' );
		$this->load->model( 'Settings_Model' );
	}

	function success() {
		$this->load->view( 'gateway/success' );

	}

	function cancel() {
		$this->load->view( 'gateway/cancel' );
	}

	function paypal_ipn( $token ) {
		$settings = $this->Settings_Model->get_settings_ciuis();
		if ( isset( $token ) ) { 
			$payment = $this->Settings_Model->get_payment_gateway_data();
				$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
				$response = $this->db->where( 'id', $invoice['id'] )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
				$response = $this->db->where( 'invoice_id', $invoice['invoice_id'] )->update( 'sales', array(
					'status_id' => 2,
					'staff_id' => $invoice[ 'staff_id' ],
					'customer_id' => $invoice[ 'customer_id' ],
					'total' => $invoice['amount'],
				));
				$this->db->insert( 'payments', array(
					'transactiontype' => 0,
					'invoice_id' => $invoice['id'],
					'staff_id' => $invoice['staff_id'],
					'amount' => $invoice[ 'total' ],
					'customer_id' => $invoice['customer_id'],
					'account_id' => $payment['paypal_payment_record_account'],
					'not' => '' . lang('paymentfor').' '.lang('invoice').' '. $invoice['id'] . '',
					'mode' => lang('paypal'),
					'date' => date('Y-m-d H:i:s'),
				));
				
			// $invoice = $this->Invoices_Model->get_invoices_by_token( $token );
			// $response = $this->db->where( 'id', $invoice['id'] )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			// $response = $this->db->where( 'invoice_id', $invoice['id'] )->update( 'sales', array(
			// 	'status_id' => 2,
			// 	'staff_id' => $invoice[ 'staff_id' ],
			// 	'customer_id' => $invoice[ 'customer_id' ],
			// 	'total' => $invoice[ 'total' ],
			// ) );
			// $this->db->insert( 'payments', array(
			// 	'transactiontype' => 0,
			// 	'invoice_id' => $invoice[ 'id' ],
			// 	'staff_id' => $invoice[ 'staff_id' ],
			// 	'amount' => $invoice[ 'total' ],
			// 	'customer_id' => $invoice[ 'customer_id' ],
			// 	'account_id' => $settings[ 'authorize_record_account' ],
			// 	'not' => '' . $message = sprintf( lang( 'paymentfor' ), $invoice['id'] ) . '',
			// 	'mode' => lang('paypal'),
			// 	'date' => date( 'Y-m-d H:i:s' ),
			// ));
			redirect( 'gateway/success' );
		}
	}

	function paypal( $token ) {
		//Set variables for paypal form
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		$returnURL = base_url( 'gateway/paypal_ipn/' . $invoice[ 'token' ] . '' );
		$cancelURL = base_url( 'gateway/cancel' );
		$notifyURL = base_url( 'pay/ipn' ); //ipn url
		$userID = 1; //current user id
		$invoiceno = 'INV-' . str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ) . '';
		$logo = base_url() . 'assets/img/logo.png'; 
		$this->omni->add_field( 'return', $returnURL );
		$this->omni->add_field( 'cancel_return', $cancelURL );
		$this->omni->add_field( 'notify_url', $notifyURL );
		$this->omni->add_field( 'item_name', $invoiceno );
		$this->omni->add_field( 'custom', $userID );
		$this->omni->add_field( 'item_number', str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ) );
		$this->omni->add_field( 'amount', $invoice[ 'total' ] );
		$this->omni->image( $logo );
		$this->omni->paypal_auto_form();
	}

	function razorpay($token) {
		$appconfig = get_appconfig();
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		if ($invoice) {
			$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
			if ($data['payment']['razorpay_active'] === TRUE) {
				//$this->load->library( "Razorpay" );
				$settings = $this->Settings_Model->get_settings_ciuis();
				$data['crm_name'] = $settings['crm_name'];
				$data['logo'] = base_url('uploads/ciuis_settings/'.$settings['logo']);
				$data['customer'] = $this->Customers_Model->get_customers( $invoice[ 'customer_id' ] );
				$data['invoice_id'] = sprintf( lang( 'paymentfor' ), str_pad( $invoice['id'], 6, '0', STR_PAD_LEFT ) );
				$data['currency'] = $this->Settings_Model->get_currency();
				$data['invoice'] = $invoice;
				$data['cust_name'] = ($data['customer']['type']===1)?$data['customer']['namesurname']:$data['customer']['company'];
				$data['invoice']['inv_id'] = $appconfig['inv_prefix'] . '' .$invoice['id'].$appconfig['inv_suffix'];
				$this->load->view( 'gateway/razorpay', $data );
			} else {
				redirect(base_url('panel'));
			}
		}
	}

	function pay_razorpay() {
		$data = $this->input->post();
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		if ($data['payment']['razorpay_active'] === TRUE) {
			$this->load->library("razorpay");
			$pay = $this->razorpay->razorpay_success($data);
			if ($pay === true) {
				$payment = $data['payment'];
				$invoice = $this->Invoices_Model->get_invoices( $data['invoice_id'] );
				$response = $this->db->where( 'id', $data['invoice_id'] )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
				$response = $this->db->where( 'invoice_id', $data['invoice_id'] )->update( 'sales', array(
					'status_id' => 2,
					'staff_id' => $invoice[ 'staff_id' ],
					'customer_id' => $invoice[ 'customer_id' ],
					'total' => $data['amount'],
				));
				$this->db->insert( 'payments', array(
					'transactiontype' => 0,
					'invoice_id' => $invoice['id'],
					'staff_id' => $invoice['staff_id'],
					'amount' => $data['amount'],
					'customer_id' => $invoice['customer_id'],
					'account_id' => $payment['razorpay_payment_record_account'],
					'not' => '' . lang('paymentfor').' '.lang('invoice').' '. $invoice['id'] . '',
					'mode' => lang('razorpay'),
					'date' => date('Y-m-d H:i:s'),
				));
				redirect(base_url('gateway/success'));
			} else {
				redirect(base_url('gateway/cancel'));
			}
		} else {
			redirect(base_url('area/panel'));
		}
	}

	function stripe($token) {
		$appconfig = get_appconfig();
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		if ($invoice) {
			$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
			if ($data['payment']['stripe_active'] === TRUE) {
				$settings = $this->Settings_Model->get_settings_ciuis();
				$data['crm_name'] = $settings['crm_name'];
				$data['logo'] = base_url('uploads/ciuis_settings/'.$settings['logo']);
				$data['customer'] = $this->Customers_Model->get_customers( $invoice[ 'customer_id' ] );
				$data['invoice_id'] = sprintf( lang( 'paymentfor' ), str_pad( $invoice['id'], 6, '0', STR_PAD_LEFT ) );
				$data['currency'] = $this->Settings_Model->get_currency();
				$data['invoice'] = $invoice;
				$data['invoice']['inv_id'] = $appconfig['inv_prefix'] . '' .$invoice['id'].$appconfig['inv_suffix'];
				$this->load->view( 'gateway/stripe', $data );
			} else {
				redirect(base_url('panel'));
			}
		}
	}

	public function stripe_success() {
		$data = $this->input->post();
		// echo '<script>console.log('.json_encode($data).')</script>';
		$response = $this->omni->stripe_success($data);
		if ($response === true) {
			$payment = $this->Settings_Model->get_payment_gateway_data();
			$invoice = $this->Invoices_Model->get_invoices( $data['invoice_id'] );
			$response = $this->db->where( 'id', $data['invoice_id'] )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			$response = $this->db->where( 'invoice_id', $data['invoice_id'] )->update( 'sales', array(
				'status_id' => 2,
				'staff_id' => $invoice[ 'staff_id' ],
				'customer_id' => $invoice[ 'customer_id' ],
				'total' => $data['amount'],
			) );
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'invoice_id' => $invoice['id'],
				'staff_id' => $invoice['staff_id'],
				'amount' => $data['amount'],
				'customer_id' => $invoice['customer_id'],
				'account_id' => $payment['stripe_record_account'],
				'not' => '' . lang('paymentfor').' '.lang('invoice').' '. $invoice['id'] . '',
				'mode' => lang('stripe'),
				'date' => date('Y-m-d H:i:s'),
			));
			redirect(base_url('gateway/success'));
		} else {
			redirect(base_url('gateway/cancel'));
		}
	}

	public function authorize( $token ) {
		$data[ 'invoice' ] = $this->Invoices_Model->get_invoices_by_token( $token );
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		$data[ 'customer' ] = $this->Customers_Model->get_customers( $invoice[ 'customer_id' ] );
		$this->load->view( 'gateway/authorize/authorize', $data ); 
	}

	public function pushPayment() { 
		$settings = $this->Settings_Model->get_settings_ciuis();
		$payment = $this->Settings_Model->get_payment_gateway_data();
		$dataCustomers = array( "fname" => $this->input->post( 'fname' ),
			"lname" => $this->input->post( 'lname' ),
			"address" => $this->input->post( 'address' ),
			"city" => $this->input->post( 'city' ),
			"state" => $this->input->post( 'state' ),
			"country" => $this->input->post( 'country' ),
			"zip" => $this->input->post( 'zip' ),
			"phone" => $this->input->post( 'phone' ),
			"email" => $this->input->post( 'email' ),
			"cnumber" => $this->input->post( 'cnumber' ),
			"cexpdate" => $this->input->post( 'cexpdate' ),
			"ccode" => $this->input->post( 'ccode' ),
			"cdesc" => $this->input->post( 'cdesc' ),
			"amount" => $this->input->post( 'camount' ),
			"inv_id" => $this->input->post( 'inv_id' ) );
		$result = $this->omni->chargerCreditCard( $dataCustomers );
		if ( $result ) {
			$data[ 'authorize_result' ] = $result;
			$invoice = $this->Invoices_Model->get_invoices( $this->input->post( 'inv_id' ) );
			$response = $this->db->where( 'id', $this->input->post( 'inv_id' ) )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			$response = $this->db->where( 'invoice_id', $this->input->post( 'inv_id' ) )->update( 'sales', array(
				'status_id' => 2,
				'staff_id' => $invoice[ 'staff_id' ],
				'customer_id' => $invoice[ 'customer_id' ],
				'total' => $this->input->post( 'camount' ),
			) );
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'invoice_id' => $invoice[ 'id' ],
				'staff_id' => $invoice[ 'staff_id' ],
				'amount' => $this->input->post( 'camount' ),
				'customer_id' => $invoice[ 'customer_id' ],
				'account_id' => $payment[ 'authorize_aim_payment_record_account' ],
				'not' => '' . $message = sprintf( lang( 'paymentfor' ), $invoice['id'] ) . '',
				'mode' => lang('authorize_aim'),
				'date' => date( 'Y-m-d H:i:s' ),
			) );
			redirect( 'gateway/success' );
		} else {
			redirect( 'gateway/cancel' );
		}
	}

	function payumoney($token) {
		$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		if ($invoice) {
			$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
			if ($data['payment']['payu_money_active'] === TRUE) {
				$data[ 'customer' ] = $this->Customers_Model->get_customers( $invoice[ 'customer_id' ] );
				$data['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
				$data['hash'] = $this->get_invoice_hash([
	                'key' => $data['payment']['payu_money_key'],
	                'txnid' => $data['txnid'],
	                'amount' => $invoice[ 'total' ],
	                'phone' => $data[ 'customer' ]['phone'],
	                'productinfo' => 'Invoice Payment',
	                'firstname' => $data[ 'customer' ]['namesurname']?$data[ 'customer' ]['namesurname']:$data[ 'customer' ]['company'],
	                'email' => $data[ 'customer' ]['email'],
	                'udf1' => $invoice[ 'id' ]
	            ], $data['payment']['payu_money_salt']);
				$data['payu']['url'] = "https://secure.payu.in/_payment";
				$hash_string = $data['payment']['payu_money_salt'];
				$data['hash'] = strtolower(hash('sha512', $hash_string));
				$data['invoice_id'] = sprintf( lang( 'paymentfor' ), str_pad( $invoice['id'], 6, '0', STR_PAD_LEFT ) );
				$data['currency'] = $this->Settings_Model->get_currency();
				$data['invoice'] = $invoice;
				$this->load->view( 'gateway/payumoney', $data ); 
			}
		}
	}

	function payumoney_success() {
		$data = $this->input->post();
		//$response = $this->omni->stripe_success($data);
		if ($data) {
			$payment = $this->Settings_Model->get_payment_gateway_data();
			$invoice = $this->Invoices_Model->get_invoices( $data['invoice_id'] );
			$response = $this->db->where( 'id', $data['invoice_id'] )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			$response = $this->db->where( 'invoice_id', $data['invoice_id'] )->update( 'sales', array(
				'status_id' => 2,
				'staff_id' => $invoice[ 'staff_id' ],
				'customer_id' => $invoice[ 'customer_id' ],
				'total' => $data['amount'],
			) );
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'invoice_id' => $invoice['id'],
				'staff_id' => $invoice['staff_id'],
				'amount' => $data['amount'],
				'customer_id' => $invoice['customer_id'],
				'account_id' => $payment['payu_money_record_account'],
				'not' => '' . lang('paymentfor').' '.lang('invoice').' '. $invoice['id'] . '',
				'mode' => lang('payumoney'),
				'date' => date('Y-m-d H:i:s'),
			));
			redirect(base_url('gateway/success'));
		} else {
			redirect(base_url('gateway/cancel'));
		}
	}

	public function get_invoice_hash($posted, $salt) {
		$hash_sequence = 'key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10';
		$hashVarsSeq = explode('|', $hash_sequence); 
		$hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
			$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
			$hash_string .= '|';
		}
		$hash_string .= $salt;
		$hash = strtolower(hash('sha512', $hash_string));
        return $hash;
    }

    function ccavenue($token) {
    	$invoice = $this->Invoices_Model->get_invoices_by_token( $token );
		if ($invoice) {
			$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
			if ($data['payment']['ccavenue_active'] === TRUE) {
				$data['customer'] = $this->Customers_Model->get_customers( $invoice[ 'customer_id' ] );
				$data['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
				$data['invoice_id'] = sprintf( lang( 'paymentfor' ), str_pad( $invoice['id'], 6, '0', STR_PAD_LEFT ) );
				$data['currency'] = $this->Settings_Model->get_currency();
				$data['invoice'] = $invoice;
				$this->load->view( 'gateway/ccavenue', $data );
			} else {
				redirect(base_url('panel'));
			}
		}
    }

    public function ccavenue_confirm() {
    	$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		if ($data['payment']['ccavenue_active'] === TRUE) {
	        $data['title'] = lang('make_payment') . 'via' . lang('ccavenue');
	        $this->load->view('gateway/pay_ccavenue', $data);
	    } else {
	    	redirect(base_url('panel'));
	    }
    }

    public function ccavenue_success() {
    	$order_info = $this->ccavenue_order_status($_POST);
    	$payment = $this->Settings_Model->get_payment_gateway_data();
    	if ($order_info['order_status'] == 'Success') {
    		$invoice = $this->Invoices_Model->get_invoices( $order_info['order_id'] );
			$response = $this->db->where( 'id', $order_info['order_id'] )->update( 'invoices', array( 'status_id' => 2, 'duedate' => 0 ) );
			$response = $this->db->where( 'invoice_id', $order_info['order_id'] )->update( 'sales', array(
				'status_id' => 2,
				'staff_id' => $invoice[ 'staff_id' ],
				'customer_id' => $invoice[ 'customer_id' ],
				'total' => $order_info['amount'],
			) );
			$this->db->insert( 'payments', array(
				'transactiontype' => 0,
				'invoice_id' => $invoice['id'],
				'staff_id' => $invoice['staff_id'],
				'amount' => $order_info['amount'],
				'customer_id' => $invoice['customer_id'],
				'account_id' => $payment['ccavenue_record_account'],
				'not' => '' . $message = sprintf( lang( 'paymentfor' ), $invoice['id'] ) . '',
				'mode' => lang('ccavenue'),
				'date' => date( 'Y-m-d H:i:s' ),
			));
    		if ($response) {
    			$this->session->set_flashdata( 'ntf1', '' . $result['message'] );
    		} else {
    			$this->session->set_flashdata( 'ntf1', '' . $result['message'] );
    		}
    	} else {
    		$this->session->set_flashdata( 'ntf4', '' . 'Thank You. Your transaction status is ' . $order_info['order_status'] );
    	}
    	redirect('gateway/success');
    }

    public function ccavenue_failure() {
    	$order_info = $this->ccavenue_order_status($_POST);
    	if (!$order_info) {
    		$this->session->set_flashdata( 'ntf3', '' . 'Invalid Transaction' );
    	} else {
    		$this->session->set_flashdata( 'ntf3', '' . 'Thank You. Your transaction status is ' . $order_info['order_status'] );
    	}
    	redirect('gateway/cancel');
    }

    public function ccavenue_order_status($data) {
    	require_once APPPATH . '/libraries/Ccavenue.php';
    	$payment = $this->Settings_Model->get_payment_gateway_data();
        $workingKey = $payment['ccavenue_working_key'];
        $encResponse = $data["encResp"]; 
        $rcvdString = decrypt_cc($encResponse, $workingKey);
        $result = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($i == 3) $order_status = $information[1];
        }
        return $order_status;
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($i == 0) {
                $result['order_id'] = $information[1];
            };
            if ($i == 1) {
                $result['tracking_id'] = $information[1];
            };
            if ($i == 3) {
                $result['order_status'] = $information[1];
            };
            if ($i == 10) {
                $result['amount'] = $information[1];
            };
        }
        return $result;
    }
}