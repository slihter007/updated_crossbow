<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
use Dompdf\ Dompdf;
class Dom {
	public

	function __construct() {
		require_once APPPATH . '/third_party/vendor/autoload.php';
		$pdf = new DOMPDF();
		$CI = & get_instance();
		$CI->dompdf = $pdf;

	}
}
?>