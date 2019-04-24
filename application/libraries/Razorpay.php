<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );

require_once( APPPATH . 'third_party/vendor/razorpay/Razorpay.php' );

use Razorpay\Api\Api;

class Razorpay {
	public function __construct() {
		$this->CI = & get_instance();
		$this->CI->load->helper( 'url' );
		$this->CI->load->model( 'Settings_Model' );
	}

	function razorpay_success($data) {
		$payment = $this->CI->Settings_Model->get_payment_gateway_data();
		$api = new Api($payment['razorpay_key'], $payment['razorpay_key_secret']);
		$amount = filter_var($data['amount'], FILTER_SANITIZE_NUMBER_INT);
		$payment  = $api->payment->fetch($data['razorpay_payment_id'])->capture(array('amount'=>$amount));
		if ($payment) {
			return true;
		} else {
			return false;
		}
	}
}