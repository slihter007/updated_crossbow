<?php

class Tasks_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function get_task( $id ) {
		return $this->db->get_where( 'tasks', array( 'id' => $id ) )->row_array();
	}

	function get_task_detail( $id ) {
		$this->db->select( '*,staff.staffname as assigner,tasks.id as id, staff.email as staffemail' );
			$this->db->join( 'staff', 'tasks.assigned = staff.id', 'left' );
			return $this->db->get_where( 'tasks', array( 'tasks.id' => $id ) )->row_array();

	}
	function get_task_time_log($id) {
		$this->db->select('*,staff.staffname as staffmember,tasktimer.id as id');
		$this->db->join( 'staff', 'tasktimer.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tasktimer', array( 'tasktimer.task_id' => $id ) )->result_array();
	}

	function get_project_tasks( $id ) {
		$this->db->select( '*' );
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'tasks', array( 'tasks.relation_type' => 'project', 'tasks.relation' => $id) )->result_array();

	}

	function get_all_tasks() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'tasks', array( '' ) )->result_array();
	}

	function get_all_tasks_for_timer() {
		$admin = $this->isAdmin();
		$this->db->select( '*' );
		$this->db->from('tasks');
		$user_id = $this->session->usr_id;
		if (!$admin) {
			$this->db->where("assigned = '$user_id'");
		}
		$this->db->order_by( 'id', 'desc' );
		$data = $this->db->get()->result_array();
		return $data;
	}

	function isAdmin() {
		$id = $this->session->usr_id;
		$this->db->select('*');
		$rows = $this->db->get_where( 'staff', array( 'admin' => 1, 'id' => $id ) )->num_rows();
        if ($rows > 0) {
            return true;
        } else {
            return false;
        }
	}

	function get_subtasks( $id ) {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'subtasks', array( 'subtasks.taskid' => $id, 'subtasks.complete' => 0 ) )->result_array();
	}

	function get_subtaskscomplete( $id ) {
		return $this->db->get_where( 'subtasks', array( 'subtasks.taskid' => $id, 'subtasks.complete' => 1 ) )->result_array();
	}

	function get_task_files( $id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'files', array( 'files.relation_type' => 'task', 'files.relation' => $id ) )->result_array();

	}
	function update_task( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'tasks', $params );
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> '.lang('updated').' <a href="tasks/task/' . $id . '">'.lang('task').'-' . $id . '</a>.' ),
			'staff_id' => $loggedinuserid,
		) );
	}

	function start_timer($id) {
		$date = new DateTime();
		$this->db->insert( 'tasktimer', array(
			//'relation' => 'task',
			'task_id' => NULL,
			'staff_id' => $id,
			'start' => $date->format('Y-m-d H:i:s'),
			'note' => '',
		));
		return $this->db->insert_id();
	}

	function stop_timer($timer_id, $params) {
		$this->db->where( 'id', $timer_id );
		$response = $this->db->update( 'tasktimer', $params );
		$response = $this->db->where( 'id', $params[ 'task_id' ] )->update( 'tasks', array( 'timer' => 0 ) );
		if ($response) {
			return true;
		} else {
			return false;
		}
	}

	function get_timer() {
		$this->db->select( 'tasktimer.id, tasktimer.start, tasktimer.end, tasktimer.task_id, tasks.name, tasktimer.note' );
		$this->db->join( 'tasks', 'tasktimer.task_id = tasks.id', 'left' );
		$this->db->order_by('tasktimer.id', 'asc');
		$this->db->limit(1);
		$data = $this->db->get_where( 'tasktimer', array( 'tasktimer.end' => NULL, 'tasktimer.staff_id' => $this->session->usr_id ) )->row_array();
		if ($data) {
			return $data;
		} else {
			return false;
		}
	}

}