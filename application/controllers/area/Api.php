<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
header('Access-Control-Allow-Origin: *');
class Api extends AREA_Controller {

	function get_settings() {
		$settings = $this->Settings_Model->get_settings_ciuis();
		echo json_encode( $settings );
	}

	function get_projects() {
		$projects = $this->Projects_Model->get_all_projects_by_customer( $_SESSION[ 'customer' ] );
		$data_projects = array();
		foreach ( $projects as $project ) {
			$settings = $this->Settings_Model->get_settings_ciuis();
			$totaltasks = $this->Report_Model->totalprojecttasks( $project[ 'id' ] );
			$opentasks = $this->Report_Model->openprojecttasks( $project[ 'id' ] );
			$completetasks = $this->Report_Model->completeprojecttasks( $project[ 'id' ] );
			$progress = ( $totaltasks > 0 ? number_format( ( $completetasks * 100 ) / $totaltasks ) : 0 );
			$project_id = $project[ 'id' ];
			switch ( $project[ 'status' ] ) {
				case '1':
					$projectstatus = 'notstarted';
					$icon = 'notstarted.png';
					$status = lang( 'notstarted' );
					break;
				case '2':
					$projectstatus = 'started';
					$icon = 'started.png';
					$status = lang( 'started' );
					break;
				case '3':
					$projectstatus = 'percentage';
					$icon = 'percentage.png';
					$status = lang( 'percentage' );
					break;
				case '4':
					$projectstatus = 'cancelled';
					$icon = 'cancelled.png';
					$status = lang( 'cancelled' );
					break;
				case '5':
					$projectstatus = 'complete';
					$icon = 'complete.png';
					$status = lang( 'complete' );
					break;
			}
			if ($project[ 'status' ] == '5') {
				$projectstatus = 'complete';
				$icon = 'complete.png';
				$status = lang( 'completed' );
				$progress = 100;
			}
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$startdate = _rdate( $project[ 'start_date' ] );
					break;
				case 'dd.mm.yy':
					$startdate = _udate( $project[ 'start_date' ] );
					break;
				case 'yy-mm-dd':
					$startdate = _mdate( $project[ 'start_date' ] );
					break;
				case 'dd-mm-yy':
					$startdate = _cdate( $project[ 'start_date' ] );
					break;
				case 'yy/mm/dd':
					$startdate = _zdate( $project[ 'start_date' ] );
					break;
				case 'dd/mm/yy':
					$startdate = _kdate( $project[ 'start_date' ] );
					break;
			};
			$customer = ($project['customercompany'])?$project['customercompany']:$project['namesurname'];
			$enddate = $project[ 'deadline' ];
			$current_date = new DateTime( date( 'Y-m-d' ), new DateTimeZone( 'Asia/Dhaka' ) );
			$end_date = new DateTime( "$enddate", new DateTimeZone( 'Asia/Dhaka' ) );
			$interval = $current_date->diff( $end_date );
			$leftdays = $interval->format( '%a day(s)' );
			$members = $this->Projects_Model->get_members_index( $project_id );
			$milestones = $this->Projects_Model->get_all_project_milestones( $project_id );
			$data_projects[] = array(
				'id' => $project[ 'id' ],
				'project_id' => $project[ 'id' ],
				'name' => $project[ 'name' ],
				'pinned' => $project[ 'pinned' ],
				'progress' => $progress,
				'startdate' => $startdate,
				'leftdays' => $leftdays,
				'customer' => $customer,
				'status_icon' => $icon,
				'status' => $status,
				'status_class' => $projectstatus,
				'customer_id' => $project[ 'customer_id' ],
				'members' => $members,
				'milestones' => $milestones,
			);
		};
		echo json_encode( $data_projects );
	}

	function notes() {
		$relation_type = $this->uri->segment( 4 );
		$relation_id = $this->uri->segment( 5 );
		$notes = $this->db->select( '*,staff.staffname as notestaff,notes.id as id, customers.namesurname as customer_name, customers.company as customercompany, notes.created as note_created ' )->join( 'staff', 'notes.addedfrom = staff.id', 'left' )->join( 'customers', 'notes.customer_id = customers.id', 'left' )->order_by('notes.id', 'desc')->get_where( 'notes', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_projectnotes = array();
		foreach ( $notes as $note ) {
			$customer = ($note['customer_name']?$note['customer_name']:$note['customercompany']);
			$noteby = $customer?$customer:$note[ 'notestaff' ];
			$data_projectnotes[] = array(
				'id' => $note[ 'id' ],
				'description' => $note[ 'description' ],
				'staffid' => $note[ 'addedfrom' ],
				'staff' => $note[ 'notestaff' ],
				'date' => _adate($note[ 'note_created' ]),
				'customer' => $customer,
				'noteby' => $noteby 
			);
		};
		echo json_encode( $data_projectnotes );
	}

	function projectfiles( $id ) {
		if (isset($id)) {
			$files = $this->Projects_Model->get_project_files( $id );
			$data = array();
			foreach ($files as $file) {
				$ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
				$type = 'file';
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$type = 'image';
				}
				if ($ext == 'pdf') {
					$type = 'pdf';
				}
				if ($ext == 'zip' || $ext == 'rar' || $ext == 'tar') {
					$type = 'archive';
				}
				if ($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif') {
					$display = true;
				} else {
					$display = false;
				}
				if ($ext == 'pdf') {
					$pdf = true;
				} else {
					$pdf = false;
				}
				if ($file['is_old'] == '1') {
					$path = base_url('uploads/files/'.$file['file_name']);
				} else {
					$path = base_url('uploads/files/projects/'.$id.'/'.$file['file_name']);
				}
				$data[] = array(
					'id' => $file['id'],
					'project_id' => $file['relation'],
					'file_name' => $file['file_name'],
					'created' => $file['created'],
					'display' => $display,
					'pdf' => $pdf,
					'type' => $type,
					'path' => $path,
				);
			}
			echo json_encode($data);
		}
	}

	function get_projectdetail( $id ) {
		$project = $this->Projects_Model->get_projects( $id );
		$settings = $this->Settings_Model->get_settings_ciuis();
		$milestones = $this->Projects_Model->get_all_project_milestones( $id );
		$projectmembers = $this->Projects_Model->get_members( $id );
		$project_logs = $this->Logs_Model->project_logs( $id );
		$totaltasks = $this->Report_Model->totalprojecttasks( $id );
		$opentasks = $this->Report_Model->openprojecttasks( $id );
		$completetasks = $this->Report_Model->completeprojecttasks( $id );
		$progress = ( $totaltasks > 0 ? number_format( ( $completetasks * 100 ) / $totaltasks ) : 0 );
		if ( $project[ 'customercompany' ] === NULL ) {
			$customer = $project[ 'namesurname' ];
		} else $customer = $project[ 'customercompany' ];
		$enddate = $project[ 'deadline' ];
		$current_date = new DateTime( date( 'Y-m-d' ), new DateTimeZone( $settings[ 'default_timezone' ] ) );
		$end_date = new DateTime( "$enddate", new DateTimeZone( $settings[ 'default_timezone' ] ) );
		$interval = $current_date->diff( $end_date );
		$project_left_date = $interval->format( '%a day(s)' );
		if ( date( "Y-m-d" ) > $project[ 'deadline' ] ) {
			$ldt = 'Time\'s up!';
		} else $ldt = $project_left_date;
		switch ( $project[ 'status' ] ) {
			case '1':
				$status = lang( 'notstarted' );
				break;
			case '2':
				$status = lang( 'started' );
				break;
			case '3':
				$status = lang( 'percentage' );
				break;
			case '4':
				$status = lang( 'cancelled' );
				break;
			case '5':
				$status = lang( 'complete' );
				break;
		};
		switch ( $settings[ 'dateformat' ] ) {
			case 'yy.mm.dd':
				$start = _rdate( $project[ 'start_date' ] );
				$deadline = _rdate( $project[ 'deadline' ] );
				$created = _rdate( $project[ 'created' ] );
				$finished = _rdate( $project[ 'finished' ] );

				break;
			case 'dd.mm.yy':
				$start = _udate( $project[ 'start_date' ] );
				$deadline = _udate( $project[ 'deadline' ] );
				$created = _udate( $project[ 'created' ] );
				$finished = _udate( $project[ 'finished' ] );
				break;
			case 'yy-mm-dd':
				$start = _mdate( $project[ 'start_date' ] );
				$deadline = _mdate( $project[ 'deadline' ] );
				$created = _mdate( $project[ 'created' ] );
				$finished = _mdate( $project[ 'finished' ] );
				break;
			case 'dd-mm-yy':
				$start = _cdate( $project[ 'start_date' ] );
				$deadline = _cdate( $project[ 'deadline' ] );
				$created = _cdate( $project[ 'created' ] );
				$finished = _cdate( $project[ 'finished' ] );
				break;
			case 'yy/mm/dd':
				$start = _zdate( $project[ 'start_date' ] );
				$deadline = _zdate( $project[ 'deadline' ] );
				$created = _zdate( $project[ 'created' ] );
				$finished = _zdate( $project[ 'finished' ] );
				break;
			case 'dd/mm/yy':
				$start = _kdate( $project[ 'start_date' ] );
				$deadline = _kdate( $project[ 'deadline' ] );
				$created = _kdate( $project[ 'created' ] );
				$finished = _kdate( $project[ 'finished' ] );
				break;
		};
		if ( $project[ 'invoice_id' ] > 0 ) {
			$billed = lang( 'yes' );
		} else {
			$billed = lang( 'no' );
		}
		$data_projectdetail = array(
			'id' => $project[ 'id' ],
			'name' => $project[ 'name' ],
			'description' => $project[ 'description' ],
			'start' => $start,
			'deadline' => $deadline,
			'created' => $created,
			'finished' => $finished,
			'status' => $status,
			'progress' => $progress,
			'totaltasks' => $totaltasks,
			'opentasks' => $opentasks,
			'completetasks' => $completetasks,
			'customer' => $customer,
			'ldt' => $ldt,
			'billed' => $billed,
			'milestones' => $milestones,
			'members' => $projectmembers,
			'project_logs' => $project_logs
		);
		echo json_encode( $data_projectdetail );
	}

	function get_projecttasks( $id ) {
		$tasks = $this->Tasks_Model->get_project_tasks( $id );
		$data_projecttasks = array();
		foreach ( $tasks as $task ) {

			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $task[ 'status_id' ] ) {
				case '1':
					$status = lang( 'open' );
					$taskdone = '';
					break;
				case '2':
					$status = lang( 'inprogress' );
					$taskdone = '';
					break;
				case '3':
					$status = lang( 'waiting' );
					$taskdone = '';
					break;
				case '4':
					$status = lang( 'complete' );
					$taskdone = 'done';
					break;
				case '5':
					$status = lang( 'cancelled' );
					$taskdone = '';
					break;
			};
			switch ( $task[ 'relation_type' ] ) {
				case 'project':
					$relationtype = 'Project';
					break;
				case 'ticket':
					$relationtype = 'Tıcket';
					break;
				case 'proposal':
					$relationtype = 'Proposal';
					break;
			};
			switch ( $task[ 'priority' ] ) {
				case '0':
					$priority = lang( 'low' );
					break;
				case '1':
					$priority = lang( 'medium' );
					break;
				case '2':
					$priority = lang( 'high' );
					break;
			};
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$startdate = _rdate( $task[ 'startdate' ] );
					$duedate = _rdate( $task[ 'duedate' ] );
					$created = _rdate( $task[ 'created' ] );
					$datefinished = _rdate( $task[ 'datefinished' ] );

					break;
				case 'dd.mm.yy':
					$startdate = _udate( $task[ 'startdate' ] );
					$duedate = _udate( $task[ 'duedate' ] );
					$created = _udate( $task[ 'created' ] );
					$datefinished = _udate( $task[ 'datefinished' ] );
					break;
				case 'yy-mm-dd':
					$startdate = _mdate( $task[ 'startdate' ] );
					$duedate = _mdate( $task[ 'duedate' ] );
					$created = _mdate( $task[ 'created' ] );
					$datefinished = _mdate( $task[ 'datefinished' ] );
					break;
				case 'dd-mm-yy':
					$startdate = _cdate( $task[ 'startdate' ] );
					$duedate = _cdate( $task[ 'duedate' ] );
					$created = _cdate( $task[ 'created' ] );
					$datefinished = _cdate( $task[ 'datefinished' ] );
					break;
				case 'yy/mm/dd':
					$startdate = _zdate( $task[ 'startdate' ] );
					$duedate = _zdate( $task[ 'duedate' ] );
					$created = _zdate( $task[ 'created' ] );
					$datefinished = _zdate( $task[ 'datefinished' ] );
					break;
				case 'dd/mm/yy':
					$startdate = _kdate( $task[ 'startdate' ] );
					$duedate = _kdate( $task[ 'duedate' ] );
					$created = _kdate( $task[ 'created' ] );
					$datefinished = _kdate( $task[ 'datefinished' ] );
					break;
			};
			$data_projecttasks[] = array(
				'id' => $task[ 'id' ],
				'name' => $task[ 'name' ],
				'relationtype' => $relationtype,
				'status' => $status,
				'status_id' => $task[ 'status_id' ],
				'duedate' => $duedate,
				'startdate' => $startdate,
				'done' => $taskdone,
			);
		};
		echo json_encode( $data_projecttasks );
	}

	function get_projectmilestones( $id ) {
		$milestones = $this->Projects_Model->get_all_project_milestones( $id );
		$data_milestones = array();
		foreach ( $milestones as $milestone ) {
			if ( date( "Y-m-d" ) > $milestone[ 'duedate' ] ) {
				$status = 'is-completed';
			}
			if ( date( "Y-m-d" ) < $milestone[ 'duedate' ] ) {
				$status = 'is-future';
			};
			$tasks = $this->Projects_Model->get_all_project_milestones_task( $milestone[ 'id' ] );
			$data_milestones[] = array(
				'id' => $milestone[ 'id' ],
				'name' => $milestone[ 'name' ],
				'duedate' => $milestone[ 'duedate' ],
				'description' => $milestone[ 'description' ],
				'order' => $milestone[ 'order' ],
				'due' => $milestone[ 'duedate' ],
				'status' => $status,
				'tasks' => $tasks,
			);
		};
		echo json_encode( $data_milestones );
	}

	function get_projectfiles( $id ) {
		$files = $this->Projects_Model->get_project_files( $id );
		$data_files = array();
		foreach ( $files as $file ) {
			$data_files[] = array(
				'id' => $file[ 'id' ],
				'name' => $file[ 'file_name' ],
			);
		};
		echo json_encode( $data_files );
	}

	function get_projecttimelogs( $id ) {
		$timelogs = $this->Projects_Model->get_project_time_log( $id );
		$data_timelogs = array();
		foreach ( $timelogs as $timelog ) {
			$task = $this->Tasks_Model->get_task( $timelog[ 'task_id' ] );
			$start = $timelog[ 'start' ];
			$end = $timelog[ 'end' ];
			$timed_minute = intval( abs( strtotime( $start ) - strtotime( $end ) ) / 60 );
			$amount = $timed_minute / 60 * $task[ 'hourly_rate' ];
			if ( $task[ 'status_id' ] != 5 ) {
				$data_timelogs[] = array(
					'id' => $timelog[ 'id' ],
					'start' => $timelog[ 'start' ],
					'end' => $timelog[ 'end' ],
					'staff' => $timelog[ 'staffmember' ],
					'timed' => $timed_minute,
					'amount' => $amount,
				);
			}
		};
		echo json_encode( $data_timelogs );
	}

	function get_tasktimelogs( $id ) {
		$timelogs = $this->Tasks_Model->get_task_time_log( $id );
		$data_timelogs = array();
		foreach ( $timelogs as $timelog ) {
			$task = $this->Tasks_Model->get_task( $id );
			$start = $timelog[ 'start' ];
			$end = $timelog[ 'end' ];
			$timed_minute = intval( abs( strtotime( $start ) - strtotime( $end ) ) / 60 );
			$amount = $timed_minute / 60 * $task[ 'hourly_rate' ];
			if ( $task[ 'status_id' ] != 5 ) {
				$data_timelogs[] = array(
					'id' => $timelog[ 'id' ],
					'start' => $timelog[ 'start' ],
					'end' => $timelog[ 'end' ],
					'staff' => $timelog[ 'staffmember' ],
					'timed' => $timed_minute,
					'amount' => $amount,
				);
			};
		};
		echo json_encode( $data_timelogs );
	}

	function get_milestones() {
		$milestones = $this->Projects_Model->get_all_milestones();
		$data_milestones = array();
		foreach ( $milestones as $milestone ) {
			$data_milestones[] = array(
				'id' => $milestone[ 'id' ],
				'milestone_id' => $milestone[ 'id' ],
				'name' => $milestone[ 'name' ],
				'project_id' => $milestone[ 'project_id' ],
			);
		};
		echo json_encode( $data_milestones );
	}




	function get_stats() {
		$customer_debt = $this->Area_Model->customerdebt();
		if ( isset( $customer_debt ) ) {
			$customer_debit = $customer_debt;
		} else {
			$customer_debit = 0;
		}
		$data_stats = array(
			'newnotification' => $this->Area_Model->newnotification(),
			'customer_debt' => $customer_debit,
			'chart_data' => $this->Report_Model->customer_annual_sales_chart( $_SESSION[ 'customer' ] ),
		);
		echo json_encode( $data_stats );
	}

	function get_staff() {
		$staffs = $this->Staff_Model->get_all_staff();
		$data_staffs = array();
		foreach ( $staffs as $staff ) {
			$data_staffs[] = array(
				'id' => $staff[ 'id' ],
				'name' => $staff[ 'staffname' ],
				'avatar' => $staff[ 'staffavatar' ],
				'department' => $staff[ 'department' ],
				'phone' => $staff[ 'phone' ],
				'address' => $staff[ 'address' ],
				'email' => $staff[ 'email' ],
				'birthday' => $staff[ 'birthday' ],
				'last_login' => $staff[ 'last_login' ],
				'appointment_availability' => $staff[ 'appointment_availability' ],
			);
		};
		echo json_encode( $data_staffs );
	}

	function get_departments() {
		$departments = $this->Settings_Model->get_departments();
		$data_departments = array();
		foreach ( $departments as $department ) {
			$data_departments[] = array(
				'id' => $department[ 'id' ],
				'name' => $department[ 'name' ],
			);
		};
		echo json_encode( $data_departments );
	}

	function get_proposals() {
		$proposals = $this->Proposals_Model->get_all_proposals_by_customer( $_SESSION[ 'customer' ] );
		$data_proposals = array();
		foreach ( $proposals as $proposal ) {
			$pro = $this->Proposals_Model->get_proposals( $proposal[ 'id' ], $proposal[ 'relation_type' ] );
			if ( $pro[ 'relation_type' ] == 'customer' ) {
				if ( $pro[ 'customercompany' ] === NULL ) {
					$customer = $pro[ 'namesurname' ];
				} else $customer = $pro[ 'customercompany' ];
			}
			if ( $pro[ 'relation_type' ] == 'lead' ) {
				$customer = $pro[ 'leadname' ];
			}
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$date = _rdate( $proposal[ 'date' ] );
					$opentill = _rdate( $proposal[ 'opentill' ] );
					break;
				case 'dd.mm.yy':
					$date = _udate( $proposal[ 'date' ] );
					$opentill = _udate( $proposal[ 'opentill' ] );
					break;
				case 'yy-mm-dd':
					$date = _mdate( $proposal[ 'date' ] );
					$opentill = _mdate( $proposal[ 'opentill' ] );
					break;
				case 'dd-mm-yy':
					$date = _cdate( $proposal[ 'date' ] );
					$opentill = _cdate( $proposal[ 'opentill' ] );
					break;
				case 'yy/mm/dd':
					$date = _zdate( $proposal[ 'date' ] );
					$opentill = _zdate( $proposal[ 'opentill' ] );
					break;
				case 'dd/mm/yy':
					$date = _kdate( $proposal[ 'date' ] );
					$opentill = _kdate( $proposal[ 'opentill' ] );
					break;
			};
			switch ( $proposal[ 'status_id' ] ) {
				case '1':
					$status = lang( 'draft' );
					$class = 'proposal-status-accepted';
					break;
				case '2':
					$status = lang( 'sent' );
					$class = 'proposal-status-sent';
					break;
				case '3':
					$status = lang( 'open' );
					$class = 'proposal-status-open';
					break;
				case '4':
					$status = lang( 'revised' );
					$class = 'proposal-status-revised';
					break;
				case '5':
					$status = lang( 'declined' );
					$class = 'proposal-status-declined';
					break;
				case '6':
					$status = lang( 'accepted' );
					$class = 'proposal-status-accepted';
					break;

			};
			$data_proposals[] = array(
				'id' => $proposal[ 'id' ],
				'token' => $proposal[ 'token' ],
				'assigned' => $proposal[ 'assigned' ],
				'subject' => $proposal[ 'subject' ],
				'customer' => $customer,
				'relation_type' => $proposal[ 'relation_type' ],
				'relation' => $proposal[ 'relation' ],
				'date' => $date,
				'opentill' => $opentill,
				'status' => $status,
				'status_id' => $proposal[ 'status_id' ],
				'staff' => $proposal[ 'staffmembername' ],
				'staffavatar' => $proposal[ 'staffavatar' ],
				'total' => $proposal[ 'total' ],
				'class' => $class,
				'' . lang( 'filterbystatus' ) . '' => $status,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
				'' . lang( 'filterbyassigned' ) . '' => $proposal[ 'staffmembername' ],
			);
		};
		echo json_encode( $data_proposals );
	}

	function get_invoices() {
		$invoices = $this->Invoices_Model->get_all_invoices_by_customer( $_SESSION[ 'customer' ] );
		$data_invoices = array();
		foreach ( $invoices as $invoice ) {
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$created = _rdate( $invoice[ 'created' ] );
					$duedate = _rdate( $invoice[ 'duedate' ] );
					break;
				case 'dd.mm.yy':
					$created = _udate( $invoice[ 'created' ] );
					$duedate = _udate( $invoice[ 'duedate' ] );
					break;
				case 'yy-mm-dd':
					$created = _mdate( $invoice[ 'created' ] );
					$duedate = _mdate( $invoice[ 'duedate' ] );
					break;
				case 'dd-mm-yy':
					$created = _cdate( $invoice[ 'created' ] );
					$duedate = _cdate( $invoice[ 'duedate' ] );
					break;
				case 'yy/mm/dd':
					$created = _zdate( $invoice[ 'created' ] );
					$duedate = _zdate( $invoice[ 'duedate' ] );
					break;
				case 'dd/mm/yy':
					$created = _kdate( $invoice[ 'created' ] );
					$duedate = _kdate( $invoice[ 'duedate' ] );
					break;
			};
			if ( $invoice[ 'duedate' ] == 0000 - 00 - 00 ) {
				$realduedate = lang('no_due_date');
			} else $realduedate = $duedate;
			$totalx = $invoice[ 'total' ];
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(invoice_id =' . $invoice[ 'id' ] . ') ' );
			$paytotal = $this->db->get();
			$balance = $totalx - $paytotal->row()->amount;
			if ( $balance > 0 ) {
				$invoicestatus = '';
			} else $invoicestatus = lang( 'paidinv' );
			$color = 'success';;
			if ( $paytotal->row()->amount < $invoice[ 'total' ] && $paytotal->row()->amount > 0 && $invoice[ 'status_id' ] == 3 ) {
				$invoicestatus = lang( 'partial' );
				$color = 'warning';
			} else {
				if ( $paytotal->row()->amount < $invoice[ 'total' ] && $paytotal->row()->amount > 0 ) {
					$invoicestatus = lang( 'partial' );
					$color = 'warning';
				}
				if ( $invoice[ 'status_id' ] == 3 ) {
					$invoicestatus = lang( 'unpaid' );
					$color = 'danger';
				}
			}
			if ( $invoice[ 'status_id' ] == 1 ) {
				$invoicestatus = lang( 'draft' );
				$color = 'muted';
			}
			if ( $invoice[ 'status_id' ] == 4 ) {
				$invoicestatus = lang( 'cancelled' );
				$color = 'danger';
			}
			if ( $invoice[ 'customercompany' ] === NULL ) {
				$customer = $invoice[ 'individual' ];
			} else $customer = $invoice[ 'customercompany' ];
			$appconfig = get_appconfig();
			$data_invoices[] = array(
				'id' => $invoice[ 'id' ],
				'token' => $invoice[ 'token' ],
				'prefix' => $appconfig['inv_prefix'], 
				'longid' => str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['inv_suffix'],
				'created' => $created,
				'duedate' => $realduedate,
				'customer' => $customer,
				'customer_id' => $invoice[ 'customer_id' ],
				'total' => $invoice[ 'total' ],
				'status' => $invoicestatus,
				'color' => $color,
				'' . lang( 'filterbystatus' ) . '' => $invoicestatus,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
			);
		};
		echo json_encode( $data_invoices );
	}

	function get_invoicedetails( $id ) {
		$invoice = $this->Invoices_Model->get_invoices( $id );
		$fatop = $this->Invoices_Model->get_items_invoices( $id );
		$tadtu = $this->Invoices_Model->get_paid_invoices( $id );
		$total = $invoice[ 'total' ];
		$today = time();
		$duedate = strtotime( $invoice[ 'duedate' ] ); // or your date as well
		$created = strtotime( $invoice[ 'created' ] );
		$paymentday = $duedate - $created; // Bunun sonucu 14 gün olcak
		$paymentx = $today - $created;
		$datepaymentnet = $paymentday - $paymentx;
		if ( $invoice[ 'duedate' ] == 0 ) {
			$duedate_text = lang('no_due_date');
		} else {
			if ( $datepaymentnet < 0 ) {
				$duedate_text = lang( 'overdue' );
				$duedate_text = '' . floor( $datepaymentnet / ( 60 * 60 * 24 ) ) . ' days';

			} else {
				$duedate_text = lang( 'payableafter' ) . floor( $datepaymentnet / ( 60 * 60 * 24 ) ) . ' ' . lang( 'day' ) . '';

			}
		}
		if ( $invoice[ 'datesend' ] == 0 ) {
			$mail_status = lang( 'notyetbeensent' );
		} else $mail_status = _adate( $invoice[ 'datesend' ] );
		$kalan = $total - $tadtu->row()->amount;
		$net_balance = $kalan;
		if ( $tadtu->row()->amount < $total && $tadtu->row()->amount > 0 ) {
			$partial_is = true;
		} else $partial_is = false;
		$payments = $this->db->select( '*,accounts.name as accountname,payments.id as id ' )->join( 'accounts', 'payments.account_id = accounts.id', 'left' )->get_where( 'payments', array( 'invoice_id' => $id ) )->result_array();
		$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'invoice', 'relation' => $id ) )->result_array();

		if ( $invoice[ 'type' ] == 1 ) {
			$customer = $invoice[ 'individual' ];
		} else $customer = $invoice[ 'customercompany' ];
		$appconfig = get_appconfig();

		$properties = array(
			'invoice_id' => '' . $appconfig['inv_prefix'] . '' . str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ) . $appconfig['inv_suffix'].'',
			'customer' => $customer,
			'customer_address' => $invoice[ 'customeraddress' ],
			'customer_phone' => $invoice[ 'phone' ],
			'invoice_staff' => $invoice[ 'staffmembername' ],

		); 

		$invoice_details = array(
			'id' => $invoice[ 'id' ],
			'token' => $invoice[ 'token' ],
			'sub_total' => $invoice[ 'sub_total' ],
			'total_discount' => $invoice[ 'total_discount' ],
			'total_tax' => $invoice[ 'total_tax' ],
			'total' => $invoice[ 'total' ],
			'no' => $invoice[ 'no' ],
			'serie' => $invoice[ 'serie' ],
			'created' => date( DATE_ISO8601, strtotime( $invoice[ 'created' ] ) ),
			'duedate' => $invoice[ 'duedate' ],
			'customer' => $invoice[ 'customer_id' ],
			'datepayment' => $invoice[ 'datepayment' ],
			'duenote' => $invoice[ 'duenote' ],
			'status_id' => $invoice[ 'status_id' ],
			'duedate_text' => $duedate_text,
			'mail_status' => $mail_status,
			'balance' => $net_balance,
			'partial_is' => $partial_is,
			'items' => $items,
			'payments' => $payments,
			// Recurring Invoice
			'recurring_endDate' => $invoice[ 'recurring_endDate' ] ? date( DATE_ISO8601, strtotime( $invoice[ 'recurring_endDate' ] ) ) : '',
			'recurring_id' => $invoice[ 'recurring_id' ],
			'recurring_status' => $invoice[ 'recurring_status' ] == 0 ? true : false,
			'recurring_period' => $invoice[ 'recurring_period' ],
			'recurring_type' => $invoice[ 'recurring_type' ] ? $invoice[ 'recurring_type' ] : 0,
			// END Recurring Invoice
			'payments' => $payments,
			'properties' => $properties

		);
		echo json_encode( $invoice_details );
	}

	function get_discussions($relation_type,$relation_id) {
		$discussions = $this->db->select( '*,contacts.name as discussion_contact_name, contacts.surname as discussion_contact_surname, staff.staffname as discussion_staff,discussions.id as id ' )->join( 'staff', 'discussions.staff_id = staff.id', 'left' )->join( 'contacts', 'discussions.contact_id = contacts.id', 'left' )->get_where( 'discussions', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_discussions = array();

		foreach ( $discussions as $discussion ) {
			$comments = $this->db->get_where( 'discussion_comments', array( 'discussion_id' => $discussion[ 'id' ] ) )->result_array();
			$data_discussions[] = array(
				'id' => $discussion[ 'id' ],
				'subject' => $discussion[ 'subject' ],
				'description' => $discussion[ 'description' ],
				'datecreated' => date( DATE_ISO8601, strtotime( $discussion[ 'datecreated' ] ) ),
				'staff_id' => $discussion[ 'staff_id' ],
				'staff' => $discussion[ 'discussion_staff' ],
				'contact_id' => $discussion[ 'contact_id' ],
				'contact' => '' . $discussion[ 'discussion_contact_name' ] . ' ' . $discussion[ 'discussion_contact_surname' ] . '',
				'comments' => $comments,
			);
		};
		echo json_encode( $data_discussions );
	}

	function add_discussion_comment() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'discussion_id' => $_POST[ 'discussion_id' ],
				'content' => $_POST[ 'content' ],
				'staff_id' => $_POST[ 'staff_id' ],
				'contact_id' => $_POST[ 'contact_id' ],
				'full_name' => $_POST[ 'full_name' ],
				'created' => date( 'Y-m-d H:i:s' ),
			);
			$this->db->insert( 'discussion_comments', $params );
			$data[ 'insert_id' ] = $this->db->insert_id();;
			echo json_encode( $data );
		}
	}

	function get_notifications() {
		$notifications = $this->Area_Model->get_all_notifications();
		$data_notifications = array();
		foreach ( $notifications as $notification ) {
			switch ( $notification[ 'customerread' ] ) { 
				case 0:
					$read = true;
					break;
				case 1:
					$read = false;
					break;
			};
			$data_notifications[] = array(
				'id' => $notification[ 'notifyid' ],
				'target' => $notification[ 'target' ],
				'date' => tes_ciuis( $notification[ 'date' ] ),
				'detail' => $notification[ 'detail' ],
				'perres' => $notification[ 'perres' ],
				'read' => $read,
			);
		};
		echo json_encode( $data_notifications );
	}

	function get_tickets() {
		$tickets = $this->Tickets_Model->get_all_tickets_by_customer( $_SESSION[ 'contact_id' ] );
		$data_tickets = array();
		foreach ( $tickets as $ticket ) {
			switch ( $ticket[ 'priority' ] ) {
				case '1':
					$priority = lang( 'low' );
					break;
				case '2':
					$priority = lang( 'medium' );
					break;
				case '3':
					$priority = lang( 'high' );
					break;
			};
			$data_tickets[] = array(
				'id' => $ticket[ 'id' ],
				'subject' => $ticket[ 'subject' ],
				'message' => $ticket[ 'message' ],
				'staff_id' => $ticket[ 'staff_id' ],
				'contactname' => '' . $ticket[ 'contactname' ] . ' ' . $ticket[ 'contactsurname' ] . '',
				'priority' => $priority,
				'priority_id' => $ticket[ 'priority' ],
				'lastreply' => $ticket[ 'lastreply' ],
				'status_id' => $ticket[ 'status_id' ],
				'customer_id' => $ticket[ 'customer_id' ],
			);
		};
		echo json_encode( $data_tickets );
	}

	function get_ticket( $id ) {
		$ticket = $this->Tickets_Model->get_tickets( $id );
		switch ( $ticket[ 'priority' ] ) {
			case '1':
				$priority = lang( 'low' );
				break;
			case '2':
				$priority = lang( 'medium' );
				break;
			case '3':
				$priority = lang( 'high' );
				break;
		};
		switch ( $ticket[ 'status_id' ] ) {
			case '1':
				$status = lang( 'open' );
				break;
			case '2':
				$status = lang( 'inprogress' );
				break;
			case '3':
				$status = lang( 'answered' );
				break;
			case '4':
				$status = lang( 'closed' );
				break;
		};
		if ( $ticket[ 'type' ] == 0 ) {
			$customer = $ticket[ 'company' ];
		} else $customer = $ticket[ 'namesurname' ];
		$data_ticketdetails = array(
			'id' => $ticket[ 'id' ],
			'subject' => $ticket[ 'subject' ],
			'message' => $ticket[ 'message' ],
			'staff_id' => $ticket[ 'staff_id' ],
			'contact_id' => $ticket[ 'contact_id' ],
			'contactname' => '' . $ticket[ 'contactname' ] . ' ' . $ticket[ 'contactsurname' ] . '',
			'priority' => $priority,
			'priority_id' => $ticket[ 'priority' ],
			'lastreply' => $ticket[ 'lastreply' ],
			'status' => $status,
			'status_id' => $ticket[ 'status_id' ],
			'customer_id' => $ticket[ 'customer_id' ],
			'department' => $ticket[ 'department' ],
			'opened_date' => _adate( $ticket[ 'date' ] ),
			'last_reply_date' => _adate( $ticket[ 'lastreply' ] ),
			'attachment' => $ticket[ 'attachment' ],
			'customer' => $customer,
			'assigned_staff_name' => $ticket[ 'staffmembername' ],
			'replies' => $this->db->get_where( 'ticketreplies', array( 'ticket_id' => $id ) )->result_array(),
		);
		echo json_encode( $data_ticketdetails );
	}

	function get_logs_by_customerId() {
		$logs = $this->Logs_Model->get_logs_by_customerId( $_SESSION[ 'customer' ] );
		$data_logs = array();
		foreach ( $logs as $log ) {
			$data_logs[] = array(
				'logdate' => _adate( $log[ 'date' ] ),
				'date' => tes_ciuis( $log[ 'date' ] ),
				'detail' => $log[ 'detail' ],
				'customer_id' => $log[ 'customer_id' ],
				'project_id' => $log[ 'project_id' ],
				'staff_id' => $log[ 'staff_id' ],
			);
		};
		echo json_encode( $data_logs );
	}

	function get_contacts() {
		$contacts = $this->Contacts_Model->get_all_contacts();
		$data_contacts = array();
		foreach ( $contacts as $contact ) {
			$data_contacts[] = array(
				'id' => $contact[ 'id' ],
				'customer_id' => $contact[ 'customer_id' ],
				'name' => '' . $contact[ 'name' ] . ' ' . $contact[ 'surname' ] . '',
				'email' => $contact[ 'email' ],
				'phone' => $contact[ 'phone' ],
				'username' => $contact[ 'username' ],
				'address' => $contact[ 'address' ],
			);
		};
		echo json_encode( $data_contacts );
	}

	function get_leftmenu() {
		$all_menu = array(
			'1' => array(
				'title' => lang( 'x_menu_panel' ),
				'show_staff' => 0,
				'url' => base_url( 'area/panel' ),
				'icon' => 'ion-ios-analytics-outline',
				'path' => null,
				'show' => 'true'
			),
			'2' => array(
				'title' => lang( 'x_menu_projects' ),
				'show_staff' => 0,
				'url' => base_url( 'area/projects' ),
				'icon' => 'ico-ciuis-projects',
				'path' => 'projects',
				'show' => 'false'
			),
			'3' => array(
				'title' => lang( 'x_menu_invoices' ),
				'show_staff' => 0,
				'url' => base_url( 'area/invoices' ),
				'icon' => 'ico-ciuis-invoices',
				'path' => 'invoices',
				'show' => 'false'
			),
			'4' => array(
				'title' => lang( 'x_menu_proposals' ),
				'show_staff' => 0,
				'url' => base_url( 'area/proposals' ),
				'icon' => 'ico-ciuis-proposals',
				'path' => 'proposals',
				'show' => 'false'
			),
			'5' => array(
				'title' => lang( 'x_menu_expenses' ),
				'show_staff' => 0,
				'url' => base_url( 'area/expenses' ),
				'icon' => 'ico-ciuis-expenses',
				'path' => 'expenses',
				'show' => 'false'
			),
			'6' => array(
				'title' => lang( 'x_menu_tickets' ),
				'show_staff' => 0,
				'url' => base_url( 'area/tickets' ),
				'icon' => 'ico-ciuis-supports',
				'path' => 'tickets',
				'show' => 'false'
			),

		);

		$data_left_menu = array();
		foreach ( $all_menu as $menu ) {
			if ( $this->Privileges_Model->contact_has_privilege( $menu[ 'path' ] ) || $menu[ 'show' ] != 'false' ) {
				$show = true;
			} else {
				$show = false;
			}
			$data_left_menu[] = array(
				'title' => $menu[ 'title' ],
				'show_staff' => $menu[ 'show_staff' ],
				'url' => $menu[ 'url' ],
				'icon' => $menu[ 'icon' ],
				'path' => $menu[ 'path' ],
				'show' => $show
			);
		}

		echo json_encode( $data_left_menu );
	}

	function get_notes() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$notes = $this->db->select( '*,staff.staffname as notestaff,notes.id as id ' )->join( 'staff', 'notes.addedfrom = staff.id', 'left' )->get_where( 'notes', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_projectnotes = array();
		foreach ( $notes as $note ) {
			$data_projectnotes[] = array(
				'id' => $note[ 'id' ],
				'description' => $note[ 'description' ],
				'staffid' => $note[ 'addedfrom' ],
				'staff' => $note[ 'notestaff' ],
				'date' => _adate( $note[ 'created' ] ),
			);
		};
		echo json_encode( $data_projectnotes );


	}

	function get_expenses_by_relation() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$expenses = $this->Expenses_Model->get_all_expenses_by_relation( $relation_type, $relation_id );
		$data_expenses = array();
		foreach ( $expenses as $expense ) {
			$settings = $this->Settings_Model->get_settings_ciuis();
			switch ( $settings[ 'dateformat' ] ) {
				case 'yy.mm.dd':
					$expensedate = _rdate( $expense[ 'date' ] );
					break;
				case 'dd.mm.yy':
					$expensedate = _udate( $expense[ 'date' ] );
					break;
				case 'yy-mm-dd':
					$expensedate = _mdate( $expense[ 'date' ] );
					break;
				case 'dd-mm-yy':
					$expensedate = _cdate( $expense[ 'date' ] );
					break;
				case 'yy/mm/dd':
					$expensedate = _zdate( $expense[ 'date' ] );
					break;
				case 'dd/mm/yy':
					$expensedate = _kdate( $expense[ 'date' ] );
					break;
			};
			if ( $expense[ 'invoice_id' ] == NULL ) {
				$billstatus = lang( 'notbilled' )and $color = 'warning'
				and $billstatus_code = 'false';
			} else $billstatus = lang( 'billed' )and $color = 'success'
			and $billstatus_code = 'true';
			if ( $expense[ 'customer_id' ] != 0 ) {
				$billable = 'true';
			} else {
				$billable = 'false';
			}
			$appconfig = get_appconfig();
			$data_expenses[] = array(
				'id' => $expense[ 'id' ],
				'title' => $expense[ 'title' ],
				'prefix' => $appconfig['expense_prefix'],
				'longid' => str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'],
				'amount' => $expense[ 'amount' ],
				'staff' => $expense[ 'staff' ],
				'category' => $expense[ 'category' ],
				'billstatus' => $billstatus,
				'billstatus_code' => $billstatus_code,
				'color' => $color,
				'billable' => $billable,
				'date' => $expensedate,
			);
		};
		echo json_encode( $data_expenses );
	}

	function get_available_times( $staff_id, $date ) {
		$customer_id = $_SESSION[ 'customer' ];
		$times = $this->Appointments_Model->check_staff_appointment_availability( $staff_id, $customer_id, $date );
		if ( check_meeting( $staff_id, $date ) == true ) {
			if ( customer_meeting_check( $staff_id, $customer_id, $date ) === $date ) {
				echo json_encode( $times['locate'] );
			} else {
				echo false;
			};
		} else {
			echo json_encode( $times['default'] );
		}
	}

	function mark_read_notification( $id ) { 
		if ( isset( $id ) ) {
			$response = $this->db->where( 'id', $id )->update( 'notifications', array( 'customerread' => ( '1' ) ) );
		}
	}
}