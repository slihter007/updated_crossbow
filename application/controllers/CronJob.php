<?php
class CronJob extends CI_Controller {

	public function __construct(){
        parent::__construct();

         //load the model  
        $this->load->model('Invoices_Model'); 
        $this->load->model('Emails_Model');
        $this->load->model('Settings_Model');
        $this->load->model('Expenses_Model');
        define( 'LANG', $this->Settings_Model->get_crm_lang() );
        define( 'currency', $this->Settings_Model->get_currency() );
        $this->lang->load( LANG, LANG );

    }

	function index(){
		header('Location: /');
	}

	function run() {
		foreach ($this->Invoices_Model->get_all_recurring() as $key => $value) {
			if($value['relation_type'] == 'invoice') {
				if(($value['end_date'] != 'Invalid date') && (strtotime(date("Y-m-d",strtotime($value['end_date']))) <= strtotime(date('Y-m-d')))) {
					continue;
				}
				
				$id = $value['relation'];
				$invv = $this->db->get_where( 'invoices', array( 'id' => $value['relation'] ) );
				$invv = $invv->result_array();
				$invv = end($invv);
				if($invv['last_recurring'] != NULL && date('Y-m-d',strtotime($invv['last_recurring'])) == date('Y-m-d')) {
					continue;
				}
				// Years
				if($value['type'] == '3' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'Years')) != date('Y-m-d')) {
					continue;
				}
				// Month
				if($value['type'] == '2' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'month')) != date('Y-m-d')) {
					continue;
				}
				// Week
				if($value['type'] == '1' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'week')) != date('Y-m-d')) {
					continue;
				}
				// Day
				if($value['type'] == '0' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'day')) != date('Y-m-d')) {
					continue;
				}
				$invoice = $this->Invoices_Model->get_invoices($id);
				$created = date('Y-m-d');
				$invoices = array(
					'token' => md5( uniqid() ),
					'no' => $invoice['no'],
					'serie' => $invoice['serie'],
					'customer_id' => $invoice['customer_id'],
					'staff_id' => $invoice['staff_id'],
					'status_id' => 3,
					'created' => $created,
					'last_recurring' => $created,
					'duedate' => date('Y-m-d' ,strtotime($created . '+30 days')), // +30 day
					'duenote' => $invoice['duenote'],
					'sub_total' => $invoice['sub_total'],
					'total_discount' => $invoice['total_discount'],
					'total_tax' => $invoice['total_tax'],
					'total' => $invoice['total'],
					'recurring' => $value['id'],
				);
				$items = $this->db->select('*')->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();
				$invoices_id = $this->Invoices_Model->recurring_invoice($invoices,$items);

				if ($invoices_id) {
					$this->Invoices_Model->update_recurring_date($id);
					$invoice = $this->Invoices_Model->get_invoice_detail( $invoices_id );
					$template = $this->Emails_Model->get_template('invoice', 'invoice_recurring');
					if ($template['status'] == 1) {
						$inv_number = 'INV' . '-' . str_pad( $invoices_id, 6, '0', STR_PAD_LEFT );
						$name = $invoice['customercompany'] ? $invoice['customercompany'] : $invoice['individualindividual'];
						$link = base_url( 'share/invoice/' . $invoice[ 'token' ] . '' );
						$message_vars = array(
							'{invoice_number}' => $inv_number,
							'{invoice_link}' => $link,
							'{invoice_status}' => lang( 'unpaid' ),
							'{email_signature}' => $template['from_name'],
							'{customer}' => $name,
							'{name}' => $invoice['staffmembername'],
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
			}
		}
		$this->expense_recurrings();
	}

	function expense_recurrings() {
		foreach ($this->Expenses_Model->get_all_recurring() as $key => $value) {
			if($value['relation_type'] == 'expense') {
				if(($value['end_date'] != 'Invalid date') && (strtotime(date("Y-m-d",strtotime($value['end_date']))) <= strtotime(date('Y-m-d')))) {
					continue;
				}
				$id = $value['relation'];
				$invv = $this->db->get_where( 'expenses', array( 'id' => $value['relation'] ) );
				$invv = $invv->result_array();
				$invv = end($invv);
				if($invv['last_recurring'] != NULL && date('Y-m-d',strtotime($invv['last_recurring'])) == date('Y-m-d')) {
					continue;
				}
				// Years
				if($value['type'] == '3' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'Years')) != date('Y-m-d')) {
					continue;
				}
				// Month
				if($value['type'] == '2' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'month')) != date('Y-m-d')) {
					continue;
				}
				// Week
				if($value['type'] == '1' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'week')) != date('Y-m-d')) {
					continue;
				}
				// Day
				if($value['type'] == '0' && date('Y-m-d',strtotime($invv['last_recurring'].' +'.$value['period'].'day')) != date('Y-m-d')) {
					continue;
				}
				$expense = $this->Expenses_Model->get_expenses($id);
				$expense_data = array(
					'category_id' => $expense['category_id'],
					'staff_id' => $expense['staff_id'],
					'customer_id' => $expense['customer_id'],
					'account_id' => $expense['account_id'],
					'title' => $expense['title'],
					'number' => $expense['number'],
					'date' => $expense['date'],
					'created' => date( 'Y-m-d H:i:s' ),
					'amount' => $expense['amount'],
					'total_tax' => $expense['total_tax'],
					'total_discount' => $expense['total_discount'],
					'sub_total' => $expense['sub_total'],
					'internal' => $expense['internal'],
					'last_recurring' => date('Y-m-d')
				);
				$items = $this->db->select('*')->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();
				$expense_id = $this->Expenses_Model->recurring_expense($expense_data, $items);

				if ($expense_id) {
					$this->Expenses_Model->update_recurring_date($id);
					$template = $this->Emails_Model->get_template('expense', 'expense_recurring');
					if ($template['status'] == 1) {
						$expense = $this->Expenses_Model->get_expenses( $expense_id );
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
							'{staff}' => $expense[ 'staff' ],
							'{expense_number}' => $appconfig['expense_prefix'].''.str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'],
							'{expense_title}' => $expense[ 'title' ],
							'{expense_category}' => $expense[ 'category' ],
							'{expense_date}' => $expense[ 'date' ],
							'{expense_description}' => $expense[ 'description' ],
							'{expense_amount}' => $expense[ 'amount' ],
							'{name}' => $expense[ 'staff' ],
							'{email_signature}' => $expense[ 'staffemail' ],
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);

						$email = $expense['staffemail'];
						if ($email) {
							$param = array(
								'from_name' => $template['from_name'],
								'email' => $email,
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" ),
								'attachments' => $path?$path:NULL
							);
							$this->db->insert( 'email_queue', $param );
						}
					}
					$template_c = $this->Emails_Model->get_template('expense', 'expense_consultant');
					if ($template['status'] == 1) {
						$expense = $this->Expenses_Model->get_expenses( $expense_id );
						$path = '';
						if ($template['attachment'] == '1') {
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
							'{staff}' => $expense[ 'staff' ],
							'{expense_number}' => $appconfig['expense_prefix'].''.str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'],
							'{expense_title}' => $expense[ 'title' ],
							'{expense_category}' => $expense[ 'category' ],
							'{expense_date}' => $expense[ 'date' ],
							'{expense_description}' => $expense[ 'description' ],
							'{expense_amount}' => $expense[ 'amount' ],
							'{name}' => $expense[ 'staff' ],
							'{email_signature}' => $expense[ 'staffemail' ],
						);
						$subject = strtr($template['subject'], $message_vars);
						$message = strtr($template['message'], $message_vars);

						$consultants = $this->Expenses_Model->get_consultants();
						$recipients = array();
						foreach ($consultants as $consultant) {
							$recipients[] = $consultant['email'];
						}
						if (count($recipients) > 0) {
							$param = array(
								'from_name' => $template['from_name'],
								'email' => serialize($recipients),
								'subject' => $subject,
								'message' => $message,
								'created' => date( "Y.m.d H:i:s" ),
								'attachments' => $path?$path:NULL
							);
							$this->db->insert( 'email_queue', $param );
						}
					}
				}
			}
		}
	}

	function emails() {
		$emails = $this->Emails_Model->get_emails();
		if ($emails) {
			foreach ($emails as $key => $email) {
				$this->load->library('mail');
				$data_email = @unserialize($email['email']);
				if ($data_email !== false) {
					$recipient = unserialize($email['email']);
				} else {
					$recipient = $email['email'];
				}
				$data = $this->mail->send_email($recipient, $email['from_name'], $email['subject'], $email['message'], $email['attachments']);
				if ($data['success'] == true) {
					$this->Emails_Model->email_sent($email['id']);
				}
			}
		}
	}

}