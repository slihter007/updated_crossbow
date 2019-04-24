<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );

require_once( APPPATH . 'third_party/vendor/autoload.php' );

use Omnipay\ Omnipay;
use Omnipay\ Common\ CreditCard;

class Omni extends Omnipay {
	public $merchanAuthentication;
	public $refId;
	protected $gateway = null;
	var $last_error; // holds the last error encountered
	var $ipn_log; // bool: log IPN results to text file?
	var $ipn_log_file; // filename of the IPN log
	var $ipn_response; // holds the IPN response from paypal	
	var $ipn_data = array(); // array contains the POST values for IPN
	var $fields = array(); // array holds the fields to submit to paypal
	var $submit_btn = ''; // Image/Form button
	var $button_path = ''; // The path of the buttons

	public

	function __construct() {

		$this->CI = & get_instance();
		$this->CI->load->helper( 'url' );
		$this->CI->load->helper( 'form' );
		$this->CI->load->config( 'hooks' );
		$this->CI->load->config( 'hooks' );
		$this->CI->load->model( 'Settings_Model' );

		//  Get payment keys
		$payment = $this->CI->Settings_Model->get_payment_gateway_data();

		// AUTHORIZE.NET AIM SETTINGS

		$this->merchanAuthentication = new net\ authorize\ api\ contract\ v1\ MerchantAuthenticationType();
		$this->merchanAuthentication->setName( $payment['authorize_aim_api_login_id'] );
		$this->merchanAuthentication->setTransactionKey( $payment['authorize_aim_api_transaction_key'] );
		$this->refId = 'ref' . time();

		// PAYPAL PRO SETTINGS
		
		$this->gateway = Omnipay::create( 'PayPal_Pro' );
		$this->gateway->setUsername( 'batikkode-facilitator_api1.gmail.com' );
		$this->gateway->setPassword( 'SKJXNTSPZUUFR4L5' );
		$this->gateway->setSignature( 'AFcWxV21C7fd0v3bYYYRCpSSRl31AHsyCp6cnyiiUJtWS.5bR.YdidWW' );
		$this->gateway->setTestMode( 'true' );

		// PAYPAL STANDART SETTINGS

		$sanbox = $payment['paypal_test_mode_enabled'];
		$this->paypal_url = ( $sanbox == TRUE ) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
		$this->last_error = '';
		$this->ipn_response = '';
		$this->ipn_log_file = $this->CI->config->item( 'paypal_lib_ipn_log_file' );
		$this->ipn_log = $this->CI->config->item( 'paypal_lib_ipn_log' );
		$this->button_path = $this->CI->config->item( 'paypal_lib_button_path' );
		$businessEmail = $this->CI->config->item( 'business' );
		$this->add_field( 'business', $businessEmail );
		$this->add_field( 'rm', '2' ); // Return method = POST
		$this->add_field( 'cmd', '_xclick' );
		$this->add_field( 'currency_code', $this->CI->config->item( 'paypal_lib_currency_code' ) );
		$this->add_field( 'quantity', '1' );
		$this->button( 'Pay Now!' );

		
	}

	function stripe_success($data) {
		try {
			$payment = $this->CI->Settings_Model->get_payment_gateway_data();
		    $gatewayStripe = Omnipay::create('Stripe');
			$gatewayStripe->setApiKey($payment['stripe_api_secret_key']);
			if ($payment['stripe_test_mode_enabled'] === TRUE) {
				$gatewayStripe->setTestMode(true);
			}
			//$cardresponse = $gatewayStripe->createCard(array('token' =>$data['stripeToken']))->send();
			$response = $gatewayStripe->purchase(array( 
				'amount' => $data['amount'],
				'currency' => $data['currency'],
				'description' => 'Invoice payment '.$data['invoice_id'],
				'capture' => true,
				'source' => $data['stripeToken'],
			))->send();

			// if ($cardresponse->isSuccessful()) {
		 //      $card_id = $cardresponse->getCardReference();
		 //      $data = $cardresponse->getData();
		 //      $customerid = $data['id'];
		 //      $cardid = $data['default_source'];
		 //    }
		 //    $paymentresponse = $gatewayStripe->purchase(array('amount' => '10.00','currency'  => 'USD', 'customerReference' => $card_id))->send();
		    if ($response->isSuccessful()) {
		        return true;
		    } elseif ($response->isRedirect()) {
		        return $response->getMessage();;
		    } else {
		        return $response->getMessage();
		    }
		} catch (\Exception $e) {
		    exit('Sorry, there was an error processing your payment. Please try again later.');
		}
	}

