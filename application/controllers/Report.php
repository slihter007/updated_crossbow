<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class Report extends CIUIS_Controller {

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
		$data = $this->Settings_Model->get_settings_ciuis();
		$data[ 'settings' ] = $data;
		$data[ 'title' ] = $data['crm_name'].' '. lang('reports');
		$data[ 'tbs' ] = $this->db->count_all( 'notifications', array( 'markread' => ( '0' ) ) );
		$data[ 'bkt' ] = $this->Report_Model->bkt(); // daily total sales
		$data[ 'bht' ] = $this->Report_Model->bht(); // weekly sales
		$data[ 'ycr' ] = $this->Report_Model->ycr(); // yearly sales
		$data[ 'oyc' ] = $this->Report_Model->oyc(); // yearly sales
		$data[ 'oft' ] = $this->Report_Model->oft(); // invoice total due
		$data[ 'tef' ] = $this->Report_Model->tef(); // total invoices
		$data[ 'vgf' ] = $this->Report_Model->vgf();
		$data[ 'tbs' ] = $this->Report_Model->tbs();
		$data[ 'akt' ] = $this->Report_Model->akt();
		$data[ 'oak' ] = $this->Report_Model->oak();
		$data[ 'tfa' ] = $this->Report_Model->tfa();
		$data[ 'yms' ] = $this->Report_Model->yms();
		$data[ 'ofy' ] = ( $data[ 'tfa' ] > 0 ? number_format( ( $data[ 'tef' ] * 100 ) / $data[ 'tfa' ] ) : 0 );
		$data[ 'weekly_sales_chart_report' ] = json_encode( $this->Report_Model->weekly_sales_chart_report() );
		$data[ 'monthly_sales_graph' ] = $this->Report_Model->monthly_sales_graph();
		$data[ 'monthly_expense_graph' ] = $this->Report_Model->monthly_expenses();
		$data[ 'invoice_chart_by_status' ] = json_encode( $this->Report_Model->invoice_chart_by_status() );
		$data[ 'leads_by_leadsource' ] = json_encode( $this->Report_Model->leads_by_leadsource() );
		$data[ 'leads_to_win_by_leadsource' ] = json_encode( $this->Report_Model->leads_to_win_by_leadsource() );
		$data[ 'top_selling_staff_chart' ] = json_encode( $this->Report_Model->top_selling_staff_chart() );
		$data[ 'incomings_vs_outgoins' ] = json_encode( $this->Report_Model->incomings_vs_outgoins() );
		$data[ 'expenses_by_categories' ] = json_encode( $this->Report_Model->expenses_by_categories() );
		$data[ 'events' ] = $this->Events_Model->get_all_events();
		$this->load->view( 'report/index', $data );
	}

	function get_reports_data() {
		$data[ 'totalTickets' ] = $this->Report_Model->totalData('tickets');
		$data[ 'totalCustomers' ] = $this->Report_Model->totalData('customers');
		$data[ 'totalLeads' ] = $this->Report_Model->totalData('leads');
		$data[ 'totalProjects' ] = $this->Report_Model->totalData('projects');
		$data[ 'totalProducts' ] = $this->Report_Model->totalData('products');
		$data[ 'totalTasks' ] = $this->Report_Model->totalData('tasks');
		$data[ 'totalLeads' ] = $this->Report_Model->totalData('leads');
		$data[ 'totalOrders' ] = $this->Report_Model->totalData('orders');
		$data[ 'totalQotations' ] = 0;
		$data[ 'totalInvoices' ] = $this->Report_Model->totalData('invoices');
		$report = $this->Report_Model->weekly_incomings_vs_outgoings();
		$data['payments'] = $report['payments'];
		$data['expenses'] = $report['expenses'];
		$data['weekdays'] = array(lang('monday'), lang('tuesday'), lang('wednesday'), lang('thursday'), lang('friday'), lang('saturday'), lang('sunday'), );
		echo json_encode($data);
	}

	function get_timesheet_data() {
		$result = $this->Report_Model->get_timesheet();
		$timesheet = array();
		$totalT = 0;
		$total_h = $total_m = $total_s = 0;
		foreach ( $result as $field ) {
			$end_time = $field['end'];
			$date = new DateTime();
			if ($end_time == NULL) {
				$endTime = NULL;
				$end_time = $date->format('Y-m-d H:i:s');
			} else {
				$endTime = $field['end'];
				$end_time = $field['end'];
			}
			$date1 = new DateTime($field['start']);
			$diffs = $date1->diff(new DateTime($end_time));
			$h = $diffs->days * 24;
			$h += $diffs->h;
			$minutes = $diffs->i;
			$seconds = $diffs->s;
			if ($minutes < 10) {
				$minutes = '0'.$minutes;
			}
			if ($seconds < 10) {
				$seconds = '0'.$seconds;
			}
			if ($h < 10) {
				$h = '0'.$h;
			}
			$total = $h.':'.$minutes.':'.$seconds;
			$total_h += $h;
			$total_m += $minutes;
			$total_s += $seconds;
			$timesheet[] = array(
				'id' => $field[ 'id' ],
				'name' => $field[ 'name' ],
				'start_time' => $field['start'],
				'end_time' => $endTime,
				'total_time' => $total,
				'note' => $field[ 'note' ],
				'relation_id' => $field[ 'task_id' ],
				'staff' => $field[ 'staff' ],
				'staff_id' => $field[ 'staff_id' ],
				'avatar' => $field[ 'avatar' ],
			);
		}
		if ($total_s > 59) {
			$total_m += (int)($total_s / 60);
			$total_s = $total_s % 60;
		}
		if ($total_m > 59) {
			$total_h += (int)($total_m / 60);
			$total_m = $total_m % 60;
		}
		$data = array(
			'total' => $total_h.':'.$total_m.':'.$total_s,
			'timesheet' => $timesheet,
		);
		echo json_encode($data);
	}

	function expenses_payments_graph( $year ) {
		echo json_encode( $this->Report_Model->expenses_payments_graph( $year ) );
	}

	function customer_monthly_increase_chart( $month ) {
		echo json_encode( $this->Report_Model->customer_monthly_increase_chart( $month ) );
	}

	function lead_graph( $month ) {
		echo json_encode( $this->Report_Model->lead_graph( $month ) );
	}

	function test() {
		echo json_encode( $this->Report_Model->a1() );
	}
}