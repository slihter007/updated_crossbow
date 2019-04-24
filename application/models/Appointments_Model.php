<?php
class Appointments_Model extends CI_Model {

	function get_all_appointments() {

		$this->db->select( '*,,staff.staffname as staff, staff.staffavatar as staff_avatar,contacts.name as contact_name,contacts.surname as contact_surname, appointments.id as id ' );
		$this->db->join( 'staff', 'appointments.staff_id = staff.id', 'left' );
		$this->db->join( 'contacts', 'appointments.contact_id = contacts.id', 'left' );
		$this->db->where( 'staff_id = ' . $this->session->userdata( 'usr_id' ) . '' );
		return $this->db->get_where( 'appointments' )->result_array();
	}

	function get_all_confirmed_appointments() {

		$this->db->select( '*,,staff.staffname as staff, staff.staffavatar as staff_avatar,contacts.name as contact_name,contacts.surname as contact_surname, appointments.id as id ' );
		$this->db->join( 'staff', 'appointments.staff_id = staff.id', 'left' );
		$this->db->join( 'contacts', 'appointments.contact_id = contacts.id', 'left' );
		$this->db->where( 'staff_id = ' . $this->session->userdata( 'usr_id' ) . ' AND status = 1' );
		return $this->db->get_where( 'appointments' )->result_array();
	}

	function new_appointment( $params ) {
		$this->db->insert( 'appointments', $params );
		return $this->db->insert_id();
	}

	function check_staff_appointment_availability( $staff_id, $customer_id, $date ) {
		$staff = $this->Staff_Model->get_staff( $staff_id );
		$meetings = $this->db->select( '*' )->get_where( 'meetings', array( 'staff_id' => $staff_id, 'date' => $date ) )->result_array();
		$this_day = strtolower( date( "l", strtotime( $date ) ) );
		$work_plans = $this->db->select( '*' )->get_where( 'staff_work_plan', array( 'staff_id' => $staff_id, ) )->row_array();
		$daily_work_plan = json_decode( $work_plans[ 'work_plan' ] );
		$work_day = array();
		foreach ( $daily_work_plan as $lookup_day ) {
			if ( $lookup_day->{'day'} == $this_day ) {
				$work_day = $lookup_day;
			}
		}
		$work_day_start = json_encode( $work_day->{'start'} );
		$work_day_end = json_encode( $work_day->{'end'} );
		$breaks = $work_day->{'breaks'};
		$interval = '30 mins';
		$format = '24';
		$startTime = strtotime( json_decode( $work_day_start ) );
		$endTime = strtotime( json_decode( $work_day_end ) );
		$returnTimeFormat = ( $format == '12' ) ? 'g:i:s A' : 'H:i';
		$current = time();
		$addTime = strtotime( '+' . $interval, $current );
		$diff = $addTime - $current;
		$times = array();
		while ( $startTime < $endTime ) {
			$times[] = date( $returnTimeFormat, $startTime );
			$startTime += $diff;
		}
		$times[] = date( $returnTimeFormat, $startTime );
		$appointments = $this->db->select( '*' )->get_where( 'appointments', array( 'staff_id' => $staff_id, 'booking_date' => $date ) )->result_array();
		$data_disabled_times = array();
		foreach ( $appointments as $appointment ) {
			$data_disabled_times[] = date( 'H:i', strtotime( $appointment[ 'start_time' ] ) );
		};
		switch ( $staff[ 'google_calendar_enable' ] ) {
			case 0:
				array_push( $data_disabled_times, $breaks->{'start'}, $breaks->{'end'}, $work_day->{'end'} );
				if ( check_meeting( $staff_id, $date ) == true ) {
					$meeting = $this->db->get_where( 'meetings', array( 'date' => $date, 'staff_id' => $staff_id, ) )->row_array();
					$interval = '30 mins';
					$format = '24';
					$startTime = strtotime( $meeting[ 'start' ] );
					$endTime = strtotime( $meeting[ 'end' ] );
					$returnTimeFormat = ( $format == '12' ) ? 'g:i:s A' : 'H:i';
					$current = time();
					$addTime = strtotime( '+' . $interval, $current );
					$diff = $addTime - $current;
					$times = array();
					while ( $startTime < $endTime ) {
						$times[] = date( $returnTimeFormat, $startTime );
						$startTime += $diff;
					}
					$times[] = date( $returnTimeFormat, $startTime );
					$data_available_times = array_values( array_diff( $times, $data_disabled_times ) );
					$time_type = array(
						'locate' => $data_available_times,
					);
				} else {
					$data_available_times = array_values( array_diff( $times, $data_disabled_times ) );
					$time_type = array(
						'default' => $data_available_times,
					);
				}
				return $time_type;
			case 1:
				$str = file_get_contents( 'https://www.googleapis.com/calendar/v3/calendars/' . $staff[ 'google_calendar_id' ] . '/events?key=' . $staff[ 'google_calendar_api_key' ] . '' );
				$json = json_decode( $str, true );
				$google_events_data = $json[ 'items' ];
				$google_events = $google_events_data;
				$data_disabled_google_times = array();
				foreach ( $google_events as $event ) {
					if ( date( 'Y-m-d', strtotime( $event[ 'start' ][ 'dateTime' ] ) ) == $date ) {
						$data_disabled_google_times[] = date( 'H:i', strtotime( $event[ 'start' ][ 'dateTime' ] ) );
					}
				};
				array_push( $data_disabled_times, $breaks->{'start'}, $breaks->{'end'}, $work_day->{'end'} );
				$result = array_merge( $data_disabled_times, $data_disabled_google_times );
				// Check Meeting
				if ( check_meeting( $staff_id, $date ) == true ) {
					$meeting = $this->db->get_where( 'meetings', array( 'date' => $date, 'staff_id' => $staff_id, ) )->row_array();
					$interval = '30 mins';
					$format = '24';
					$startTime = strtotime( $meeting[ 'start' ] );
					$endTime = strtotime( $meeting[ 'end' ] );
					$returnTimeFormat = ( $format == '12' ) ? 'g:i:s A' : 'H:i';
					$current = time();
					$addTime = strtotime( '+' . $interval, $current );
					$diff = $addTime - $current;
					$times = array();
					while ( $startTime < $endTime ) {
						$times[] = date( $returnTimeFormat, $startTime );
						$startTime += $diff;
					}
					$times[] = date( $returnTimeFormat, $startTime );
					$data_available_times = array_values( array_diff( $times, $result ) );
					$time_type = array(
						'locate' => $data_available_times,
					);
				} else {
					$data_available_times = array_values( array_diff( $times, $result ) );
					$time_type = array(
						'default' => $data_available_times,
					);
				}
				return $time_type;
				break;
		}
	}
}