	public function chargerCreditCard( $detCus ) {
		$creditCard = new net\ authorize\ api\ contract\ v1\ CreditCardType();
		$creditCard->setCardNumber( $detCus[ 'cnumber' ] );
		$creditCard->setExpirationDate( $detCus[ 'cexpdate' ] );
		$creditCard->setCardCode( $detCus[ 'ccode' ] );
		$paymentOne = new net\ authorize\ api\ contract\ v1\ PaymentType();
		$paymentOne->setCreditCard( $creditCard );
		$order = new net\ authorize\ api\ contract\ v1\ OrderType();
		$order->setDescription( $detCus[ 'cdesc' ] );
		$billto = new net\ authorize\ api\ contract\ v1\ CustomerAddressType();
		$billto->setFirstName( $detCus[ 'fname' ] );
		$billto->setLastName( $detCus[ 'lname' ] );
		$billto->setAddress( $detCus[ 'address' ] ); 
		$billto->setCity( $detCus[ 'city' ] );
		$billto->setState( $detCus[ 'state' ] );
		$billto->setCountry( $detCus[ 'country' ] );
		$billto->setZip( $detCus[ 'zip' ] );
		$billto->setPhoneNumber( $detCus[ 'phone' ] );
		$billto->setEmail( $detCus[ 'email' ] );
		$transactionRequestType = new net\ authorize\ api\ contract\ v1\ TransactionRequestType();
		$transactionRequestType->setTransactionType( "authCaptureTransaction" );
		$transactionRequestType->setAmount( $detCus[ 'amount' ] );
		$transactionRequestType->setOrder( $order );
		$transactionRequestType->setPayment( $paymentOne );
		$transactionRequestType->setBillTo( $billto );
		$request = new net\ authorize\ api\ contract\ v1\ CreateTransactionRequest();
		$request->setMerchantAuthentication( $this->merchanAuthentication );
		$request->setRefId( $this->refId );
		$request->setTransactionRequest( $transactionRequestType );
		$controllerx = new net\ authorize\ api\ controller\ CreateTransactionController( $request );
		$response = $controllerx->executeWithApiResponse( \net\ authorize\ api\ constants\ ANetEnvironment::PRODUCTION );
		if ( $response != null ) {
			$tresponse = $response->getTransactionResponse();

			if ( ( $tresponse != null ) && ( $tresponse->getResponseCode() == "1" ) ) {
				echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
				echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
				return true;
			} else {
				echo "Charge Credit Card ERROR :  Invalid response\n";
				return false;
			}

		} else {
			echo "Charge Credit card Null response returned";
		}
	}

	// PAYPAL SETTINGS

	public

	function sendPurchase( $cardInput, $valTransaction ) {
		$card = new CreditCard( $cardInput );
		$payArray = array(
			'amount' => $valTransaction[ 'amount' ],
			'transactionId' => $valTransaction[ 'transactionId' ],
			'description' => $valTransaction[ 'description' ],
			'currency' => $valTransaction[ 'currency' ],
			'clientIp' => $valTransaction[ 'clientIp' ],
			'returnUrl' => $valTransaction[ 'returnUrl' ],
			'card' => $card );
		$response = $this->gateway->purchase( $payArray )->send();
		if ( $response->isSuccessful() ) {
			$paypalResponse = $response->getData();
		} elseif ( $response->isRedirect() ) {
			$paypalResponse = $response->getRedirectData();
		} else {
			$paypalResponse = $response->getMessage();
		}
		return $paypalResponse;
	}


	// PAYPAL STANDART

	function button( $value ) {
		$this->submit_btn = form_submit( 'pp_submit', $value );
	}

	function image( $file ) {
		$this->submit_btn = '<input type="image" name="add" src="' . site_url( $this->button_path . '/' . $file ) . '" border="0" />';
	}


	function add_field( $field, $value ) {
		$this->fields[ $field ] = $value;
	}

