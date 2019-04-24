<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Customers extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'customers' );
		$data[ 'settings' ] = $this->Settings_Model->get_settings_ciuis();
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'customers/index', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function create() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$company = $this->input->post( 'company' );
			$namesurname = $this->input->post( 'namesurname' );
			$email = $this->input->post( 'email' );
			$default_payment_method = $this->input->post( 'default_payment_method' );
			if ( $this->input->post('type') == 'true' ) {
				$type = 1;
				$company = '';
			} else {
				$type = 0;
				$namesurname = '';
			}
			$hasError = false;
			$data['message'] = '';
			if ($company == '' && $type == 0) {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('company');
			} else if ($namesurname == '' && $type == 1) {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('name');
			} else if ($email == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('email');
			}
			//  else if ($default_payment_method == '') {
			// 	$hasError = true;
			// 	$data['message'] = lang('selectinvalidmessage'). ' ' .lang('default_payment_method');
			// }

			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'created' => date( 'Y-m-d H:i:s' ),
					'type' => $type,
					'company' => $company,
					'namesurname' => $namesurname,
					'ssn' => $this->input->post( 'ssn' ),
					'executive' => $this->input->post( 'executive' ),
					'address' => $this->input->post( 'address' ),
					'phone' => $this->input->post( 'phone' ),
					'email' => $this->input->post( 'email' ),
					'fax' => $this->input->post( 'fax' ),
					'web' => $this->input->post( 'web' ),
					'taxoffice' => $this->input->post( 'taxoffice' ),
					'taxnumber' => $this->input->post( 'taxnumber' ),
					'country_id' => $this->input->post( 'country_id' ),
					'state' => $this->input->post( 'state' ),
					'city' => $this->input->post( 'city' ),
					'town' => $this->input->post( 'town' ),
					'zipcode' => $this->input->post( 'zipcode' ),
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
					'staff_id' => $this->session->userdata( 'usr_id' ),
					'status_id' => $this->input->post( 'status' ),
					'default_payment_method' => $this->input->post('default_payment_method')
				);
				$customers_id = $this->Customers_Model->add_customers( $params );

				$template = $this->Emails_Model->get_template('customer', 'new_customer');
				if ($template['status'] == 1) {
					$admins = $this->Staff_Model->get_all_admins(); 
					if($this->input->post( 'namesurname' )) {
						$name = $this->input->post( 'namesurname' );
						$type = lang('individual');
					} else {
						$name = $this->input->post( 'company' );
						$type = lang('company');
					}
					$message_vars = array(
						'{customer_type}' => $type,
						'{name}' => $name,
						'{customer_email}' => $this->input->post( 'email' ),
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $admins['email'],
						'subject' => $subject,
						'message' => $message,
					);
					if ($param['email']) {
						$this->db->insert( 'email_queue', $param );
					}
				}
				if ( $this->input->post( 'custom_fields' ) ) {
					$custom_fields = array(
						'custom_fields' => $this->input->post( 'custom_fields' )
					);
					$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'customer', $customers_id );
				}
				$data['success'] = true;
				$data['id'] = $customers_id;
				echo json_encode($data);
			}
		}
	}

	function customer( $id ) {
		$data['payment'] = $this->Settings_Model->get_payment_gateway_data();
		$data[ 'title' ] = lang( 'customer' );
		$customers = $this->Customers_Model->get_customers( $id );
		$data[ 'ycr' ] = $this->Report_Model->ycr();
		if ( isset( $customers[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$company = $this->input->post( 'company' );
				$namesurname = $this->input->post( 'namesurname' );
				$email = $this->input->post( 'email' );
				$default_payment_method = $this->input->post( 'default_payment_method' );
				
				if ( $this->input->post('type') == 'true' ) {
					$type = 1;
					$company = '';
				} else {
					$type = 0;
					$namesurname = '';
				}
				$hasError = false;
				$data['message'] = '';
				if ($company == '' && $type == 0) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('company');
				} else if ($namesurname == '' && $type == 1) {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('name');
				} else if ($email == '') {
					$hasError = true;
					$data['message'] = lang('invalidmessage'). ' ' .lang('customer'). ' ' .lang('email');
				} 
				// else if ($default_payment_method == '') {
				// 	$hasError = true;
				// 	$data['message'] = lang('selectinvalidmessage'). ' ' .lang('default_payment_method');
				// }
				if ($hasError) {
					$data['success'] = false;
					echo json_encode($data);
				}
				if (!$hasError) {
					$params = array(
						'company' => $company,
						'type' => $type,
						'namesurname' => $namesurname,
						'ssn' => $this->input->post( 'ssn' ),
						'executive' => $this->input->post( 'executive' ),
						'address' => $this->input->post( 'address' ),
						'phone' => $this->input->post( 'phone' ),
						'email' => $this->input->post( 'email' ),
						'fax' => $this->input->post( 'fax' ),
						'web' => $this->input->post( 'web' ),
						'taxoffice' => $this->input->post( 'taxoffice' ),
						'taxnumber' => $this->input->post( 'taxnumber' ),
						'country_id' => $this->input->post( 'country_id' ),
						'state' => $this->input->post( 'state' ),
						'city' => $this->input->post( 'city' ),
						'town' => $this->input->post( 'town' ),
						'zipcode' => $this->input->post( 'zipcode' ),
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
						'staff_id' => $this->session->userdata( 'usr_id' ),
						'risk' => $this->input->post( 'risk' ),
						'status_id' => $this->input->post( 'status' ),
						'default_payment_method' => $this->input->post('default_payment_method')
					);
					$this->Customers_Model->update_customers( $id, $params );
					if ( $this->input->post( 'custom_fields' ) ) {
						$custom_fields = array(
							'custom_fields' => $this->input->post( 'custom_fields' )
						);
						$this->Fields_Model->custom_field_data_add_or_update_by_type( $custom_fields, 'customer', $id );
					}
					$data['success'] = true;
					$data['message'] = lang('customer').' '.lang('updatemessage');
					echo json_encode($data);
				}
			} else {
				$data[ 'customers' ] = $this->Customers_Model->get_customers( $id );
				$this->load->view( 'customers/customer', $data );
			}
		} else
			show_error( 'Eror' );
	}

	function customersimport () {
		$this->load->library( 'import' );
		$data[ 'customers' ] = $this->Customers_Model->get_customers_for_import();
		$data[ 'error' ] = '';
		$config[ 'upload_path' ] = './uploads/imports/';
		$config[ 'allowed_types' ] = 'csv';
		$config[ 'max_size' ] = '1000';
		$this->load->library( 'upload', $config ); 
		if ( !$this->upload->do_upload() ) {
			$data[ 'error' ] = $this->upload->display_errors();
			$this->session->set_flashdata( 'ntf1', lang('csvimporterror') );
			redirect( 'customers/index' );
		} else {
			$file_data = $this->upload->data();
			$file_path = './uploads/imports/' . $file_data[ 'file_name' ];
			if ( $this->import->get_array( $file_path ) ) {
				$csv_array = $this->import->get_array( $file_path );
				foreach ( $csv_array as $row ) {
					$insert_data = array(
						'created' => date( 'Y-m-d H:i:s' ),
						'type' => $row[ 'type' ],
						'namesurname' => $row[ 'namesurname' ],
						'company' => $row[ 'company' ],
						'taxoffice' => $row[ 'taxoffice' ],
						'taxnumber' => $row[ 'taxnumber' ],
						'executive' => $row[ 'executive' ],
						'ssn' => $row[ 'ssn' ],
						'town' => $row[ 'town' ],
						'zipcode' => $row[ 'zipcode' ],
						'city' => $row[ 'city' ],
						'state' => $row[ 'state' ],
						'country_id' => $row[ 'country_id' ],
						'address' => $row[ 'address' ],
						'fax' => $row[ 'fax' ],
						'email' => $row[ 'email' ],
						'web' => $row[ 'web' ],
						'status_id' => $row[ 'status_id' ],
						'phone' => $row[ 'phone' ],
						'risk' => $row[ 'risk' ],
						'staff_id' => $this->input->post( 'importassigned' ),
					);
					$this->Customers_Model->insert_customers_csv( $insert_data );
				}
				$this->session->set_flashdata( 'ntf1', lang('csvimportsuccess') );
				redirect( 'customers/index' );
			} else
				redirect( 'customers/index' );
			$this->session->set_flashdata( 'ntf3', 'Error' );
		}
	}

	function exportdata() {
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$this->db->select('type, created, company, namesurname, taxoffice, taxnumber, ssn, executive, address, zipcode, country_id, state, city, town, phone, fax, email, web, status_id, risk');
		$this->db->order_by( 'customers.id', 'desc' );
		$q = $this->db->get_where( 'customers', array( ''  ) );
		$delimiter = ",";
		$nuline    = "\r\n";
		force_download('Customers.csv', $this->dbutil->csv_from_result($q, $delimiter, $nuline));
	}

	function addreminder() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'description' => $this->input->post( 'description' ),
				'relation' => $this->input->post( 'relation' ),
				'relation_type' => 'customer',
				'staff_id' => $this->input->post( 'staff' ),
				'addedfrom' => $this->session->userdata( 'usr_id' ),
				'date' => $this->input->post( 'date' ),
			);
			$notes = $this->Trivia_Model->add_reminder( $params );
			$this->session->set_flashdata( 'ntf1', '' . lang( 'reminderadded' ) . '' );
			redirect( 'customers/customer/' . $this->input->post( 'relation' ) . '' );
		} else {
			redirect( 'leads/index' );
		}
	}

	function remove( $id ) {
		$customers = $this->Customers_Model->get_customers( $id );
		if ( isset( $customers[ 'id' ] ) ) {
			$this->Customers_Model->delete_customers( $id );
			redirect( 'customers/index' );
		} else
			show_error( 'Customer not deleted' );
	}

	function customers_json() {
		$customers = $this->Customers_Model->get_all_customers();
		header( 'Content-Type: application/json' );
		echo json_encode( $customers );
	}

	function customers_arama_json() {
		$veriler = $this->Customers_Model->search_json_customer();
		echo json_encode( $veriler );

	}

	function create_contact() {
		if ( $this->Contacts_Model->isDuplicate( $this->input->post( 'email' ) ) ) {
			$this->session->set_flashdata( 'ntf4', 'Contact email already exists' );
			redirect( 'customers/customer/' . $this->input->post( 'customer' ) . '' );
		} else {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				switch ( $this->input->post( 'isPrimary' ) ) {
					case 'true':
						$primary = 1;
						$passNew = password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT );
						break;
					case 'false':
						$primary = 0;
						$passNew = null;
						break;
				}
				switch ( $this->input->post( 'isAdmin' ) ) {
					case true:
						$isAdmin = 1;
						break;
					case false:
						$isAdmin = 0;
						break;
				}
				$params = array(
					'name' => $this->input->post( 'name' ),
					'surname' => $this->input->post( 'surname' ),
					'phone' => $this->input->post( 'phone' ),
					'extension' => $this->input->post( 'extension' ),
					'mobile' => $this->input->post( 'mobile' ),
					'email' => $this->input->post( 'email' ),
					'address' => $this->input->post( 'address' ),
					'skype' => $this->input->post( 'skype' ),
					'linkedin' => $this->input->post( 'linkedin' ),
					'customer_id' => $this->input->post( 'customer' ),
					'position' => $this->input->post( 'position' ),
					'primary' => $primary,
					'admin' => $isAdmin,
					'password' => $passNew,
				);
				$contacts_id = $this->Contacts_Model->create( $params );

				$template = $this->Emails_Model->get_template('customer', 'new_contact_added');
				if ($template['status'] == 1) {
					$message_vars = array(
						'{login_email}' => $this->input->post( 'email' ),
						'{login_password}' => ($this->input->post( 'password' ))?($this->input->post( 'password' )):' ',
						'{app_url}' => '' . base_url( 'area/login' ) . '',
						'{email_signature}' => $this->session->userdata( 'email' ),
						'{name}' => $this->session->userdata( 'staffname' ),
						'{customer}' => $this->input->post( 'name' )
					);
					$subject = strtr($template['subject'], $message_vars);
					$message = strtr($template['message'], $message_vars);

					$param = array(
						'from_name' => $template['from_name'],
						'email' => $this->input->post( 'email' ),
						'subject' => $subject,
						'message' => $message,
						'created' => date( "Y.m.d H:i:s" ),
					);
					if ($this->input->post( 'email' )) {
						$this->db->insert( 'email_queue', $param );
					}
				}

				if ( $contacts_id ) {
					$message = sprintf( lang( 'addedcontacts' ), $this->input->post( 'name' ) );
					echo $message;
				} else {
					$message = sprintf( lang( 'addedcontactsbut' ), $this->input->post( 'name' ) );
					echo $message;
				}

			}
		}
	}

	function update_contact( $id ) {
		$contacts = $this->Contacts_Model->get_contacts( $id );
		if ( isset( $contacts[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
					'surname' => $this->input->post( 'surname' ),
					'phone' => $this->input->post( 'phone' ),
					'extension' => $this->input->post( 'extension' ),
					'mobile' => $this->input->post( 'mobile' ),
					'email' => $this->input->post( 'email' ),
					'address' => $this->input->post( 'address' ),
					'skype' => $this->input->post( 'skype' ),
					'linkedin' => $this->input->post( 'linkedin' ),
					'position' => $this->input->post( 'position' ),
				);

				$this->Contacts_Model->update( $id, $params );
				$data['message'] = $this->input->post( 'name' ) .' '. lang( 'contactupdated' );
				echo json_encode($data);
			} else {
				$data[ 'contacts' ] = $this->Contacts_Model->get_contacts( $id );
			}
		} else
			show_error( 'The contacts you are trying to edit does not exist.' );
	}

	function update_contact_privilege( $id, $value, $privilege_id ) {
		if ( $value != 'false' ) {
			$params = array(
				'relation' => ( int )$id,
				'relation_type' => 'contact',
				'permission_id' => ( int )$privilege_id
			);
			$this->db->insert( 'privileges', $params );
			echo $this->db->insert_id();
		} else {
			$response = $this->db->delete( 'privileges', array( 'relation' => $id, 'relation_type' => 'contact', 'permission_id' => $privilege_id ) );
		}

	}

	function change_password_contact( $id ) { 
		$contact = $this->Contacts_Model->get_contacts( $id );
		if ( isset( $contact[ 'id' ] ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'password' => password_hash( $this->input->post( 'password' ), PASSWORD_BCRYPT ),
				);
				$customer = $contact[ 'customer_id' ];
				$staffname = $this->session->staffname;
				$contactname = $contact[ 'name' ];
				$contactsurname = $contact[ 'surname' ];
				$loggedinuserid = $this->session->usr_id;
				$this->db->insert( 'logs', array(
					'date' => date( 'Y-m-d H:i:s' ),
					'detail' => ( '' . $message = sprintf( lang( 'changedpassword' ), $staffname, $contactname, $contactsurname ) . '' ),
					'staff_id' => $loggedinuserid,
					'customer_id' => $customer,
				) );
				$this->Contacts_Model->update( $id, $params ); 

				// send email to contact about password change
				$template = $this->Emails_Model->get_template('staff', 'customer_password_reset');
				$message_vars = array(
					'{email}' => $contact['email'],
					'{contact}' => $contact['name'].' '.$contact['surname'],
					'{email_signature}' => $template['from_name'],
				);
				$subject = strtr($template['subject'], $message_vars);
				$message = strtr($template['message'], $message_vars);

				$param = array(
					'from_name' => $template['from_name'],
					'email' => $contact['email'],
					'subject' => $subject,
					'message' => $message,
					'created' => date( "Y.m.d H:i:s" ),
					'status' => 1
				);
				if ($contact['email']) {
					$this->db->insert( 'email_queue', $param );
				}
				echo ' ' . $contact[ 'name' ] . ' ' . lang( 'passwordchanged' ) . '';
			}
		}
	}

	function remove_contact( $id ) {
		$contacts = $this->Contacts_Model->get_contacts( $id );
		if ( isset( $contacts[ 'id' ] ) ) {
			$this->Contacts_Model->delete( $id );
			echo lang( 'contactdeleted' );
		} else
			show_error( 'The contacts you are trying to delete does not exist.' );
	}
}