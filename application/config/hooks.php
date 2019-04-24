<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

$ci = & get_instance();
$set = $ci->db->select( '*' )->get( 'settings' )->row_array();
$ci->load->model( 'Settings_Model' );
$payment = $ci->Settings_Model->get_payment_gateway_data();
switch ( $payment[ 'paypal_test_mode_enabled' ] ) {
	case '0':
		$config[ 'sandbox' ] = 'FALSE';
		break;
	case '1':
		$config[ 'sandbox' ] = 'TRUE';
		break;
}
$config[ 'business' ] = $payment[ 'paypal_username' ];
$config[ 'paypal_lib_ipn_log_file' ] = BASEPATH . 'logs/paypal_ipn.log';
$config[ 'paypal_lib_ipn_log' ] = TRUE;
$config[ 'paypal_lib_button_path' ] = 'buttons';
$config[ 'paypal_lib_currency_code' ] = $payment[ 'paypal_currency' ];
$config[ 'authorize_login_id' ] = $payment[ 'authorize_aim_api_login_id' ];
$config[ 'authorize_transaction_key' ] = $payment[ 'authorize_aim_api_transaction_key' ]; 