	function paypal_auto_form() {
		$this->button( 'Click here if you\'re not automatically redirected...' );
		echo '<html>' . "\n";
		echo '<head><title>Processing Payment...</title></head>' . "\n";
		echo '<body style="text-align:center;" onLoad="document.forms[\'paypal_auto_form\'].submit();">' . "\n";
		echo '<p style="text-align:center;">Please wait, your order is being processed and you will be redirected to the paypal website.</p>' . "\n";
		echo $this->paypal_form( 'paypal_auto_form' );
		echo '</body></html>';
	}

	function paypal_form( $form_name = 'paypal_form' ) {
		$str = '';
		$str .= '<form method="post" action="' . $this->paypal_url . '" name="' . $form_name . '"/>' . "\n";
		foreach ( $this->fields as $name => $value )
			$str .= form_hidden( $name, $value ) . "\n";
		$str .= '<p>' . $this->submit_btn . '</p>';
		$str .= form_close() . "\n";

		return $str;
	}

	function validate_ipn() {
		$url_parsed = parse_url( $this->paypal_url );
		$post_string = '';
		if ( $this->CI->input->post() ) {
			foreach ( $this->CI->input->post() as $field => $value ) {
				$this->ipn_data[ $field ] = $value;
				$post_string .= $field . '=' . urlencode( stripslashes( $value ) ) . '&';
			}
		}
		$post_string .= "cmd=_notify-validate"; // append ipn command
		$fp = fsockopen( $url_parsed[ 'host' ], "80", $err_num, $err_str, 30 );
		if ( !$fp ) {
			$this->last_error = "fsockopen error no. $errnum: $errstr";
			$this->log_ipn_results( false );
			return false;
		} else {
			fputs( $fp, "POST $url_parsed[path] HTTP/1.1\r\n" );
			fputs( $fp, "Host: $url_parsed[host]\r\n" );
			fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" );
			fputs( $fp, "Content-length: " . strlen( $post_string ) . "\r\n" );
			fputs( $fp, "Connection: close\r\n\r\n" );
			fputs( $fp, $post_string . "\r\n\r\n" );
			while ( !feof( $fp ) )
				$this->ipn_response .= fgets( $fp, 1024 );

			fclose( $fp ); // close connection
		}
		if ( preg_match( "/VERIFIED/", $this->ipn_response ) ) {
			$this->log_ipn_results( true );
			return true;
		} else {
			$this->last_error = 'IPN Validation Failed.';
			$this->log_ipn_results( false );
			return false;
		}
	}

	function log_ipn_results( $success ) {
		if ( !$this->ipn_log ) return; // is logging turned off?
		$text = '[' . date( 'm/d/Y g:i A' ) . '] - ';
		if ( $success )$text .= "SUCCESS!\n";
		else $text .= 'FAIL: ' . $this->last_error . "\n";
		$text .= "IPN POST Vars from Paypal:\n";
		foreach ( $this->ipn_data as $key => $value )
			$text .= "$key=$value, ";
		$text .= "\nIPN Response from Paypal Server:\n " . $this->ipn_response;
		$fp = fopen( $this->ipn_log_file, 'a' );
		fwrite( $fp, $text . "\n\n" );

		fclose( $fp );
	}

	function dump() {
		ksort( $this->fields );
		echo '<h2>ppal->dump() Output:</h2>' . "\n";
		echo '<code style="font: 12px Monaco, \'Courier New\', Verdana, Sans-serif;  background: #f9f9f9; border: 1px solid #D0D0D0; color: #002166; display: block; margin: 14px 0; padding: 12px 10px;">' . "\n";
		foreach ( $this->fields as $key => $value )echo '<strong>' . $key . '</strong>:	' . urldecode( $value ) . '<br/>';
		echo "</code>\n";
	}


	function curlPost( $paypalurl, $paypalreturnarr ) {
		$req = 'cmd=_notify-validate';
		foreach ( $paypalreturnarr as $key => $value ) {
			$value = urlencode( stripslashes( $value ) );
			$req .= "&$key=$value";
		}
		$ipnsiteurl = $paypalurl;
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $ipnsiteurl );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
		$result = curl_exec( $ch );
		curl_close( $ch );
		return $result;
	}
}