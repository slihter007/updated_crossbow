<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );
class Timesheets extends CIUIS_Controller {

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
		$data[ 'title' ] = lang( 'timesheets' );
		$this->load->view( 'inc/header', $data );
		$this->load->view( 'timesheets/index', $data );
		$this->load->view( 'inc/footer', $data );
	}

	function logtime() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$task = $this->input->post('task');
			$start_time = $this->input->post('start_time');
			$end_time = $this->input->post('end_time');
			$note = $this->input->post('note');

			$hasError = false;
			$data['message'] = '';
			if ($task == '') {
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('task');
				$hasError = true;
			} else if ($start_time == '') {
				$data['message'] = lang('invalidmessage'). ' ' .lang('start_time');
				$hasError = true;
			} else if ($end_time == '') {
				$data['message'] = lang('invalidmessage'). ' ' .lang('end_time');
				$hasError = true;
			} else if (strtotime($end_time) < strtotime($start_time)) {
				$data['message'] = lang('start_time').' '.lang('date_error'). ' ' .lang('end_time');
				$hasError = true;
			} else if ($note == '') {
				$data['message'] = lang('invalidmessage'). ' ' .lang('description');
				$hasError = true;
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'task_id' => $task,
					'start' => $start_time,
					'end' => $end_time,
					'note' => $note,
					'staff_id' => $this->session->userdata( 'usr_id' ),
					'status' => 0,
					//'created' => date( 'Y-m-d H:i:s' ), 
				);
				$this->db->insert( 'tasktimer', $params );
				$time_id = $this->db->insert_id();
				if ($time_id) {
					$data['success'] = true;
					$data['message'] = lang('timesheet'). ' ' .lang('addmessage');
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function update_logtime($id) {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$task = $this->input->post('task');
			$start_time = $this->input->post('start_time');
			$end_time = $this->input->post('end_time');
			$note = $this->input->post('note');

			$hasError = false;
			$data['message'] = '';
			if ($task == '') {
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('task');
				$hasError = true;
			} else if ($start_time == '') {
				$data['message'] = lang('invalidmessage'). ' ' .lang('start_time');
				$hasError = true;
			} else if ($end_time == '') {
				$data['message'] = lang('invalidmessage'). ' ' .lang('end_time');
				$hasError = true;
			} else if (strtotime($end_time) < strtotime($start_time)) {
				$data['message'] = lang('start_time').' '.lang('date_error'). ' ' .lang('end_time');
				$hasError = true;
			} else if ($note == '') {
				$data['message'] = lang('invalidmessage'). ' ' .lang('description');
				$hasError = true;
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$params = array(
					'task_id' => $task,
					'start' => $start_time,
					'end' => $end_time,
					'note' => $note,
				);
				$this->db->where( 'id', $id );
				$response = $this->db->update( 'tasktimer', $params );
				$data['success'] = true;
				$data['message'] = lang('timesheet'). ' ' .lang('updatemessage');
				echo json_encode($data);
			}
		}
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
				'task_id' => $field['task_id'],
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

	function delete_log($id) {
		if (isset($id)) {
			$this->db->delete('tasktimer', array('id' => $id));
			$data['success'] = true;
			$data['message'] = lang('timesheet'). ' ' .lang('deletemessage');
			echo json_encode($data);
		}
	}
}