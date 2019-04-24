<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Orders extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'orders' );
		$data[ 'orders' ] = $this->Orders_Model->get_all_orders();
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'orders/index', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function create() {
		$data[ 'title' ] = lang( 'createorder' );
		$order_type = $this->input->post( 'order_type' );
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			if ( $order_type != true ) {
				$relation_type = 'customer';
				$relation = $this->input->post( 'customer' );
			} else {
				$relation_type = 'lead';
				$relation = $this->input->post( 'lead' );
			};
			$allow_comment = $this->input->post( 'comment' );
			if ( $allow_comment != true ) {
				$comment_allow = 0;
			} else {
				$comment_allow = 1;
			};
			$params = array(
				'token' => md5( uniqid() ),
				'subject' => $this->input->post( 'subject' ),
				'content' => $this->input->post( 'content' ),
				'date' => _pdate( $this->input->post( 'date' ) ),
				'created' => _pdate( $this->input->post( 'created' ) ),
				'opentill' => _pdate( $this->input->post( 'opentill' ) ),
				'relation_type' => $relation_type,
				'relation' => $relation,
				'assigned' => $this->input->post( 'assigned' ),
				'addedfrom' => $this->session->usr_id,
				'datesend' => _pdate( $this->input->post( 'datesend' ) ),
				'comment' => $comment_allow,
				'status_id' => $this->input->post( 'status' ),
				'invoice_id' => $this->input->post( 'invoice' ),
				'dateconverted' => $this->input->post( 'dateconverted' ),
				'sub_total' => $this->input->post( 'sub_total' ),
				'total_discount' => $this->input->post( 'total_discount' ),
				'total_tax' => $this->input->post( 'total_tax' ),
				'total' => $this->input->post( 'total' ),
			);
			$orders_id = $this->Orders_Model->order_add( $params );
			// Custom Field Post
			if ( $this->input->post( 'custom_fields' ) ) {
				$custom_fields = array(
					'custom_fields' => $this->input->post( 'custom_fields' )
				);
				$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'order', $orders_id );
			}
			// $template = $this->Emails_Model->get_template('proposal', 'send_proposal');
			// if ($template['status'] == 1) {
			// 	$pro = $this->Orders_Model->get_pro_rel_type( $id );
			// 	$rel_type = $pro[ 'relation_type' ];
			// 	$order = $this->Orders_Model->get_orders( $id, $rel_type );
			// 	if ($rel_type == 'customer') {
			// 		$name = $proposal['namesurname'];
			// 	} else {
			// 		$name = $proposal['leadname'];
			// 	}
			// 	$message_vars = array(
			// 		'{proposal_to}' => $name,
			// 		'{proposal_number}' => $proposals_id,
			// 		'{subject}' => $this->input->post( 'subject' ),
			// 		'{details}' => $this->input->post( 'content' ),
			// 		'{name}' => $this->session->userdata('staffname'),
			// 		'{email_signature}' => $this->session->userdata('email'),
			// 		'{open_till}' => _pdate( $this->input->post( 'opentill' ) )
			// 	);
			// 	$subject = strtr($template['subject'], $message_vars);
			// 	$message = strtr($template['message'], $message_vars);

			// 	$param = array(
			// 		'from_name' => $template['from_name'],
			// 		'email' => $proposal['toemail'],
			// 		'subject' => $subject,
			// 		'message' => $message,
			// 		'created' => date( "Y.m.d H:i:s" )
			// 	);
			// 	if ($proposal['toemail']) {
			// 		$this->db->insert( 'email_queue', $param );
			// 	}
			// }
			echo $orders_id;
		} else {
			$this->load->view( 'orders/create', $data );
		}
	}

	function update( $id ) {
		$data[ 'title' ] = lang( 'updateorder' );
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$data[ 'order' ] = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( isset( $pro[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				switch ( $this->input->post( 'order_type' ) ) {
					case 'true':
						$relation_type = 'lead';
						$relation = $this->input->post( 'lead' );
						break;
					case 'false':
						$relation_type = 'customer';
						$relation = $this->input->post( 'customer' );
						break;
				};
				switch ( $this->input->post( 'comment' ) ) {
					case 'true':
						$comment_allow = 1;
						break;
					case 'false':
						$comment_allow = 0;
						break;
				};
				$params = array(
					'subject' => $this->input->post( 'subject' ),
					'content' => $this->input->post( 'content' ),
					'date' => _pdate( $this->input->post( 'date' ) ),
					'created' => _pdate( $this->input->post( 'created' ) ),
					'opentill' => _pdate( $this->input->post( 'opentill' ) ),
					'relation_type' => $relation_type,
					'relation' => $relation,
					'assigned' => $this->input->post( 'assigned' ),
					'addedfrom' => $this->session->usr_id,
					'datesend' => _pdate( $this->input->post( 'datesend' ) ),
					'comment' => $comment_allow,
					'status_id' => $this->input->post( 'status' ),
					'invoice_id' => $this->input->post( 'invoice' ),
					'dateconverted' => $this->input->post( 'dateconverted' ),
					'sub_total' => $this->input->post( 'sub_total' ),
					'total_discount' => $this->input->post( 'total_discount' ),
					'total_tax' => $this->input->post( 'total_tax' ),
					'total' => $this->input->post( 'total' ),
				);
				$this->Orders_Model->update_orders( $id, $params );
				// Custom Field Post
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' )
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'order', $id );
				}
				echo $id;
			} else {
				$this->load->view( 'orders/update', $data );
			}
		} else
			$this->session->set_flashdata( 'ntf3', '' . $id . lang( 'orderediterror' ) );
	}

	function order( $id ) {
		$data[ 'title' ] = lang( 'order' );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'orders/order', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function create_pdf( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
		$this->load->view( 'orders/pdf', $data );
		$file_name = '' . lang( 'orderprefix' ) . '-' . str_pad( $id, 6, '0', STR_PAD_LEFT ) . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'assets/files/generated_pdf_files/orders/' . $file_name . '', $output );
		//$this->dompdf->stream( '' . $file_name . '', array( "Attachment" => 0 ) );
		if ( $output ) {
			redirect( base_url( 'orders/pdf_generated/' . $file_name . '' ) );
		} else {
			redirect( base_url( 'invoices/pdf_fault/' ) );
		}
	}

	function print_( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
		$this->load->view( 'orders/pdf', $data );
		$file_name = '' . lang( 'orderprefix' ) . '-' . str_pad( $id, 6, '0', STR_PAD_LEFT ) . '.pdf';
		$html = $this->output->get_output();
		$this->load->library( 'dom' );
		$this->dompdf->loadHtml( $html );
		$this->dompdf->set_option( 'isRemoteEnabled', TRUE );
		$this->dompdf->setPaper( 'A4', 'portrait' );
		$this->dompdf->render();
		$output = $this->dompdf->output();
		file_put_contents( 'assets/files/generated_pdf_files/orders/' . $file_name . '', $output );
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
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data[ 'items' ] = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
		$this->load->view( 'orders/pdf', $data );
	}

	function share( $id ) {
		$setconfig = $this->Settings_Model->get_settings_ciuis();
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $rel_type == 'customer' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			switch ( $order[ 'type' ] ) {
				case '0':
					$orderto = $order[ 'customercompany' ];
					break;
				case '1':
					$orderto = $order[ 'namesurname' ];
					break;
			}
			$ordertoemail = $order[ 'toemail' ];
		}
		if ( $rel_type == 'lead' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			$orderto = $order[ 'leadname' ];
			$ordertoemail = $order[ 'toemail' ];
		}
		$subject = lang( 'neworder' );
		$to = $ordertoemail;
		$data = array(
			'customer' => $orderto,
			'customermail' => $ordertoemail,
			'orderlink' => '' . base_url( 'share/order/' . $pro[ 'token' ] . '' ) . ''
		);
		$body = $this->load->view( 'email/orders/send.php', $data, TRUE );
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			$response = $this->db->where( 'id', $id )->update( 'orders', array( 'datesend' => date( 'Y-m-d H:i:s' ) ) );
			$this->session->set_flashdata( 'ntf1', '<b>' . lang( 'sendmailcustomer' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		} else {
			$this->session->set_flashdata( 'ntf4', '<b>' . lang( 'sendmailcustomereror' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		}
	}

	function send_order_email($id) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $rel_type == 'customer' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			switch ( $order[ 'type' ] ) {
				case '0':
					$orderto = $order[ 'customercompany' ];
					break;
				case '1':
					$orderto = $order[ 'namesurname' ];
					break;
			}
			$ordertoemail = $order[ 'toemail' ];
		}
		if ($rel_type == 'lead') {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data['orders'] = $this->Orders_Model->get_orders( $id, $rel_type );
			$orderto = $order['leadname'];
			$ordertoemail = $order['toemail'];
		}

		$template = $this->Emails_Model->get_template('order', 'order_message');
		$appconfig = get_appconfig();
		$order_number = $appconfig['order_prefix'] . '' . str_pad( $id, 6, '0', STR_PAD_LEFT ).$appconfig['order_suffix'];
		
		$settings = $this->Settings_Model->get_settings_ciuis();
		$message_vars = array(
			'{customer}' => $orderto,
			'{order_to}' => $orderto,
			'{email_signature}' => $this->session->userdata( 'email' ),
			'{name}' => $this->session->userdata( 'staffname' ),
			'{order_number}' => $order_number,
			'{app_name}' => $settings['company'],
			'{company_name}' => $settings['company']
		);
		$subject = strtr($template['subject'], $message_vars);
		$message = strtr($template['message'], $message_vars);

		$param = array(
			'from_name' => $template['from_name'],
			'email' => $ordertoemail,
			'subject' => $subject,
			'message' => $message,
			'created' => date( "Y.m.d H:i:s" ),
			'status' => 0
		);
		if ($ordertoemail) {
			$this->load->library('mail'); 
			$data = $this->mail->send_email($ordertoemail, $template['from_name'], $subject, $message);
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

	function expiration( $id ) {
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$setconfig = $this->Settings_Model->get_settings_ciuis();
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		if ( $rel_type == 'customer' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			switch ( $order[ 'type' ] ) {
				case '0':
					$orderto = $order[ 'customercompany' ];
					break;
				case '1':
					$orderto = $order[ 'namesurname' ];
					break;
			}
			$ordertoemail = $order[ 'toemail' ];
		}
		if ( $rel_type == 'lead' ) {
			$order = $this->Orders_Model->get_orders( $id, $rel_type );
			$data[ 'orders' ] = $this->Orders_Model->get_orders( $id, $rel_type );
			$orderto = $order[ 'leadname' ];
			$ordertoemail = $order[ 'toemail' ];
		}
		$subject = lang( 'orderexpiryreminder' );
		$to = $ordertoemail;
		$data = array(
			'customer' => $orderto,
			'customermail' => $ordertoemail,
			'orderlink' => '' . base_url( 'share/order/' . $pro[ 'token' ] . '' ) . ''
		);
		$body = $this->load->view( 'email/orders/expiration.php', $data, TRUE );
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			$response = $this->db->where( 'id', $id )->update( 'orders', array( 'datesend' => date( 'Y-m-d H:i:s' ) ) );
			$this->session->set_flashdata( 'ntf1', '<b>' . lang( 'sendmailcustomer' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		} else {
			$this->session->set_flashdata( 'ntf4', '<b>' . lang( 'sendmailcustomereror' ) . '</b>' );
			redirect( 'orders/order/' . $id . '' );
		}
	}

	function convert_invoice( $id ) {
		$data[ 'title' ] = lang( 'convertordertoinvoice' );
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$order = $this->Orders_Model->get_orders( $id, $rel_type );
		$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $order[ 'id' ] ) )->result_array();
		$date = strtotime( "+7 day" );
		if ( isset( $order[ 'id' ] ) ) {
			$params = array(
				'token' => md5( uniqid() ),
				'no' => null,
				'serie' => null,
				'customer_id' => $order[ 'relation' ],
				'staff_id' => $this->session->usr_id,
				'status_id' => 3,
				'created' => date( 'Y-m-d H:i:s' ),
				'duedate' => date( 'Y-m-d H:i:s', $date ),
				'datepayment' => 0,
				'duenote' => null,
				//'order_id' => $order[ 'id' ],
				'sub_total' => $order[ 'sub_total' ],
				'total_discount' => $order[ 'total_discount' ],
				'total_tax' => $order[ 'total_tax' ],
				'total' => $order[ 'total' ],
			);
			$this->db->insert( 'invoices', $params );
			$invoice = $this->db->insert_id();
			$i = 0;
			foreach ( $items as $item ) {
				$this->db->insert( 'items', array(
					'relation_type' => 'invoice',
					'relation' => $invoice,
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
				$i++;
			};
			//LOG
			$staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$appconfig = get_appconfig();
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'added' ) . ' <a href="invoices/invoice/' . $invoice . '">' . $appconfig['inv_prefix'] . '' . $invoice .$appconfig['inv_suffix']. '</a>.' ),
				'staff_id' => $loggedinuserid,
				'customer_id' => $order[ 'relation' ]
			) );
			//NOTIFICATION
			$staffname = $this->session->staffname;
			$staffavatar = $this->session->staffavatar;
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $staffname . ' ' . lang( 'isaddedanewinvoice' ) . '' ),
				'customer_id' => $order[ 'relation' ],
				'perres' => $staffavatar,
				'target' => '' . base_url( 'area/invoice/' . $invoice . '' ) . ''
			) );
			//--------------------------------------------------------------------------------------
			$this->db->insert( $this->db->dbprefix . 'sales', array(
				'invoice_id' => '' . $invoice . '',
				'status_id' => 3,
				'staff_id' => $loggedinuserid,
				'customer_id' => $order[ 'relation' ],
				'total' => $order[ 'total' ],
				'date' => date( 'Y-m-d H:i:s' )
			) );

			$response = $this->db->where( 'id', $id )->update( 'orders', array( 'invoice_id' => $invoice, 'status_id' => 6, 'dateconverted' => date( 'Y-m-d H:i:s' ) ) );
			echo json_encode( $invoice );
		}
	}

	function markas() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'order_id' => $_POST[ 'order_id' ],
				'status_id' => $_POST[ 'status_id' ],
			);
			$tickets = $this->Orders_Model->markas();
		}
	}

	function cancelled() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'order' => $_POST[ 'order_id' ],
				'status_id' => $_POST[ 'status_id' ],
			);
			$tickets = $this->Orders_Model->cancelled();
		}
	}

	function remove( $id ) {
		$orders = $this->Orders_Model->get_pro_rel_type( $id );
		if ( isset( $orders[ 'id' ] ) ) {
			$this->session->set_flashdata( 'ntf4', lang( 'orderdeleted' ) );
			$this->Orders_Model->delete_orders( $id );
			redirect( 'orders/index' );
		} else
			show_error( 'The orders you are trying to delete does not exist.' );
	}

	function remove_item( $id ) {
		$response = $this->db->delete( 'items', array( 'id' => $id ) );
	}
}