<?php
class Projects_Model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	/*
	 * Get projects by id
	 */
	function get_projects( $id ) {
		$this->db->select( '*,customers.id as customer_id, customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,customers.email as customeremail,projects.status_id as status, projects.id as id ' );
		$this->db->join( 'customers', 'projects.customer_id = customers.id', 'left' );
		return $this->db->get_where( 'projects', array( 'projects.id' => $id ) )->row_array();
	}
	function get_members( $id ) {
		$this->db->select( '*,staff.staffname as member,staff.staffavatar as memberavatar,staff.email as memberemail,projectmembers.id as id' );
		$this->db->join( 'staff', 'projectmembers.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'projectmembers', array( 'projectmembers.project_id' => $id ) )->result_array();
	}

	function get_project_admin($id) {
		$this->db->select( 'staff.email as adminemail' );
		$this->db->join( 'staff', 'projects.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'projects', array( 'projects.id' => $id ) )->row_array();
	}
	function get_members_index( $id ) {
		$this->db->select( '*,staff.staffname as member,staff.staffavatar as memberavatar,staff.email as memberemail,projectmembers.id as id' );
		$this->db->join( 'staff', 'projectmembers.staff_id = staff.id', 'left' );
		$this->db->limit(3);
		return $this->db->get_where( 'projectmembers', array( 'projectmembers.project_id' => $id ) )->result_array();
	}

	function get_project_services( $id ) {
		$this->db->select( '*,productcategories.name as categoryname, projectservices.id as serviceid' );
		$this->db->join( 'productcategories', 'projectservices.categoryid = productcategories.id', 'left' );
		$this->db->order_by( 'projectservices.id', 'desc' );
		return $this->db->get_where( 'projectservices', array( 'projectservices.projectid' => $id ) )->result_array();
	}

	function get_project_service( $id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'projectservices', array( 'id' => $id ) )->row_array();
	}

	function delete_service($id) {
		return $this->db->delete( 'projectservices', array( 'id' => $id ) );
	}

	function get_customer_by_contact($contact_id) {
		$data = $this->db->get_where('contacts', array('id' => $contact_id))->row_array();
		return $data['customer_id'];
	}

	function check_project_permission($project, $contact_id) {
		$customer_id = $this->get_customer_by_contact($contact_id);
		if ($customer_id) {
			$data = $this->db->get_where( 'projects', array( 'id' => $project, 'customer_id' => $customer_id ) )->num_rows();
			if ($data > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function get_all_tickets($id) {
		$this->db->select( '*,departments.name as department,staff.staffname as staffmembername,staff.staffavatar as staffavatar,contacts.name as contactname,contacts.surname as contactsurname, tickets.id as id ' );
		$this->db->join( 'contacts', 'tickets.contact_id = contacts.id', 'left' );
		$this->db->join( 'departments', 'tickets.department_id = departments.id', 'left' );
		$this->db->join( 'staff', 'tickets.staff_id = staff.id', 'left' );
		$this->db->order_by( 'date desc, priority desc' );
		$this->db->order_by( "date", "desc" );
		return $this->db->get_where( 'tickets', array('relation_id' => $id, 'relation' => 'project',) )->result_array();
	}

	function get_ticket_replies( $id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'ticketreplies', array( 'ticket_id' => $id ) )->result_array();
	}

	function get_tickets( $id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'tickets', array( 'id' => $id ) )->row_array();
	}

	function delete_tickets( $id ) {
		$response = $this->db->delete( 'tickets', array( 'id' => $id ) );
		$this->db->delete( 'ticketreplies', array( 'ticket_id' => $id ) );
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' . $message = sprintf( lang( 'xdeletedxticket' ), $this->session->staffname, $id ) . '' ),
			'staff_id' => $this->session->usr_id
		) );
		return true;
	}

	function get_products_by_category( $id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'products', array( 'categoryid' => $id ) )->result_array();
	}

	function copy_services ($services, $project_id) {
		foreach ( $services as $service ) {
			$params = array(
				'categoryid' => $service['categoryid'],
				'productid' => $service['productid'],
				'servicename' => $service['servicename'],
				'serviceprice' => $service['serviceprice'],
				'servicetax' => $service['servicetax'],
				'quantity' => $service['quantity'],
				'unit' => $service['unit'],
				'servicedescription' => $service['servicedescription'],
				'projectid' => $project_id,
			);
			$this->db->insert( 'projectservices', $params );
		}
		return true;
	}

	function copy_expenses ($expenses, $project_id) {
		foreach ( $expenses as $expense ) {
			$params = array(
				'category_id' => $expense['category_id'],
				'staff_id' => $this->session->usr_id,
				'customer_id' => $expense['customer_id'],
				'relation_type' => 'project',
				'relation' => $project_id,
				'account_id' => $expense['account_id'],
				'title' => $expense['title'],
				'date' => $expense['date'],
				'created' => date( 'Y-m-d H:i:s' ),
				'amount' => $expense['amount'],
				'description' => $expense['description'],
			);
			$this->db->insert( 'expenses', $params );
			$expenseId = $this->db->insert_id();
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'payments', array(
				'transactiontype' => 1,
				'is_transfer' => 0,
				'expense_id' => $expenseId,
				'staff_id' => $loggedinuserid,
				'amount' => $expense['amount'],
				'account_id' => $expense['account_id'],
				'customer_id' => $expense['customer_id'],
				'not' => 'Outgoings for <a href="' . base_url( 'expenses/receipt/' . $expenseId . '' ) . '">EXP-' . $expenseId . '</a>',
				'date' => _pdate( $expense['date'] ),
			) );
			$staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$appconfig = get_appconfig();
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'addedanewexpense' ) . ' <a href="expenses/receipt/' . $expenseId . '">' . $appconfig['expense_prefix'] . '-' . $expenseId.$appconfig['expense_suffix'] . '</a>.' ),
				'staff_id' => $loggedinuserid,
				'customer_id' => $expense['customer_id']
			) );
		}
		return true;
	}

	function copy_milestones ($milestones, $project_id) {
		foreach ( $milestones as $milestone ) {
			$params = array(
				'project_id' => $project_id,
				'name' => $milestone['name'],
				'order' => $milestone['order'],
				'duedate' => _phdate( $milestone['duedate'] ),
				'description' => $milestone['description'],
				'created' => date( 'Y-m-d' ),
				'color' => 'green',
			);
			$this->db->insert( 'milestones', $params );
			$milestoneId = $this->db->insert_id();
			$loggedinuserid = $this->session->usr_id;

			$staffname = $this->session->staffname;
			$loggedinuserid = $this->session->usr_id;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( ''.$staffname.' '.lang('added').' '.lang('milestone') ), 
				'staff_id' => $loggedinuserid,
				'project_id' => $project_id,
			) );
		}
		return true;
	}

	function copy_tasks ($tasks, $project_id) {
		foreach ( $tasks as $task ) {
			$params = array(
				'name' => $task['name'],
				'description' => $task['description'],
				'priority' => $task['priority'],
				'assigned' => $task['assigned'],
				'relation_type' => 'project',
				'relation' => $project_id,
				'milestone' => $task['milestone'],
				'public' => $task['public'],
				'billable' => $task['billable'],
				'visible' => $task['visible'],
				'hourly_rate' => $task['hourly_rate'],
				'startdate' => $task['startdate'],
				'duedate' => $task['duedate'],
				'addedfrom' => $this->session->userdata( 'usr_id' ),
				'status_id' => 1,
				'created' => date( 'Y-m-d H:i:s' ),
			);
			$this->db->insert( 'tasks', $params );
			$loggedinuserid = $this->session->usr_id;
			$staffname = $this->session->staffname;
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $staffname .' '.lang('added').' '.lang('new').' '.lang('task')),
				'staff_id' => $loggedinuserid,
				'project_id' => $project_id,
			));
		}
		return true;
	}

	function copy_members ($members, $project_id) {
		foreach ( $members as $member ) {
			$params = array(
				'staff_id' => $member['staff_id'],
				'project_id' => $project_id,
			);
			$this->db->insert( 'projectmembers', $params );
			$this->db->insert( 'notifications', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( lang( 'assignednewproject' ) ),
				'perres' => $this->session->staffavatar,
				'staff_id' => $member['staff_id'],
				'target' => '' . base_url( 'projects/project/' . $project_id . '' ) . ''
			) );
			$this->db->insert( 'logs', array(
				'date' => date( 'Y-m-d H:i:s' ),
				'detail' => ( '' . $this->session->staffname.' '.lang('added_a_member_project') . ' ' ),
				'staff_id' => $this->session->usr_id,
				'project_id' => $project_id,
			));
		}
		return true;
	}

	function copy_files ($files, $project_id) {
		foreach ( $files as $file ) {
			$params = array(
				'relation_type' => 'project',
				'relation' => $project_id,
				'file_name' => $file['file_name'],
				'created' => date( " Y.m.d H:i:s " ),
			);
			$this->db->insert( 'files', $params );
		}
		return true;
	}

	function copy_notes ($notes, $project_id) {
		foreach ( $notes as $note ) {
			$params = array(
				'relation_type' => 'project',
				'relation' => $project_id,
				'description' => $note['description'],
				'addedfrom' => $note['addedfrom'],
				'created' => date( " Y.m.d H:i:s " ),
			);
			$this->db->insert( 'notes', $params );
		}
		return true;
	}

	/*
	 * Get all projects
	 */
	function get_all_projects() {
		$this->db->select( '*,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,projects.status_id as status, projects.id as id ' );
		$this->db->join( 'customers', 'projects.customer_id = customers.id', 'left' );
		$this->db->order_by( 'projects.id', 'desc' );
		return $this->db->get( 'projects' )->result_array();
	}
	
	function get_all_projects_by_customer($id) {
		$this->db->select( '*,customers.company as customercompany,customers.namesurname as individual,customers.address as customeraddress,projects.status_id as status, projects.id as id ' );
		$this->db->join( 'customers', 'projects.customer_id = customers.id', 'left' );
		$this->db->order_by( 'projects.id', 'desc' );
		return $this->db->get_where( 'projects', array( 'customer_id' => $id ) )->result_array();
	}
	
	function get_all_milestones() {
		$this->db->order_by( 'id', 'asc' );
		return $this->db->get_where( 'milestones', array() )->result_array();
	}
	
	function get_all_project_milestones($id) {
		$this->db->order_by( 'order', 'asc' );
		return $this->db->get_where( 'milestones', array( 'project_id' => $id ) )->result_array();
	}
	
	function get_all_project_milestones_task($id) {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'tasks', array( 'milestone' => $id ) )->result_array();
	}
	
	function get_project_time_log($id) {
		$this->db->select('*,staff.staffname as staffmember,tasktimer.id as id');
		$this->db->join( 'staff', 'tasktimer.staff_id = staff.id', 'left' );
		return $this->db->get_where( 'tasktimer', array( 'tasktimer.project_id' => $id ) )->result_array();
	}
	
	function get_project_files( $id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'files', array( 'files.relation_type' => 'project', 'files.relation' => $id ) )->result_array();
	}

	function get_project_tasks( $project_id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'tasks', array( 'relation_type' => 'project', 'relation' => $project_id ) )->result_array();
	}

	function get_project_task_files( $task_id ) {
		$this->db->select( '*' );
		return $this->db->get_where( 'files', array( 'relation_type' => 'task', 'relation' => $task_id ) )->result_array();
	}

	/*
	 * function to add new projects
	 */
	function add_projects( $params ) {
		$this->db->insert( 'projects', $params );
		return $this->db->insert_id();
	}

	/*
	 * function to update projects
	 */
	
	function update( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'projects', $params );
		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( ''.$staffname.' '.lang('updated').' '.lang('project') ),
			'staff_id' => $loggedinuserid,
			'project_id' => $id,
		) );
	}
	
	function markas() {
		$response = $this->db->where( 'id', $_POST[ 'project_id' ] )->update( 'projects', array( 'status_id' => $_POST[ 'status_id' ] ) );
	}

	function markas_complete() {
		$this->db->where( 'id', $_POST[ 'project_id' ] )->update( 'projects', array( 'status_id' => $_POST[ 'status_id' ] ) );
		$this->db->where( array( 'relation' => 'project', 'relation_id' => $_POST[ 'project_id' ] ))->update( 'tickets', array( 'status_id' => '4' ) );
		$this->db->where( array( 'relation' => $_POST[ 'project_id'], 'relation_type' =>  'project' ))->update( 'tasks', array( 'status_id' => '4' ) );
		return true;
	}
	
	function add_milestone( $id, $params ) {
		$this->db->insert( 'milestones', $params );
		$milestone = $this->db->insert_id();
		$loggedinuserid = $this->session->usr_id;
		//LOG
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( ''.$staffname.' '.lang('added').' '.lang('new').' '.lang('milestone') ),
			'staff_id' => $loggedinuserid,
			'project_id' => $id,
		) );
		return $this->db->insert_id();
	}
	
	function update_milestone( $id, $params ) {
		$this->db->where( 'id', $id );
		return $this->db->update( 'milestones', $params );
	}

	function delete_projects( $id ) { 
		$this->db->delete( 'projects', array( 'id' => $id ) );
		$this->db->delete( 'notes', array( 'relation' => $id, 'relation_type' => 'project' ) );
		$this->db->delete( 'logs', array( 'project_id' => $id ) );
		$this->db->delete( 'projectmembers', array( 'project_id' => $id ) );
		$this->db->delete( 'milestones', array( 'project_id' => $id ) );
		$this->db->delete( 'projectservices', array( 'projectid' => $id ) );

		// delete all tickets, ticket replies, ticket attachments
		$tickets = $this->get_all_tickets($id);
		foreach ($tickets as $ticket) {
			$replies = $this->get_ticket_replies($ticket['id']);
			foreach ($replies as $reply) {
				if ($reply['attachment']) {
					if (is_file('./uploads/attachments/' . $reply['attachment'])) {
						unlink('./uploads/attachments/' . $reply['attachment']);
					}
				}
			}
			$this->db->delete( 'ticketreplies', array( 'ticket_id' => $ticket['id'] ) );
		}
		$this->db->delete( 'tickets', array( 'relation_id' => $id, 'relation' => 'project' ) );

		// delete all tasks, subtasks, task files
		$tasks = $this->get_project_tasks($id);
		foreach ($tasks as $task) {
			$task_files = $this->get_project_task_files($task['id']);
			foreach ($task_files as $task_file) {
				if (is_file('./uploads/files/' . $task_file['file_name'])) {
			    	unlink('./uploads/files/' . $task_file['file_name']);
			    }
			}
			$this->db->delete( 'files', array( 'relation' => $task['id'], 'relation_type' => 'task' ) );
			$this->db->delete( 'subtasks', array( 'taskid' => $task['id'] ) );
		}
		$this->db->delete( 'tasks', array( 'relation' => $id, 'relation_type' => 'project' ) );

		$files = $this->Projects_Model->get_project_files( $id );
		foreach ($files as $file) {
			if ($file['is_old'] == '1') {
				if (is_file('./uploads/files/' . $file['file_name'])) {
			    	unlink('./uploads/files/' . $file['file_name']);
			    }
			}
		}
		$this->db->delete( 'files', array( 'relation' => $id, 'relation_type' => 'project' ) );
		$folder = './uploads/files/projects/'.$id;
		delete_files($folder, true);
		rmdir($folder);

		$loggedinuserid = $this->session->usr_id;
		$staffname = $this->session->staffname;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '' ),
			'detail' => ( ' ' .$staffname.' '.lang( 'project_deleted' ).' '.$id.'.'),
			'staff_id' => $loggedinuserid,
		));
		return true;
	}
}