<?php
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
header( 'Access-Control-Allow-Origin: *' );
class Api extends CIUIS_Controller {

	function index() {
		echo 'Ciuis RestAPI Service';
	}

	function settings() {
		$settings = $this->Settings_Model->get_settings_ciuis();
		echo json_encode( $settings ); 
	}

	function settings_detail() {
		$settings = $this->Settings_Model->get_settings_ciuis_origin();
		$settings['smtppassoword'] = '********';
		echo json_encode( $settings );
	}

	function languages() {
		$languages = $this->Settings_Model->get_languages();
		$lang = array();
		foreach ($languages as $language) {
			$lang[] = array(
				'name' => lang($language['name']),
				'foldername' => $language['foldername'],
				'id' => $language['id'],
				'langcode' => $language['langcode']
			);
		}
		echo json_encode($lang);
	}

	function currencies() {
		$currencies = $this->Settings_Model->get_currencies();
		echo json_encode( $currencies );
	}

	function timezones() {
		$jsonstring = include( 'assets/json/timezones.json' );
		$obj = json_decode( $jsonstring );
		print_r( $obj[ 'Data' ] );
	}

	function upload_attachmet() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$config[ 'upload_path' ] = './uploads/attachments/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'fd' );
			$data_upload_files = $this->upload->data();
			$file_data = $this->upload->data();
			echo $file_data[ 'file_name' ];
		} else {
			echo 'null';
		}
	}

	function get_appconfig() {
		$configs = $this->db->get_where('appconfig', array())->result_array();
		$data = array();
		foreach ($configs as $config) {
			$data[$config['name']] = $config['value'];
		}
		echo json_encode($data);
	}

	function stats() {
		$otc = $this->Report_Model->otc();
		$yms = $this->Report_Model->yms();
		$bkt = $this->Report_Model->bkt();
		$ogt = $this->Report_Model->ogt();
		$pay = $this->Report_Model->pay();
		$exp = $this->Report_Model->exp();
		$bht = $this->Report_Model->bht();
		$ohc = $this->Report_Model->ohc();
		$oak = $this->Report_Model->oak();
		$akt = $this->Report_Model->akt();
		$mex = $this->Report_Model->mex();
		$pme = $this->Report_Model->pme();
		$ycr = $this->Report_Model->ycr();
		$oyc = $this->Report_Model->oyc();
		if ( $otc > 1 ) {
			$newticketmsg = lang( 'newtickets' );
		} else $newticketmsg = lang( 'newticket' );
		if ( $yms > 1 ) {
			$newcustomermsg = lang( 'newcustomers' );
		} else $newcustomermsg = lang( 'newcustomer' );
		if ( $bkt > $ogt ) {
			$todaysalescolor = 'default';
		} else {
			$todaysalescolor = 'danger';
		}
		$todayrate = $bkt - $ogt;
		if ( empty( $ogt ) ) {
			$todayrate = 'N/A';
		} else {
			if ($ogt != 0) {
				$todayrate = floor( $todayrate / $ogt * 100 );
			} 
		}
		if ( $bkt > $ogt ) {
			$todayicon = 'icon ion-arrow-up-c';
		} else {
			$todayicon = 'icon ion-arrow-down-c';
		}
		$netcashflow = ( $pay - $exp );
		if ( $bht > $ohc ) {
			$weekstat = 'default';
		} else {
			$weekstat = 'danger';
		}
		$weekrate = $bht - $ohc;
		if ( empty( $ohc ) ) {
			$weekrate = 'N/A';
		} else {
			if ($ohc != 0) {
				$weekrate = floor( $weekrate / $ohc * 100 );
			} 
		}
		if ( $bht > $ohc ) {
			$weekratestatus = lang( 'increase' );
		} else {
			$weekratestatus = lang( 'recession' );
		}
		if ( $akt > $oak ) {
			$montearncolor = 'success';
			$monicon = 'icon ion-arrow-up-c';
		} else {
			$montearncolor = 'danger';
			$monicon = 'icon ion-arrow-down-c';
		}
		$oao = $akt - $oak;
		if ( empty( $oak ) ) {
			$monmessage = '' . lang( 'notyet' ) . '';
		} else { 
			if($oak != 0) {
				$monmessage = floor( $oao / $oak * 100 );
			}
		}
		$time = date( "H" );
		$timezone = date( "e" );
		if ( $time < "12" ) {
			$daymessage = lang( 'goodmorning' );
			$dayimage = 'morning.png';
		} else if ( $time >= "12" && $time < "17" ) {
			$daymessage = lang( 'goodafternoon' );
			$dayimage = 'afternoon.png';
		} else if ( $time >= "17" && $time < "19" ) {
			$daymessage = lang( 'goodevening' );
			$dayimage = 'evening.png';
		} else if ( $time >= "19" ) {
			$daymessage = lang( 'goodnight' );
			$dayimage = 'night.png';
		}
		if ( $mex > $pme ) {
			$expensecolor = 'warning';
		} else {
			$expensecolor = 'danger';
		}
		if ( $mex > $pme ) {
			$expenseicon = 'icon ion-arrow-up-c';
		} else {
			$expenseicon = 'icon ion-arrow-down-c';
		}
		$expenses = $mex - $pme;
		if ( empty( $pme ) ) {
			$expensestatus = '' . lang( 'notyet' ) . '';
		} else {
			if ($pme != 0) {
				$expensestatus = floor( $expenses / $pme * 100 );
			}
		}
		if ( $ycr > $oyc ) {
			$yearcolor = 'success';
		} else {
			$yearcolor = 'danger';
		}
		if ( $ycr > $oyc ) {
			$yearicon = 'icon ion-arrow-up-c';
		} else {
			$yearicon = 'icon ion-arrow-down-c';
		}
		$yearly = $ycr - $oyc;
		if ( empty( $oyc ) ) {
			$yearmessage = '' . lang( 'notyet' ) . '';
		} else {
			if ($oyc != 0) {
				$yearmessage = floor( $yearly / $oyc * 100 );
			}
		} 
		$stats = array(
			'mex' => $mex = $this->Report_Model->mex(),
			'pme' => $pme = $this->Report_Model->pme(),
			'bkt' => $bkt = $this->Report_Model->bkt(),
			'bht' => $bht = $this->Report_Model->bht(),
			'ogt' => $ogt = $this->Report_Model->ogt(),
			'ohc' => $ohc = $this->Report_Model->ohc(),
			'otc' => $otc = $this->Report_Model->otc(),
			'ycr' => $ycr = $this->Report_Model->ycr(),
			'oyc' => $oyc = $this->Report_Model->oyc(),
			'oft' => $oft = $this->Report_Model->oft(),
			'tef' => $tef = $this->Report_Model->tef(),
			'vgf' => $vgf = $this->Report_Model->vgf(),
			'tbs' => $tbs = $this->Report_Model->tbs(),
			'akt' => $akt = $this->Report_Model->akt(),
			'oak' => $oak = $this->Report_Model->oak(),
			'tfa' => $tfa = $this->Report_Model->tfa(),
			'yms' => $yms = $this->Report_Model->yms(),
			'ttc' => $ttc = $this->Report_Model->ttc(),
			'ipc' => $ipc = $this->Report_Model->ipc(),
			'atc' => $atc = $this->Report_Model->atc(),
			'ctc' => $ctc = $this->Report_Model->ctc(),
			'put' => $put = $this->Report_Model->put(),
			'pay' => $pay = $this->Report_Model->pay(),
			'exp' => $exp = $this->Report_Model->exp(),
			'twt' => $twt = $this->Report_Model->twt(),
			'clc' => $clc = $this->Report_Model->clc(),
			'mlc' => $mlc = $this->Report_Model->mlc(),
			'mtt' => $mtt = $this->Report_Model->mtt(),
			'mct' => $mct = $this->Report_Model->mct(),
			'ues' => $ues = $this->Report_Model->ues(),
			'myc' => $myc = $this->Report_Model->myc(),
			'tpz' => $tpz = $this->Report_Model->tpz(),
			'nsp' => $nsp = $this->Report_Model->nsp(),
			'sep' => $sep = $this->Report_Model->sep(),
			'pep' => $pep = $this->Report_Model->pep(),
			'cap' => $cap = $this->Report_Model->cap(),
			'cop' => $cop = $this->Report_Model->cop(),
			'tht' => $tht = $this->Report_Model->tht(),
			'total_incomings' => $this->Report_Model->total_incomings(),
			'total_outgoings' => $this->Report_Model->total_outgoings(),
			'not_started_percent' => $tpz > 0 ? number_format( ( $nsp * 100 ) / $tpz ) : 0,
			'started_percent' => $tpz > 0 ? number_format( ( $sep * 100 ) / $tpz ) : 0,
			'percentage_percent' => $tpz > 0 ? number_format( ( $pep * 100 ) / $tpz ) : 0,
			'cancelled_percent' => $tpz > 0 ? number_format( ( $cap * 100 ) / $tpz ) : 0,
			'complete_percent' => $tpz > 0 ? number_format( ( $cop * 100 ) / $tpz ) : 0,
			'totalpaym' => $this->Report_Model->totalpaym(),
			'incomings' => $this->Report_Model->incomings(),
			'outgoings' => $this->Report_Model->outgoings(),
			'ysy' => $ysy = ( $ttc > 0 ? number_format( ( $otc * 100 ) / $ttc ) : 0 ),
			'bsy' => $bsy = ( $ttc > 0 ? number_format( ( $ipc * 100 ) / $ttc ) : 0 ),
			'twy' => $twy = ( $ttc > 0 ? number_format( ( $atc * 100 ) / $ttc ) : 0 ),
			'iey' => $iey = ( $ttc > 0 ? number_format( ( $ctc * 100 ) / $ttc ) : 0 ),
			'ofy' => $ofy = ( $tfa > 0 ? number_format( ( $tef * 100 ) / $tfa ) : 0 ),
			'clp' => $clp = ( $mlc > 0 ? number_format( ( $clc * 100 ) / $mlc ) : 0 ),
			'mtp' => $mtp = ( $mtt > 0 ? number_format( ( $mct * 100 ) / $mtt ) : 0 ),
			'inp' => $inp = ( $put > 0 ? number_format( ( $pay * 100 ) / $put ) : 0 ),
			'ogp' => $ogp = ( $put > 0 ? number_format( ( $exp * 100 ) / $put ) : 0 ),
			'newticketmsg' => $newticketmsg,
			'newcustomermsg' => $newcustomermsg,
			'todaysalescolor' => $todaysalescolor,
			'todayrate' => $todayrate,
			'todayicon' => $todayicon,
			'netcashflow' => $netcashflow,
			'weekstat' => $weekstat,
			'weekrate' => $weekrate,
			'weekratestatus' => $weekratestatus,
			'daymessage' => $daymessage,
			'dayimage' => $dayimage,
			'montearncolor' => $montearncolor,
			'monicon' => $monicon,
			'monmessage' => $monmessage,
			'expensecolor' => $expensecolor,
			'expenseicon' => $expenseicon,
			'expensestatus' => $expensestatus,
			'yearcolor' => $yearcolor,
			'yearicon' => $yearicon,
			'yearmessage' => $yearmessage,
			'newnotification' => $this->Notifications_Model->newnotification(),
			'totaltasks' => $totaltasks = $this->Report_Model->totaltasks(),
			'opentasks' => $opentasks = $this->Report_Model->opentasks(),
			'inprogresstasks' => $inprogresstasks = $this->Report_Model->inprogresstasks(),
			'waitingtasks' => $waitingtasks = $this->Report_Model->waitingtasks(),
			'completetasks' => $completetasks = $this->Report_Model->completetasks(),
			'invoice_chart_by_status' => $invoice_chart_by_status = $this->Report_Model->invoice_chart_by_status(),
			'leads_to_win_by_leadsource' => $leads_to_win_by_leadsource = $this->Report_Model->leads_to_win_by_leadsource(),
			'leads_by_leadsource' => $leads_by_leadsource = $this->Report_Model->leads_by_leadsource(),
			'incomings_vs_outgoins' => $leads_by_leadsource = $this->Report_Model->incomings_vs_outgoins(),
			'expenses_by_categories' => $expenses_by_categories = $this->Report_Model->expenses_by_categories(),
			'top_selling_staff_chart' => $top_selling_staff_chart = $this->Report_Model->top_selling_staff_chart(),
			'weekly_sales_chart' => $weekly_sales_chart = $this->Report_Model->weekly_sales_chart(),
			'monthly_expenses' => $this->Report_Model->monthly_expenses(),
			'months' => months(),
		);
		echo json_encode( $stats );
	}

	function get_consultant_data() {
		if ($this->session->userdata('other')) {
			$months = array(
				mb_substr(lang( 'january' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'february' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'march' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'april' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'may' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'june' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'july' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'august' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'september' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'october' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'november' ), 0, 3, 'UTF-8'),
				mb_substr(lang( 'december' ), 0, 3, 'UTF-8')
			);
			$lang = array(
				'amount' => lang('amount'),
				'expenses' => lang('expenses'),
				'sales' => lang('sales'),
				'sales_vs_expenses' => lang('sales_vs_expenses')
			);
			$data['months_short'] = $months;
			$data['months'] = months();
			$data['lang'] = $lang;
			$data['totalInvoices'] = $this->Report_Model->totalData('invoices');
			$data['expenses'] = $this->Report_Model->totalData('expenses');
			$data['invoices_thisweek'] = $this->Report_Model->invoices_thisweek();
			$data['expenses_thisweek'] = $this->Report_Model->expenses_thisweek();
			$data['monthly_expenses'] = $this->Report_Model->monthly_expenses();
			$data['monthly_sales'] = $this->Report_Model->monthly_sales();
			echo json_encode($data);
		}
	}

	function user() {
		$id = $this->session->userdata( 'usr_id' );
		$user = $this->Staff_Model->get_staff( $id );
		$user_data = array(
			'id' => $user[ 'id' ],
			'role_id' => $user[ 'role_id' ],
			'language' => $user[ 'language' ],
			'name' => $user[ 'staffname' ],
			'avatar' => $user[ 'staffavatar' ],
			'department_id' => $user[ 'department_id' ],
			'phone' => $user[ 'phone' ],
			'email' => $user[ 'email' ],
			'birthday' => $user[ 'birthday' ],
			'root' => $user[ 'root' ],
			'admin' => $user[ 'admin' ],
			'staffmember' => $user[ 'staffmember' ],
			'last_login' => $user[ 'last_login' ],
			'inactive' => $user[ 'inactive' ],
			'appointment_availability' => $user[ 'appointment_availability' ],
		);
		echo json_encode( $user_data );
	}

	function staff_detail( $id ) {
		$staff = $this->Staff_Model->get_staff( $id );
		$permissions = $this->Privileges_Model->get_all_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$work_plans = $this->db->select( '*' )->get_where( 'staff_work_plan', array( 'staff_id' => $id, ) )->row_array();
		$daily_work_plan = json_decode( $work_plans[ 'work_plan' ] );
		$arr = array();
		foreach ( $privileges as $privilege ) {
			if ( $privilege[ 'relation' ] == $staff[ 'id' ] && $privilege[ 'relation_type' ] == 'staff' ) {
				array_push( $arr, $privilege[ 'permission_id' ] );
			}
		}
		$data_privileges = array();
			if ($staff['other']) {
				foreach ( $permissions as $permission ) {
					if (($permission['key'] == 'invoices') || ($permission['key'] == 'expenses')) {
						$data_privileges[] = array(
							'id' => $permission[ 'id' ],
							'name' => '' . lang( $permission[ 'permission' ] ) . '',
							'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
						);
					}
				}
			} else {
				foreach ( $permissions as $permission ) {
					$data_privileges[] = array(
						'id' => $permission[ 'id' ],
						'name' => '' . lang( $permission[ 'permission' ] ) . '',
						'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
					);
				}
			}
		switch ( $staff[ 'admin' ] ) {
			case 1:
				$isAdmin = true;
				$type = 'admin';
				break;
			case null || 0:
				$isAdmin = false;
				break;
		}
		switch ( $staff[ 'staffmember' ] ) {
			case 1:
				$isStaff = true;
				$type = 'staffmember';
				break;
			case null || 0:
				$isStaff = false;
				break;
		}
		switch ($staff['other']) {
			case 1:
				$isOther = true;
				$isStaff = false;
				$type = 'other';
				break;
			case null || 0:
				$isOther = false;
				break;
		}
		switch ( $staff[ 'inactive' ] ) {
			case null:
				$isInactive = true;
				break;
			case '0':
				$isInactive = false;
				break;
		}
		switch ( $staff[ 'google_calendar_enable' ] ) {
			case '0':
				$GoogleCalendarEnable = false;
				break;
			case '1':
				$GoogleCalendarEnable = true;
				break;
		}
		$properties = array(
			'department' => $staff[ 'department' ],
			'sales_total' => $this->Staff_Model->total_sales_by_staff( $id ),
			'total_customer' => $this->Staff_Model->total_custoemer_by_staff( $id ),
			'total_ticket' => $this->Staff_Model->total_ticket_by_staff( $id ),
			'chart_data' => $this->Report_Model->staff_sales_graph( $id ),
		);
		$user_data = array(
			'id' => $staff[ 'id' ],
			'role_id' => $staff[ 'role_id' ],
			'language' => $staff[ 'language' ],
			'name' => $staff[ 'staffname' ],
			'avatar' => $staff[ 'staffavatar' ],
			'department_id' => $staff[ 'department_id' ],
			'phone' => $staff[ 'phone' ],
			'email' => $staff[ 'email' ],
			'address' => $staff[ 'address' ],
			'birthday' => $staff[ 'birthday' ],
			'google_calendar_id' => $staff[ 'google_calendar_id' ],
			'google_calendar_api_key' => $staff[ 'google_calendar_api_key' ],
			'google_calendar_enable' => $GoogleCalendarEnable,
			'admin' => $isAdmin,
			'other' => $isOther,
			'type' => $type,
			'staffmember' => $isStaff,
			'last_login' => $staff[ 'last_login' ],
			'active' => $isInactive,
			'properties' => $properties,
			'privileges' => $data_privileges,
			'work_plan' => $daily_work_plan
		);
		echo json_encode( $user_data );
	}

	function menu() {
		$menus = $this->Settings_Model->get_menus();
		$data_menus = array();
		foreach ( $menus as $menu ) {
			$sub_menus = $this->Settings_Model->get_submenus( $menu[ 'id' ] );
			$data_submenus = array();
			foreach ( $sub_menus as $sub_menu ) {
				if ( $this->Privileges_Model->has_privilege( $sub_menu[ 'url' ] ) ) {
					if ( $sub_menu[ 'url' ] != NULL ) {
						$suburl = '' . base_url( $sub_menu[ 'url' ] ) . '';
					} else {
						$suburl = '#';
					}
					$data_submenus[] = array(
						'id' => $sub_menu[ 'id' ],
						'order_id' => $sub_menu[ 'order_id' ],
						'main_menu' => $sub_menu[ 'main_menu' ],
						'name' => lang( $sub_menu[ 'name' ] ),
						'description' => lang( $sub_menu[ 'description' ] ),
						'icon' => $sub_menu[ 'icon' ],
						'url' => $suburl,
					);
				}
			};
			if ( $menu[ 'url' ] != NULL ) {
				$url = '' . base_url( $menu[ 'url' ] ) . '';
			} else {
				$url = '#';
			}
			$data_menus[] = array(
				'id' => $menu[ 'id' ],
				'order_id' => $menu[ 'order_id' ],
				'main_menu' => $menu[ 'main_menu' ],
				'name' => lang( '' . $menu[ 'name' ] . '' ),
				'description' => $menu[ 'description' ],
				'icon' => $menu[ 'icon' ],
				'url' => $url,
				'sub_menu' => $data_submenus,
			);
		};
		echo json_encode( $data_menus );
	}

	function leftmenu() {
		if ( !if_admin ) {
			$permission_menu = 0;
		} else $permission_menu = 1;
		$all_menu = array(
			'1' => array(
				'title' => lang( 'x_menu_panel' ),
				'show_staff' => 0,
				'url' => base_url( 'panel' ),
				'icon' => 'ion-ios-analytics-outline',
				'path' => null,
				'show' => 'true'
			),
			'2' => array(
				'title' => lang( 'x_menu_customers' ),
				'show_staff' => 0,
				'url' => base_url( 'customers' ),
				'icon' => 'ico-ciuis-customers',
				'path' => 'customers',
				'show' => 'false'
			),
			'3' => array(
				'title' => lang( 'x_menu_leads' ),
				'show_staff' => 0,
				'url' => base_url( 'leads' ),
				'icon' => 'ico-ciuis-leads',
				'path' => 'leads',
				'show' => 'false'
			),
			'4' => array(
				'title' => lang( 'x_menu_projects' ),
				'show_staff' => 0,
				'url' => base_url( 'projects' ),
				'icon' => 'ico-ciuis-projects',
				'path' => 'projects',
				'show' => 'false'
			),
			'5' => array(
				'title' => lang( 'x_menu_invoices' ),
				'show_staff' => 0,
				'url' => base_url( 'invoices' ),
				'icon' => 'ico-ciuis-invoices',
				'path' => 'invoices',
				'show' => 'false'
			),
			'6' => array(
				'title' => lang( 'x_menu_proposals' ),
				'show_staff' => 0,
				'url' => base_url( 'proposals' ),
				'icon' => 'ico-ciuis-proposals',
				'path' => 'proposals',
				'show' => 'false'
			),
			'7' => array(
				'title' => lang( 'x_menu_expenses' ),
				'show_staff' => 0,
				'url' => base_url( 'expenses' ),
				'icon' => 'ico-ciuis-expenses',
				'path' => 'expenses',
				'show' => 'false'
			),
			'8' => array(
				'title' => lang( 'x_menu_staff' ),
				'show_staff' => $permission_menu,
				'url' => base_url( 'staff' ),
				'icon' => 'ico-ciuis-staff',
				'path' => 'staff',
				'show' => 'false'
			),
			'9' => array(
				'title' => lang( 'x_menu_tickets' ),
				'show_staff' => 0,
				'url' => base_url( 'tickets' ),
				'icon' => 'ico-ciuis-supports',
				'path' => 'tickets',
				'show' => 'false'
			),

		);

		$data_left_menu = array();
		foreach ( $all_menu as $menu ) {
			if ( $this->Privileges_Model->has_privilege( $menu[ 'path' ] ) || $menu[ 'show' ] != 'false' ) {
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

	function projects() {
		$projects = $this->Projects_Model->get_all_projects();
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
			if ($project[ 'template' ] == '1') {
				$projectstatus = 'template';
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
				'value' => $project[ 'projectvalue' ],
				'tax' => $project[ 'tax' ],
				'template' => $project[ 'template' ],
				'status_id' => $project[ 'status' ],
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

	function project( $id ) {
		$project = $this->Projects_Model->get_projects( $id );
		$settings = $this->Settings_Model->get_settings_ciuis();
		$milestones = $this->Projects_Model->get_all_project_milestones( $id );
		$projectmembers = $this->Projects_Model->get_members( $id );
		$project_logs = $this->Logs_Model->project_logs( $id );
		$totaltasks = $this->Report_Model->totalprojecttasks( $id );
		$opentasks = $this->Report_Model->openprojecttasks( $id );
		$completetasks = $this->Report_Model->completeprojecttasks( $id );
		$progress = ( $totaltasks > 0 ? number_format( ( $completetasks * 100 ) / $totaltasks ) : 0 );
		$customer = ($project['customercompany'])?$project['customercompany']:$project['namesurname'];
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
				$status_project = lang( 'notstarted' );
				break;
			case '2':
				$status_project = lang( 'started' ); 
				break;
			case '3':
				$status_project = lang( 'percentage' );
				break;
			case '4':
				$status_project = lang( 'cancelled' );
				break;
			case '5':
				$status_project = lang( 'completed' );
				break;
		};
		if ( in_array( current_user_id, array_column( $projectmembers, 'staff_id' ) ) || !if_admin ) {
			$authorization = "true";
		} else {
			$authorization = 'false';
		};
		if ( $project[ 'invoice_id' ] > 0 ) {
			$billed = lang( 'yes' );
		} else {
			$billed = lang( 'no' );
		}
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
				'description' => $task[ 'description' ],
				'relationtype' => $relationtype,
				'status' => $status,
				'status_id' => $task[ 'status_id' ],
				'duedate' => $duedate,
				'startdate' => $startdate,
				'done' => $taskdone,
			);
		};
		$data_projectdetail = array(
			'id' => $project[ 'id' ],
			'name' => $project[ 'name' ],
			'value' => $project[ 'projectvalue' ],
			'status_id' => $project[ 'status' ],
			'tax' => $project[ 'tax' ],
			'description' => $project[ 'description' ],
			'start' => $project[ 'start_date' ],
			'deadline' => $project[ 'deadline' ],
			'created' => $project[ 'created' ],
			'finished' => $project[ 'finished' ],
			'template' => $project[ 'template' ],
			'status' => $status_project,
			'progress' => $progress,
			'totaltasks' => $totaltasks,
			'opentasks' => $opentasks,
			'completetasks' => $completetasks,
			'customer' => $customer,
			'customer_id' => $project[ 'customer_id' ],
			'ldt' => $ldt,
			'authorization' => $authorization,
			'billed' => $billed,
			'milestones' => $milestones,
			'tasks' => $data_projecttasks,
			'members' => $projectmembers,
			'project_logs' => $project_logs
		);
		echo json_encode( $data_projectdetail );
	}

	function projectmilestones( $id ) {
		$milestones = $this->Projects_Model->get_all_project_milestones( $id );
		$data_milestones = array();
		foreach ( $milestones as $milestone ) {
			if ( date( "Y-m-d" ) > $milestone[ 'duedate' ] ) {
				$status = 'is-completed';
			} else if ( date( "Y-m-d" ) < $milestone[ 'duedate' ] ) {
				$status = 'is-future';
			} else {
				$status = 'is-completed';
			}
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

	function notes() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$notes = $this->db->select( '*,staff.staffname as notestaff,notes.id as id ' )->join( 'staff', 'notes.addedfrom = staff.id', 'left' )->order_by('notes.id', 'desc')->get_where( 'notes', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
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

	function discussions() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
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

	function discussion_comments( $id ) {
		$comments = $this->db->get_where( 'discussion_comments', array( 'discussion_id' => $id ) )->result_array();
		echo json_encode( $comments );
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

	function projecttimelogs( $id ) {
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
					'status' => $timelog[ 'status' ],
					'timed' => $timed_minute,
					'amount' => $amount,
				);
			}
		};
		echo json_encode( $data_timelogs );
	}

	function tasks() {
		$tasks = $this->Tasks_Model->get_all_tasks();
		$data_tasks = array();
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
					$taskdone = 'done';
					break;
			};
			switch ( $task[ 'relation_type' ] ) {
				case 'project':
					$relationtype = lang( 'project' );
					break;
				case 'ticket':
					$relationtype = lang( 'ticket' );
					break;
				case 'proposal':
					$relationtype = lang( 'proposal' );
					break;
			};
			switch ( $task[ 'priority' ] ) {
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
			$data_tasks[] = array(
				'id' => $task[ 'id' ],
				'name' => $task[ 'name' ],
				'relationtype' => $relationtype,
				'status' => $status,
				'status_id' => $task[ 'status_id' ],
				'duedate' => $duedate,
				'startdate' => $startdate,
				'done' => $taskdone,
				'' . lang( 'filterbystatus' ) . '' => $status,
				'' . lang( 'filterbypriority' ) . '' => $priority,
			);
		};
		echo json_encode( $data_tasks );
	}

	function task( $id ) {
		$task = $this->Tasks_Model->get_task_detail( $id );
		if ( $task[ 'milestone' ] != NULL ) {
			$milestone = $task[ 'milestone' ];
		} else {
			$milestone = lang( 'nomilestone' );
		}
		$settings = $this->Settings_Model->get_settings_ciuis();
		switch ( $task[ 'status_id' ] ) {
			case '1':
				$status = lang( 'open' );
				break;
			case '2':
				$status = lang( 'inprogress' );
				break;
			case '3':
				$status = lang( 'waiting' );
				break;
			case '4':
				$status = lang( 'complete' );
				break;
			case '5':
				$status = lang( 'cancelled' );
				break;
		};
		switch ( $task[ 'priority' ] ) {
			case '1':
				$priority = lang( 'low' );
				break;
			case '2':
				$priority = lang( 'medium' );
				break;
			case '3':
				$priority = lang( 'high' );
				break;
			default: 
				$priority = lang( 'medium' );
				break;
		};
		switch ( $task[ 'public' ] ) {
			case '1':
				$is_Public = true;
				break;
			case '0':
				$is_Public = false;
				break;
		}
		switch ( $task[ 'visible' ] ) {
			case '1':
				$is_visible = true;
				break;
			case '0':
				$is_visible = false;
				break;
		}
		switch ( $task[ 'billable' ] ) {
			case '1':
				$is_billable = true;
				break;
			case '0':
				$is_billable = false;
				break;
		}
		switch ( $task[ 'timer' ] ) {
			case '1':
				$is_timer = true;
				break;
			case '0':
				$is_timer = false;
				break;
		}
		$taskdata = array(
			'id' => $task[ 'id' ],
			'name' => $task[ 'name' ],
			'description' => $task[ 'description' ],
			'staff' => $task[ 'assigner' ],
			'status' => $status,
			'priority' => $priority,
			'priority_id' => $task[ 'priority' ],
			'status_id' => $task[ 'status_id' ],
			'assigned' => $task[ 'assigned' ],
			'duedate' => $task[ 'duedate' ],
			'startdate' => $task[ 'startdate' ],
			'created' => $task[ 'created' ],
			'relation_type' => $task[ 'relation_type' ],
			'relation' => $task[ 'relation' ],
			'milestone' => $task[ 'milestone' ],
			'datefinished' => $task[ 'datefinished' ],
			'hourlyrate' => $task[ 'hourly_rate' ],
			'timer' => $is_timer,
			'public' => $is_Public,
			'visible' => $is_visible,
			'billable' => $is_billable,

		);
		echo json_encode( $taskdata );
	}

	function weekly_incomings() {
		$allsales[] = $this->Report_Model->weekly_incomings();
		for ( $i = 0; $i < count( $allsales ); $i++ ) {
			foreach ( $allsales[ $i ] as $salesc ) {
				$salesday = date( 'l', strtotime( $salesc[ 'date' ] ) );
				$salestotal = $salesc[ 'total' ];
				$data_timelogs = array();
				foreach ( weekdays_git() as $dayc ) {
					if ( $salesday == $dayc ) {
						$total = $salestotal;
					} else $total = 0;
					$data_timelogs[] = array(
						'day' => $dayc,
						'amount' => $total,
						'type' => 'incoming',
					);
				}

			}
		}
		echo json_encode( $data_timelogs );
	}

	function tasktimelogs( $id ) {
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
					'status' => $timelog[ 'status' ],
					'timed' => $timed_minute,
					'amount' => $amount,
				);
			};
		};
		echo json_encode( $data_timelogs );
	}

	function subtasks( $id ) {
		$subtasks = $this->Tasks_Model->get_subtasks( $id );
		echo json_encode( $subtasks );
	}

	function subtaskscomplete( $id ) {
		$subtaskscomplete = $this->Tasks_Model->get_subtaskscomplete( $id );
		echo json_encode( $subtaskscomplete );
	}

	function taskfiles( $id ) {
		$files = $this->Tasks_Model->get_task_files( $id );
		$data_files = array();
		foreach ( $files as $file ) {
			$data_files[] = array(
				'id' => $file[ 'id' ],
				'name' => $file[ 'file_name' ],
			);
		};
		echo json_encode( $data_files );
	}

	function milestones() {
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

	function staff() {
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

	function departments() {
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

	function expenses_by_relation() {
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
			if ( $expense[ 'internal' ] == '1') {
				$billstatus = lang( 'internal' ) and $color = 'success';
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

	function expenses() {
		$expenses = $this->Expenses_Model->get_all_expenses();
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
			if ( $expense[ 'invoice_id' ] == NULL) {
				$billstatus = lang( 'notbilled' ) and $color = 'warning';
			} else {
				$billstatus = lang( 'billed' ) and $color = 'success';
			}
			if ( $expense[ 'customer_id' ] != 0 ) {
				$billable = 'true';
			} else {
				$billable = 'false';
			}
			if ( $expense[ 'internal' ] == '1') {
				$billstatus = lang( 'internal' ) and $color = 'success';
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
				'color' => $color,
				'billable' => $billable,
				'date' => $expensedate,
				'' . lang( 'filterbycategory' ) . '' => $expense[ 'category' ],
				'' . lang( 'filterbybillstatus' ) . '' => $billstatus,
			);
		};
		echo json_encode( $data_expenses );
	}

	function expense( $id ) {
		$expense = $this->Expenses_Model->all_expenses( $id );
		$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'expense', 'relation' => $id ) )->result_array();

		if ($expense[ 'recurring_endDate' ] != 'Invalid date') {
			$recurring_endDate = date( DATE_ISO8601, strtotime( $expense[ 'recurring_endDate' ] ) );
		} else {
			$recurring_endDate = '';
		}
		$appconfig = get_appconfig();
		$data_expense = array( 
			'id' => $expense[ 'id' ],
			'prefix' => $appconfig['expense_prefix'],
			'longid' => str_pad( $expense[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['expense_suffix'],
			'title' => $expense[ 'title' ],
			'amount' => $expense[ 'amount' ],
			'total' => $expense[ 'amount' ],
			'date' => $expense[ 'date' ],
			'created' => $expense[ 'created' ],
			'internal' => $expense[ 'internal' ] == '1' ? true : false,
			'category' => $expense[ 'category_id' ],
			'customer' => $expense[ 'customer_id' ],
			'customername' => $expense['individual']?$expense['individual']:$expense['customer'],
			'customeremail' => $expense[ 'customeremail' ],
			'customer_phone' => $expense[ 'customer_phone' ],
			'account' => $expense[ 'account_id' ],
			'number' => $expense[ 'number' ],
			'invoice_id' => $expense[ 'invoice_id' ],
			'pdf_status' => $expense[ 'pdf_status' ],
			'description' => $expense[ 'desc' ],
			'category_name' => $expense[ 'category' ],
			'staff_name' => $expense[ 'staff' ],
			'staff_id' => $expense['staff_id'],
			'account_name' => $expense[ 'account' ], 
			'sub_total' => $expense[ 'sub_total' ],
			'total_discount' => $expense[ 'total_discount' ],
			'total_tax' => $expense[ 'total_tax' ],
			'items' => $items,
			'EndRecurring' => $recurring_endDate,
			'recurring_id' => $expense[ 'recurring_id' ],
			'recurring_status' => $expense[ 'recurring_status' ] == '0' ? true : false,
			'recurring_period' => (int)$expense[ 'recurring_period' ],
			'recurring_type' => $expense[ 'recurring_type' ] ? $expense[ 'recurring_type' ] : 0,
		);
		echo json_encode( $data_expense );
	}

	function expensescategories() {
		$expensescategories = $this->Expenses_Model->get_all_expensecat();
		$data_expensescategories = array();
		foreach ( $expensescategories as $category ) {
			$catid = $category[ 'id' ];
			$amountby = $this->Report_Model->expenses_amount_by_category( $catid );
			if ( $amountby != NULL ) {
				$amtbc = $amountby;
			} else $amtbc = 0;
			$percent = $this->Report_Model->expenses_percent_by_category( $catid );
			$data_expensescategories[] = array(
				'id' => $category[ 'id' ],
				'name' => $category[ 'name' ],
				'description' => $category[ 'description' ],
				'amountby' => $amtbc,
				'percent' => $percent,
			);
		};
		echo json_encode( $data_expensescategories );
	}

	function proposals() {
		$proposals = $this->Proposals_Model->get_all_proposals();
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
			$appconfig = get_appconfig();
			$data_proposals[] = array(
				'id' => $proposal[ 'id' ],
				'assigned' => $proposal[ 'assigned' ],
				'prefix' => $appconfig['proposal_prefix'],
				'longid' => str_pad( $proposal[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['proposal_suffix'],
				'subject' => $proposal[ 'subject' ],
				'customer' => $customer,
				'relation' => $proposal[ 'relation' ],
				'date' => $date,
				'opentill' => $opentill,
				'status' => $status,
				'status_id' => $proposal[ 'status_id' ],
				'staff' => $proposal[ 'staffmembername' ],
				'staffavatar' => $proposal[ 'staffavatar' ],
				'total' => $proposal[ 'total' ],
				'class' => $class,
				'relation_type' => $proposal[ 'relation_type' ],
				'' . lang( 'relationtype' ) . '' => $proposal[ 'relation_type' ],
				'' . lang( 'filterbystatus' ) . '' => $status,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
				'' . lang( 'filterbyassigned' ) . '' => $proposal[ 'staffmembername' ],
			);
		};
		echo json_encode( $data_proposals );
	}

	function orders() {
		$orders = $this->Orders_Model->get_all_orders();
		$data_orders = array();
		foreach ( $orders as $order ) {
			$pro = $this->Orders_Model->get_orders( $order[ 'id' ], $order[ 'relation_type' ] );
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
					$date = _rdate( $order[ 'date' ] );
					$opentill = _rdate( $order[ 'opentill' ] );
					break;
				case 'dd.mm.yy':
					$date = _udate( $order[ 'date' ] );
					$opentill = _udate( $order[ 'opentill' ] );
					break;
				case 'yy-mm-dd':
					$date = _mdate( $order[ 'date' ] );
					$opentill = _mdate( $order[ 'opentill' ] );
					break;
				case 'dd-mm-yy':
					$date = _cdate( $order[ 'date' ] );
					$opentill = _cdate( $order[ 'opentill' ] );
					break;
				case 'yy/mm/dd':
					$date = _zdate( $order[ 'date' ] );
					$opentill = _zdate( $order[ 'opentill' ] );
					break;
				case 'dd/mm/yy':
					$date = _kdate( $order[ 'date' ] );
					$opentill = _kdate( $order[ 'opentill' ] );
					break;
			};
			switch ( $order[ 'status_id' ] ) {
				case '1':
					$status = lang( 'draft' );
					$class = 'order-status-accepted';
					break;
				case '2':
					$status = lang( 'sent' );
					$class = 'order-status-sent';
					break;
				case '3':
					$status = lang( 'open' );
					$class = 'order-status-open';
					break;
				case '4':
					$status = lang( 'revised' );
					$class = 'order-status-revised';
					break;
				case '5':
					$status = lang( 'declined' );
					$class = 'order-status-declined';
					break;
				case '6':
					$status = lang( 'accepted' );
					$class = 'order-status-accepted';
					break;

			};
			$data_orders[] = array(
				'id' => $order[ 'id' ],
				'assigned' => $order[ 'assigned' ],
				'prefix' => lang( 'orderprefix' ),
				'longid' => str_pad( $order[ 'id' ], 6, '0', STR_PAD_LEFT ),
				'subject' => $order[ 'subject' ],
				'customer' => $customer,
				'relation' => $order[ 'relation' ],
				'date' => $date,
				'opentill' => $opentill,
				'status' => $status,
				'status_id' => $order[ 'status_id' ],
				'staff' => $order[ 'staffmembername' ],
				'staffavatar' => $order[ 'staffavatar' ],
				'total' => $order[ 'total' ],
				'class' => $class,
				'relation_type' => $order[ 'relation_type' ],
				'' . lang( 'relationtype' ) . '' => $order[ 'relation_type' ],
				'' . lang( 'filterbystatus' ) . '' => $status,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
				'' . lang( 'filterbyassigned' ) . '' => $order[ 'staffmembername' ],
			);
		};
		echo json_encode( $data_orders );
	}

	function order( $id ) {
		$pro = $this->Orders_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$order = $this->Orders_Model->get_orders( $id, $rel_type );
		$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'order', 'relation' => $id ) )->result_array();
		$comments = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'order' ) )->result_array();
		if ( $rel_type == 'customer' ) {
			$customer_id = $order[ 'relation' ];
			$lead_id = '';
			$order_type = false;
		} else {
			$lead_id = $order[ 'relation' ];
			$customer_id = '';
			$order_type = true;
		}
		if ( $order[ 'comment' ] != 0 ) {
			$comment = true;
		} else {
			$comment = false;
		}
		switch ( $order[ 'status_id' ] ) {
			case '1':
				$status = lang( 'draft' );
				break;
			case '2':
				$status = lang( 'sent' );
				break;
			case '3':
				$status = lang( 'open' );
				break;
			case '4':
				$status = lang( 'revised' );
				break;
			case '5':
				$status = lang( 'declined' );
				break;
			case '6':
				$status = lang( 'accepted' );
				break;

		};
		$appconfig = get_appconfig();
		$order_details = array(
			'id' => $order[ 'id' ],
			'token' => $order[ 'token' ],
			'long_id' => '' . $appconfig['order_prefix'].'-'.str_pad( $order['id'], 6, '0', STR_PAD_LEFT ).$appconfig['order_suffix'].'',
			'subject' => $order[ 'subject' ],
			'content' => $order[ 'content' ],
			'comment' => $comment,
			'sub_total' => $order[ 'sub_total' ],
			'total_discount' => $order[ 'total_discount' ],
			'total_tax' => $order[ 'total_tax' ],
			'total' => $order[ 'total' ],
			'customer' => $customer_id,
			'lead' => $lead_id,
			'order_type' => $order_type,
			'created' => $order[ 'created' ],
			'date' => $order[ 'date' ],
			'opentill' => $order[ 'opentill' ],
			'status' => $order[ 'status_id' ],
			'assigned' => $order[ 'assigned' ],
			'content' => $order[ 'content' ],
			'invoice_id' => $order[ 'invoice_id' ],
			'status_name' => $status,
			'items' => $items,
			'comments' => $comments,
		);
		echo json_encode( $order_details );
	}

	function proposal( $id ) {
		$pro = $this->Proposals_Model->get_pro_rel_type( $id );
		$rel_type = $pro[ 'relation_type' ];
		$proposal = $this->Proposals_Model->get_proposals( $id, $rel_type );
		$items = $this->db->select( '*' )->get_where( 'items', array( 'relation_type' => 'proposal', 'relation' => $id ) )->result_array();
		$comments = $this->db->get_where( 'comments', array( 'relation' => $id, 'relation_type' => 'proposal' ) )->result_array();
		if ( $rel_type == 'customer' ) {
			$customer_id = $proposal[ 'relation' ];
			$lead_id = '';
			$proposal_type = false;
		} else {
			$lead_id = $proposal[ 'relation' ];
			$customer_id = '';
			$proposal_type = true;
		}
		if ( $proposal[ 'comment' ] != 0 ) {
			$comment = true;
		} else {
			$comment = false;
		}
		switch ( $proposal[ 'status_id' ] ) {
			case '1':
				$status = lang( 'draft' );
				break;
			case '2':
				$status = lang( 'sent' );
				break;
			case '3':
				$status = lang( 'open' );
				break;
			case '4':
				$status = lang( 'revised' );
				break;
			case '5':
				$status = lang( 'declined' );
				break;
			case '6':
				$status = lang( 'accepted' );
				break;

		};
		$appconfig = get_appconfig();
		$proposal_details = array(
			'id' => $proposal[ 'id' ],
			'token' => $proposal[ 'token' ],
			'long_id' => '' . $appconfig['proposal_prefix'] . '' . str_pad( $proposal[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['proposal_suffix'] . '',
			'subject' => $proposal[ 'subject' ],
			'content' => $proposal[ 'content' ],
			'comment' => $comment,
			'sub_total' => $proposal[ 'sub_total' ],
			'total_discount' => $proposal[ 'total_discount' ],
			'total_tax' => $proposal[ 'total_tax' ],
			'total' => $proposal[ 'total' ],
			'customer' => $customer_id,
			'lead' => $lead_id,
			'proposal_type' => $proposal_type,
			'created' => $proposal[ 'created' ],
			'date' => $proposal[ 'date' ],
			'opentill' => $proposal[ 'opentill' ],
			'status' => $proposal[ 'status_id' ],
			'assigned' => $proposal[ 'assigned' ],
			'content' => $proposal[ 'content' ],
			'invoice_id' => $proposal[ 'invoice_id' ],
			'status_name' => $status,
			'items' => $items,
			'comments' => $comments,
		);
		echo json_encode( $proposal_details );
	}

	function invoices() {
		$invoices = $this->Invoices_Model->get_all_invoices();
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
				$realduedate = 'No Due Date';
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
			if ( $invoice[ 'type' ] == 1 ) {
				$customer = $invoice[ 'individual' ];
			} else $customer = $invoice[ 'customercompany' ];
			$appconfig = get_appconfig();
			$data_invoices[] = array(
				'id' => $invoice[ 'id' ],
				'prefix' => $appconfig['inv_prefix'], 
				'longid' => str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ).$appconfig['inv_suffix'],
				'created' => $created,
				'duedate' => $realduedate,
				'customer' => $customer,
				'customer_id' => $invoice[ 'customer_id' ],
				'recurring_status' => $invoice[ 'recurring_status' ] == '0' ? true : false,
				'staff_id' => $invoice[ 'staff_id' ],
				'total' => $invoice[ 'total' ],
				'status' => $invoicestatus,
				'color' => $color,
				'' . lang( 'filterbystatus' ) . '' => $invoicestatus,
				'' . lang( 'filterbycustomer' ) . '' => $customer,
			);
		};
		echo json_encode( $data_invoices );
	}

	function invoice( $id ) {
		$appconfig = get_appconfig();
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
			$duedate_text = 'No Due Date';
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

		$properties = array(
			'invoice_id' => '' . $appconfig['inv_prefix'] . '' . str_pad( $invoice[ 'id' ], 6, '0', STR_PAD_LEFT ) . $appconfig['inv_suffix'].'',
			'customer' => $customer,
			'customer_address' => $invoice[ 'customeraddress' ],
			'customer_phone' => $invoice[ 'phone' ],
			'invoice_staff' => $invoice[ 'staffmembername' ],
		);

		if ($invoice[ 'recurring_endDate' ] != 'Invalid date') {
			$recurring_endDate = date( DATE_ISO8601, strtotime( $invoice[ 'recurring_endDate' ] ) );
		} else {
			$recurring_endDate = '';
		}

		$invoice_details = array(
			'id' => $invoice[ 'id' ],
			'sub_total' => $invoice[ 'sub_total' ],
			'total_discount' => $invoice[ 'total_discount' ],
			'total_tax' => $invoice[ 'total_tax' ],
			'total' => $invoice[ 'total' ],
			'no' => $invoice[ 'no' ],
			'serie' => $invoice[ 'serie' ],
			'created' => date( DATE_ISO8601, strtotime( $invoice[ 'created' ] ) ),
			'duedate' => $invoice[ 'duedate' ],
			'customer' => $invoice[ 'customer_id' ],
			'billing_street' => $invoice[ 'billing_street' ],
			'billing_city' => $invoice[ 'billing_city' ],
			'billing_state' => $invoice[ 'billing_state' ],
			'billing_zip' => $invoice[ 'billing_zip' ],
			'billing_country' => $invoice[ 'billing_country' ],
			'shipping_street' => $invoice[ 'shipping_street' ],
			'shipping_city' => $invoice[ 'shipping_city' ],
			'shipping_state' => $invoice[ 'shipping_state' ],
			'shipping_zip' => $invoice[ 'shipping_zip' ],
			'shipping_country' => $invoice[ 'shipping_country' ],
			'datepayment' => $invoice[ 'datepayment' ],
			'duenote' => $invoice[ 'duenote' ],
			'status_id' => $invoice[ 'status_id' ],
			'default_payment_method' => $invoice[ 'default_payment_method' ],
			'duedate_text' => $duedate_text,
			'mail_status' => $mail_status,
			'balance' => $net_balance,
			'partial_is' => $partial_is,
			'items' => $items,
			'payments' => $payments,
			// Recurring Invoice
			'recurring_endDate' => $recurring_endDate,
			'recurring_id' => $invoice[ 'recurring_id' ],
			'recurring_status' => $invoice[ 'recurring_status' ] == '0' ? true : false,
			'recurring_period' => (int)$invoice[ 'recurring_period' ],
			'recurring_type' => $invoice[ 'recurring_type' ] ? $invoice[ 'recurring_type' ] : 0,
			// END Recurring Invoice
			'payments' => $payments,
			'properties' => $properties

		);
		echo json_encode( $invoice_details );
	}

	function dueinvoices() {
		if (!isAdmin()) {
			$dueinvoices = $this->Invoices_Model->dueinvoices_by_staff();
		} else {
			$dueinvoices = $this->Invoices_Model->dueinvoices();
		}
		$data_dueinvoices = array();
		foreach ( $dueinvoices as $invoice ) {
			if ( $invoice[ 'type' ] == 1 ) {
				$customer = $invoice[ 'individual' ];
			} else $customer = $invoice[ 'customercompany' ];
			$data_dueinvoices[] = array(
				'id' => $invoice[ 'id' ],
				'total' => $invoice[ 'total' ],
				'customer' => $customer,
			);
		};
		echo json_encode( $data_dueinvoices );
	}

	function overdueinvoices() {
		if (!isAdmin()) {
			$overdueinvoices = $this->Invoices_Model->overdueinvoices_by_staff();
		} else {
			$overdueinvoices = $this->Invoices_Model->overdueinvoices();
		}
		$data_overdueinvoices = array();
		foreach ( $overdueinvoices as $invoice ) {
			if ( $invoice[ 'type' ] == 1 ) {
				$customer = $invoice[ 'individual' ];
			} else $customer = $invoice[ 'customercompany' ];
			$today = time();
			$duedate = strtotime( $invoice[ 'duedate' ] ); // or your date as well
			$created = strtotime( $invoice[ 'created' ] );
			$paymentday = $duedate - $created; // Calculate days left.
			$paymentx = $today - $created;
			$datepaymentnet = $paymentday - $paymentx;
			if ( $datepaymentnet < 0 ) {
				$status = '' . floor( $datepaymentnet / ( 60 * 60 * 24 ) ) . ' days';
			};
			$data_overdueinvoices[] = array(
				'id' => $invoice[ 'id' ],
				'total' => $invoice[ 'total' ],
				'customer' => $customer,
				'status' => $status,
			);
		};
		echo json_encode( $data_overdueinvoices );
	}

	function reminders() {
		$reminders = $this->Trivia_Model->get_reminders();
		$data_reminders = array();
		foreach ( $reminders as $reminder ) {
			switch ( $reminder[ 'relation_type' ] ) {
				case 'event':
					$remindertitle = lang( 'eventreminder' );
					break;
				case 'lead':
					$remindertitle = lang( 'leadreminder' );
					break;
				case 'customer':
					$remindertitle = lang( 'customerreminder' );
					break;
				case 'invoice':
					$remindertitle = lang( 'invoicereminder' );
					break;
				case 'expense':
					$remindertitle = lang( 'expensereminder' );
					break;
				case 'ticket':
					$remindertitle = lang( 'ticketreminder' );
					break;
				case 'proposal':
					$remindertitle = lang( 'proposalreminder' );
					break;
			};
			$data_reminders[] = array(
				'id' => $reminder[ 'id' ],
				'title' => $remindertitle,
				'date' => date( DATE_ISO8601, strtotime( $reminder[ 'date' ] ) ),
				'description' => $reminder[ 'description' ],
				'creator' => $reminder[ 'remindercreator' ],
			);
		};
		echo json_encode( $data_reminders );
	}

	function reminders_by_type() {
		$relation_type = $this->uri->segment( 3 );
		$relation_id = $this->uri->segment( 4 );
		$reminders = $this->db->select( '*,staff.staffname as staff,staff.staffavatar as avatar,reminders.id as id ' )->join( 'staff', 'reminders.staff_id = staff.id', 'left' )->get_where( 'reminders', array( 'relation' => $relation_id, 'relation_type' => $relation_type ) )->result_array();
		$data_reminders = array();
		foreach ( $reminders as $reminder ) {
			$data_reminders[] = array(
				'id' => $reminder[ 'id' ],
				'date' => _adate( $reminder[ 'date' ] ),
				'description' => $reminder[ 'description' ],
				'creator' => $reminder[ 'staff' ],
				'avatar' => base_url( 'uploads/images/' . $reminder[ 'avatar' ] . '' ),
			);
		};
		echo json_encode( $data_reminders );


	}

	function notifications() {
		$notifications = $this->Notifications_Model->get_all_notifications();
		$data_notifications = array();
		foreach ( $notifications as $notification ) {
			switch ( $notification[ 'markread' ] ) {
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
				'avatar' => $notification[ 'perres' ],
				'read' => $read,
			);
		};
		echo json_encode( $data_notifications );
	}

	function tickets() {
		$tickets = $this->Tickets_Model->get_all_tickets();
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
				'lastreply' => date( DATE_ISO8601, strtotime( $ticket[ 'lastreply' ] ) ),
				'status_id' => $ticket[ 'status_id' ],
				'customer_id' => $ticket[ 'customer_id' ],
			);
		};
		echo json_encode( $data_tickets );
	}

	function ticket( $id ) {
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
			'relation' => $ticket[ 'relation' ],
			'relation_id' => $ticket[ 'relation_id' ],
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

	function newtickets() {
		if (!isAdmin()) {
			$newtickets = $this->Tickets_Model->get_all_open_tickets_by_staff();
		} else {
			$newtickets = $this->Tickets_Model->get_all_open_tickets();
		}
		$data_newtickets = array();
		foreach ( $newtickets as $ticket ) {
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
			$data_newtickets[] = array(
				'id' => $ticket[ 'id' ],
				'subject' => $ticket[ 'subject' ],
				'contactsurname' => $ticket[ 'contactsurname' ],
				'contactname' => $ticket[ 'contactname' ],
				'priority' => $priority,
			);
		};
		echo json_encode( $data_newtickets );
	}

	function transactions() {
		if (!isAdmin()) {
			$transactions = $this->Payments_Model->todaypayments_by_staff();
		} else {
			$transactions = $this->Payments_Model->todaypayments();
		}
		$data_transactions = array();
		foreach ( $transactions as $transaction ) {
			switch ( $transaction[ 'transactiontype' ] ) {
				case '0':
					$type = 'paymenttoday';
					break;
				case '1':
					$type = 'expensetoday';
					break;
			};
			switch ( $transaction[ 'transactiontype' ] ) {
				case '0':
					$icon = 'ion-log-in';
					break;
				case '1':
					$icon = 'ion-log-out';
					break;
			};
			switch ( $transaction[ 'transactiontype' ] ) {
				case '0':
					$title = lang( 'paymentistoday' );
					break;
				case '1':
					$title = lang( 'expensetoday' );
					break;
			};
			$data_transactions[] = array(
				'id' => $transaction[ 'id' ],
				'amount' => $transaction[ 'amount' ],
				'type' => $type,
				'title' => $title,
				'icon' => $icon,
			);
		};
		echo json_encode( $data_transactions );
	}

	function logs() {
		if (!isAdmin()) {
			$logs = $this->Logs_Model->panel_last_logs_by_staff();
		} else {
			$logs = $this->Logs_Model->panel_last_logs();
		}
		$data_logs = array();
		foreach ( $logs as $log ) {
			$data_logs[] = array(
				'logdate' => date( DATE_ISO8601, strtotime( $log[ 'date' ] ) ),
				'date' => tes_ciuis( $log[ 'date' ] ),
				'detail' => $log[ 'detail' ],
				'customer_id' => $log[ 'customer_id' ],
				'project_id' => $log[ 'project_id' ],
				'staff_id' => $log[ 'staff_id' ],
			);
		};
		echo json_encode( $data_logs );
	}

	function contacts() {
		$contacts = $this->Contacts_Model->get_all_contacts();
		$permissions = $this->Privileges_Model->get_all_common_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$data_contacts = array();
		foreach ( $contacts as $contact ) {
			$arr = array();
			foreach ( $privileges as $privilege ) {
				if ( $privilege[ 'relation' ] == $contact[ 'id' ] && $privilege[ 'relation_type' ] == 'contact' ) {
					array_push( $arr, $privilege[ 'permission_id' ] );
				}
			}
			$data_privileges = array();
			foreach ( $permissions as $permission ) {
				$data_privileges[] = array(
					'id' => $permission[ 'id' ],
					'name' => '' . lang( $permission[ 'permission' ] ) . '',
					'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
				);
			}
			$data_contacts[] = array(
				'id' => $contact[ 'id' ],
				'customer_id' => $contact[ 'customer_id' ],
				'name' => '' . $contact[ 'name' ] . '',
				'surname' => '' . $contact[ 'surname' ] . '',
				'email' => $contact[ 'email' ],
				'phone' => $contact[ 'phone' ],
				'username' => $contact[ 'username' ],
				'address' => $contact[ 'address' ],
				'extension' => $contact[ 'extension' ],
				'mobile' => $contact[ 'mobile' ],
				'password' => $contact[ 'password' ],
				'language' => $contact[ 'language' ],
				'skype' => $contact[ 'skype' ],
				'linkedin' => $contact[ 'linkedin' ],
				'position' => $contact[ 'position' ],
				'primary' => $contact[ 'primary' ],
				'admin' => $contact[ 'admin' ],
				'inactive' => $contact[ 'inactive' ],
				'privileges' => $data_privileges,
			);
		};
		echo json_encode( $data_contacts );
	}

	function contact($id) {
		$contacts = $this->Contacts_Model->get_customer_contacts($id);
		$permissions = $this->Privileges_Model->get_all_common_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$data_contacts = array();
		foreach ( $contacts as $contact ) {
			$arr = array();
			foreach ( $privileges as $privilege ) {
				if ( $privilege[ 'relation' ] == $contact[ 'id' ] && $privilege[ 'relation_type' ] == 'contact' ) {
					array_push( $arr, $privilege[ 'permission_id' ] );
				}
			}
			$data_privileges = array();
			foreach ( $permissions as $permission ) {
				$data_privileges[] = array(
					'id' => $permission[ 'id' ],
					'name' => '' . lang( $permission[ 'permission' ] ) . '',
					'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? true : false . ''
				);
			}
			$data_contacts[] = array(
				'id' => $contact[ 'id' ],
				'customer_id' => $contact[ 'customer_id' ],
				'name' => '' . $contact[ 'name' ] . '',
				'surname' => '' . $contact[ 'surname' ] . '',
				'email' => $contact[ 'email' ],
				'phone' => $contact[ 'phone' ],
				'username' => $contact[ 'username' ],
				'address' => $contact[ 'address' ],
				'extension' => $contact[ 'extension' ],
				'mobile' => $contact[ 'mobile' ],
				'password' => $contact[ 'password' ],
				'language' => $contact[ 'language' ],
				'skype' => $contact[ 'skype' ],
				'linkedin' => $contact[ 'linkedin' ],
				'position' => $contact[ 'position' ],
				'primary' => $contact[ 'primary' ],
				'admin' => $contact[ 'admin' ],
				'inactive' => $contact[ 'inactive' ],
				'privileges' => $data_privileges,
			);
		};
		echo json_encode( $data_contacts );
	}

	function contact_privileges( $id ) {
		$permissions = $this->Privileges_Model->get_all_common_permissions();
		$privileges = $this->Privileges_Model->get_privileges();
		$arr = array();
		foreach ( $privileges as $privilege ) {
			if ( $privilege[ 'relation' ] == $id && $privilege[ 'relation_type' ] == 'contact' ) {
				array_push( $arr, $privilege[ 'permission_id' ] );
			}
		}
		foreach ( $permissions as $permission ) {

			$data_privileges[] = array(
				'id' => $permission[ 'id' ],
				'name' => '' . lang( $permission[ 'permission' ] ) . '',
				'value' => '' . ( array_search( $permission[ 'id' ], $arr ) !== FALSE ) ? 'true' : 'false' . '',
			);
		}
		echo json_encode( $data_privileges );
	}

	function customers() {
		$customers = $this->Customers_Model->get_all_customers();
		$data_customers = array();
		foreach ( $customers as $customer ) {
			switch ( $customer[ 'type' ] ) {
				case '0':
					$name = $customer[ 'company' ];
					$type = lang( 'corporatecustomers' );
					break;
				case '1':
					$name = $customer[ 'namesurname' ];
					$type = lang( 'individual' );
					break;
			};
			$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 3 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_unpaid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'total' )->from( 'invoices' )->where( '(status_id = 2 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_paid_invoice_amount = $this->db->get()->row()->total;
			$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(transactiontype = 0 AND customer_id = ' . $customer[ 'id' ] . ') ' );
			$total_paid_amount = $this->db->get()->row()->amount;
			$contacts = $this->Contacts_Model->get_customer_contacts( $customer[ 'id' ] );
			$country = $this->db->get_where( 'countries', array( 'id' => $customer[ 'country_id' ] ) )->row_array();
			$billing_country = $this->db->get_where( 'countries', array( 'id' => $customer[ 'billing_country' ] ) )->row_array();
			$shipping_country = $this->db->get_where( 'countries', array( 'id' => $customer[ 'shipping_country' ] ) )->row_array();
			$data_customers[] = array(
				'id' => $customer[ 'id' ],
				'customer_id' => $customer[ 'id' ],
				'name' => $name,
				'address' => $customer[ 'address' ],
				'email' => $customer[ 'email' ],
				'phone' => $customer[ 'phone' ],
				'billing_street' => $customer[ 'billing_street' ],
				'billing_city' => $customer[ 'billing_city' ],
				'billing_state' => $customer[ 'billing_state' ],
				'billing_zip' => $customer[ 'billing_zip' ],
				'billing_country' => $billing_country,
				'shipping_street' => $customer[ 'shipping_street' ],
				'shipping_city' => $customer[ 'shipping_city' ],
				'shipping_state' => $customer[ 'shipping_state' ],
				'shipping_zip' => $customer[ 'shipping_zip' ],
				'shipping_country' => $shipping_country,
				'customer_country' => $country,
				'balance' => $total_unpaid_invoice_amount - $total_paid_amount + $total_paid_invoice_amount,
				'default_payment_method' => $customer[ 'default_payment_method' ],
				'contacts' => $contacts,
				'' . lang( 'filterbytype' ) . '' => $type,
				'' . lang( 'filterbycountry' ) . '' => $customer[ 'country' ],
			);
		};
		echo json_encode( $data_customers );
	}

	function customer( $id ) {
		$customer = $this->Customers_Model->get_customers( $id );
		$contacts = $this->Contacts_Model->get_customer_contacts( $id );
		$subsidiaries = $this->Customers_Model->get_subsidiaries( $id );
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$this->db->where( '(status_id = 2 AND customer_id = ' . $customer[ 'id' ] . ') ' );
		$netrevenue = $this->db->get();
		$this->db->select_sum( 'total' );
		$this->db->from( 'invoices' );
		$this->db->where( '(status_id != 1 AND customer_id = ' . $customer[ 'id' ] . ') ' );
		$grossrevenue = $this->db->get();
		$data_customerdetail = array(
			'id' => $customer[ 'id' ],
			'type' => $customer[ 'type' ],
			'isIndividual' => ($customer['type'] == '1')? true:false,
			'created' => $customer[ 'created' ],
			'staff_id' => $customer[ 'staff_id' ],
			'company' => $customer[ 'company' ],
			'namesurname' => $customer[ 'namesurname' ],
			'taxoffice' => $customer[ 'taxoffice' ],
			'taxnumber' => $customer[ 'taxnumber' ],
			'ssn' => $customer[ 'ssn' ],
			'executive' => $customer[ 'executive' ],
			'address' => $customer[ 'address' ],
			'zipcode' => $customer[ 'zipcode' ],
			'country_id' => $customer[ 'country_id' ],
			'state' => $customer[ 'state' ],
			'city' => $customer[ 'city' ],
			'town' => $customer[ 'town' ],
			'default_payment_method' => $customer[ 'default_payment_method' ],
			'billing_street' => $customer[ 'billing_street' ],
			'billing_city' => $customer[ 'billing_city' ],
			'billing_state' => $customer[ 'billing_state' ],
			'billing_zip' => $customer[ 'billing_zip' ],
			'billing_country' => $customer[ 'billing_country' ],
			'shipping_street' => $customer[ 'shipping_street' ],
			'shipping_city' => $customer[ 'shipping_city' ],
			'shipping_state' => $customer[ 'shipping_state' ],
			'shipping_zip' => $customer[ 'shipping_zip' ],
			'shipping_country' => $customer[ 'shipping_country' ],
			'phone' => $customer[ 'phone' ],
			'fax' => $customer[ 'fax' ],
			'email' => $customer[ 'email' ],
			'web' => $customer[ 'web' ],
			'risk' => intval( $customer[ 'risk' ] ),
			'netrevenue' => $netrevenue->row()->total,
			'grossrevenue' => $grossrevenue->row()->total,
			'country' => $customer[ 'country' ],
			'subsidiaries' => $subsidiaries,
			'contacts' => $contacts,
			'chart_data' => $this->Report_Model->customer_annual_sales_chart( $id ),
		);
		echo json_encode( $data_customerdetail );
	}

	function countries() {
		$countries = $this->db->order_by( "id", "asc" )->get( 'countries' )->result_array();
		$data_countries = array();
		foreach ( $countries as $country ) {
			$data_countries[] = array(
				'id' => $country[ 'id' ],
				'shortname' => $country[ 'shortname' ],
				'isocode' => $country[ 'isocode' ],
			);
		};
		echo json_encode( $data_countries );
	}

	function events() {
		$events = $this->Events_Model->get_all_events();
		$data_events = array();
		foreach ( $events as $event ) {
			if ( $event[ 'end' ] < date( " Y-m-d h:i:sa" ) ) {
				$status = 'past';
			} else {
				$status = 'next';
			};
			$data_events[] = array(
				'day' => date( 'D', strtotime( $event[ 'start' ] ) ),
				'aday' => _dDay( $event[ 'start' ] ),
				'start' => _adate( $event[ 'start' ] ),
				'start_iso_date' => date( DATE_ISO8601, strtotime( $event[ 'start' ] ) ),
				'start_date' => $event[ 'start' ],
				'end_date' => $event[ 'end' ],
				'detail' => $event[ 'detail' ],
				'title' => $event[ 'title' ],
				'staff' => $event[ 'staff' ],
				'status' => $status,
				'id' => $event[ 'id' ],
				'date' => date( DATE_ISO8601, strtotime( $event[ 'start' ] ) ),
			);
		};
		echo json_encode( $data_events );
	}

	function appointments() {
		$appointments = $this->Appointments_Model->get_all_appointments();
		$data_appointments = array();
		foreach ( $appointments as $appointment ) {
			if ( $appointment[ 'booking_date' ] < date( "Y-m-d" ) ) {
				$status = 'past';
			} else {
				$status = 'next';
			};
			$data_appointments[] = array(
				'id' => $appointment[ 'id' ],
				'day' => date( 'D', strtotime( $appointment[ 'booking_date' ] ) ),
				'aday' => _dDay( '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '' ),
				'start' => _adate( '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '' ),
				'start_iso_date' => date( DATE_ISO8601, strtotime( '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '' ) ),
				'start_date' => $appointment[ 'booking_date' ],
				'title' => '' . $message = sprintf( lang( 'appointment_for' ), $appointment[ 'contact_name' ] ) . '',
				'staff' => $appointment[ 'staff' ],
				'contact' => '' . $appointment[ 'contact_name' ] . ' ' . $appointment[ 'contact_surname' ] . '',
				'status_class' => $status,
				'status' => $appointment[ 'status' ],
				'date' => date( DATE_ISO8601, strtotime( $appointment[ 'booking_date' ] ) ),
			);
		};
		echo json_encode( $data_appointments );
	}


	function all_appointments() {
		$appointments = $this->Appointments_Model->get_all_confirmed_appointments();
		$data_appointments = array();
		foreach ( $appointments as $appointment ) {
			if ( $appointment[ 'booking_date' ] < date( " Y-m-d h:i:sa" ) ) {
				$status = 'past';
			} else {
				$status = 'next';
			};
			$data_appointments[] = array(
				'id' => $appointment[ 'id' ],
				'text' => '' . $message = sprintf( lang( 'appointment_for' ), $appointment[ 'contact_name' ] ) . '',
				'start' => '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'start_time' ] . '',
				'end' => '' . $appointment[ 'booking_date' ] . ' ' . $appointment[ 'end_time' ] . '',

			);
		};
		echo json_encode( $data_appointments );
	}


	function get_google_events( $id ) {
		$staff = $this->Staff_Model->get_staff( $id );
		$str = file_get_contents( 'https://www.googleapis.com/calendar/v3/calendars/' . $staff[ 'google_calendar_id' ] . '/events?key=' . $staff[ 'google_calendar_api_key' ] . '' );
		$json = json_decode( $str, true );
		echo json_encode( $json[ 'items' ] );
	}

	function meetings() {
		$this->db->select( '*,staff.staffname as staff_name,customers.type as type, customers.company as customercompany,customers.namesurname as individual, meetings.id as id ' );
		$this->db->join( 'customers', 'meetings.customer_id = customers.id', 'left' );
		$this->db->join( 'staff', 'meetings.staff_id = staff.id', 'left' );
		$this->db->where( 'meetings.staff_id', $this->session->userdata( 'usr_id' ) );
		$meetings = $this->db->get_where( 'meetings' )->result_array();
		$data_meetings = array();
		foreach ( $meetings as $meet ) {
			if ( $meet[ 'type' ] == 1 ) {
				$customer = $meet[ 'individual' ];
			} else $customer = $meet[ 'customercompany' ];
			$data_meetings[] = array(
				'id' => $meet[ 'id' ],
				'title' => $meet[ 'title' ],
				'description' => $meet[ 'description' ],
				'date' => date( DATE_ISO8601, strtotime( '' . $meet[ 'date' ] . ' ' . $meet[ 'start' ] . '' ) ),
				'start' => $meet[ 'start' ],
				'end' => $meet[ 'end' ],
				'staff' => $meet[ 'staff_name' ],
				'customer' => $customer,
			);
		};
		echo json_encode( $data_meetings );
	}


	function todos() {
		$todos = $this->Trivia_Model->get_todos();
		$data_todo = array();
		foreach ( $todos as $todo ) {
			$data_todo[] = array(
				'id' => $todo[ 'id' ],
				'date' => date( DATE_ISO8601, strtotime( $todo[ 'date' ] ) ),
				'description' => $todo[ 'description' ],
			);
		};
		echo json_encode( $data_todo );
	}

	function donetodos() {
		$donetodos = $this->Trivia_Model->get_done_todos();
		$data_donetodos = array();
		foreach ( $donetodos as $donetodo ) {
			$data_donetodos[] = array(
				'id' => $donetodo[ 'id' ],
				'date' => date( DATE_ISO8601, strtotime( $donetodo[ 'date' ] ) ),
				'description' => $donetodo[ 'description' ],
			);
		};
		echo json_encode( $data_donetodos );
	}

	function accounts() {
		$accounts = $this->Accounts_Model->get_all_accounts();
		$data_account = array();
		foreach ( $accounts as $account ) {
			switch ( $account[ 'type' ] ) {
				case '0':
					$icon = 'mdi mdi-balance-wallet';
					break;
				case '1':
					$icon = 'mdi mdi-balance';
					break;
			};
			switch ( $account[ 'status_id' ] ) {
				case '0':
					$status = lang( 'accuntactive' );
					break;
				case '0':
					$status = lang( 'accuntnotactive' );
					break;
			};
			$data_account[] = array(
				'id' => $account[ 'id' ],
				'name' => $account[ 'name' ],
				'amount' => $data = $amountby = $this->Report_Model->get_account_amount( $account[ 'id' ] ),
				'icon' => $icon,
				'status' => $status,
			);
		};
		echo json_encode( $data_account );
	}

	function account( $id ) {
		$account = $this->Accounts_Model->get_accounts( $id );
		$payments = $this->db->select( '*' )->order_by( 'id', 'desc' )->get_where( 'payments', array( 'account_id' => $id ) )->result_array();
		$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(account_id = ' . $id . ' and transactiontype = 0)' );
		$account_incomings_sum = $this->db->get()->row()->amount;
		$this->db->select_sum( 'amount' )->from( 'payments' )->where( '(account_id = ' . $id . ' and transactiontype = 1)' );
		$account_outgoings_sum = $this->db->get()->row()->amount;
		$account_sum = ( $account_incomings_sum - $account_outgoings_sum );
		if ( !empty( $account_sum ) ) {
			$account_total = $account_incomings_sum - $account_outgoings_sum;
		} else {
			$account_total = 0;
		}
		switch ( $account[ 'status_id' ] ) {
			case '1':
				$is_status = false;
				break;
			case '0':
				$is_status = true;
				break;
		}

		$payments_data = array();
		foreach ( $payments as $payment ) {
			if ( $payment[ 'customer_id' ] != 0 ) {
				$customer = $this->Customers_Model->get_customers( $payment[ 'customer_id' ] );
				switch ( $customer[ 'type' ] ) {
					case '0':
						$name = $customer[ 'company' ];
						$type = lang( 'corporatecustomers' );
						break;
					case '1':
						$name = $customer[ 'namesurname' ];
						$type = lang( 'individual' );
						break;
				};
			} else {
				$name = 'false';
			}
			$staff = $this->Staff_Model->get_staff( $payment[ 'staff_id' ] );

			if ( $payment[ 'customer_id' ] != 0 ) {
				$for_customer = true;
			} else {
				$for_customer = false;
			}
			$payments_data[] = array(
				'id' => $payment[ 'id' ],
				'transactiontype' => $payment[ 'transactiontype' ],
				'is_transfer' => $payment[ 'is_transfer' ],
				'invoice_id' => $payment[ 'invoice_id' ],
				'expense_id' => $payment[ 'expense_id' ],
				'customer_id' => $payment[ 'customer_id' ],
				'customer' => $name,
				'amount' => $payment[ 'amount' ],
				'account_id' => $payment[ 'account_id' ],
				'date' => date( DATE_ISO8601, strtotime( $payment[ 'date' ] ) ),
				'attachment' => $payment[ 'attachment' ],
				'staff_id' => $payment[ 'staff_id' ],
				'not' => $payment[ 'not' ],
				'staff' => $staff[ 'staffname' ],
				'for_customer' => $for_customer,
			);
		};

		$data_account = array(
			'id' => $account[ 'id' ],
			'name' => $account[ 'name' ],
			'type' => $account[ 'type' ],
			'bankname' => $account[ 'bankname' ],
			'branchbank' => $account[ 'branchbank' ],
			'account' => $account[ 'account' ],
			'iban' => $account[ 'iban' ],
			'account_total' => $account_total,
			'status' => $is_status,
			'payments' => $payments_data,
		);
		echo json_encode( $data_account );
	}

	function leads() {
		if ( !if_admin ) {
			$leads = $this->Leads_Model->get_all_leads_for_admin();
		} else {
			$leads = $this->Leads_Model->get_all_leads();
		};
		$data_leads = array();
		foreach ( $leads as $lead ) {
			$tags = $this->db->select( '*' )->get_where( 'tags', array( 'relation_type' => 'lead', 'relation' => $lead[ 'id' ] ) )->row_array();
			$data_leads[] = array(
				'id' => $lead[ 'id' ],
				'name' => $lead[ 'leadname' ],
				'company' => $lead[ 'company' ],
				'phone' => $lead[ 'leadphone' ],
				'color' => $lead[ 'color' ],
				'status' => $lead[ 'status' ],
				'statusname' => $lead[ 'statusname' ],
				'source' => $lead[ 'source' ],
				'sourcename' => $lead[ 'sourcename' ],
				'assigned' => $lead[ 'leadassigned' ],
				'avatar' => $lead[ 'assignedavatar' ],
				'staff' => $lead[ 'staff_id' ],
				'createddate' => $lead[ 'created' ],
				'' . lang( 'filterbystatus' ) . '' => $lead[ 'statusname' ],
				'' . lang( 'filterbysource' ) . '' => $lead[ 'sourcename' ],
			);
		};
		echo json_encode( $data_leads );
	}

	function leads_by_leadsource_leadpage() {
		echo json_encode( $this->Report_Model->leads_by_leadsource_leadpage() );
	}

	function lead( $id ) {
		$lead = $this->Leads_Model->get_lead( $id );
		switch ( $lead[ 'public' ] ) {
			case '0':
				$is_public = false;
				break;
			case '1':
				$is_public = true;
				break;
		}
		switch ( $lead[ 'type' ] ) {
			case '0':
				$is_individual = false;
				break;
			case '1':
				$is_individual = true;
				break;
		}
		$data_lead = array(
			'id' => $lead[ 'id' ],
			'type' => $lead[ 'type' ],
			'name' => $lead[ 'leadname' ],
			'title' => $lead[ 'title' ],
			'company' => $lead[ 'company' ],
			'description' => $lead[ 'description' ],
			'country_id' => $lead[ 'country_id' ],
			'country' => $lead[ 'leadcountry' ],
			'zip' => $lead[ 'zip' ],
			'city' => $lead[ 'city' ],
			'state' => $lead[ 'state' ],
			'email' => $lead[ 'leadmail' ],
			'address' => $lead[ 'address' ],
			'website' => $lead[ 'website' ],
			'phone' => $lead[ 'leadphone' ],
			'assigned' => $lead[ 'leadassigned' ],
			'assigned_id' => $lead[ 'assigned_id' ],
			'created' => $lead[ 'created' ],
			'status_id' => $lead[ 'status' ],
			'status' => $lead[ 'statusname' ],
			'source_id' => $lead[ 'source' ],
			'source' => $lead[ 'sourcename' ],
			'lastcontact' => $lead[ 'lastcontact' ],
			'dateassigned' => $lead[ 'dateassigned' ],
			'staff_id' => $lead[ 'staff_id' ],
			'dateconverted' => $lead[ 'dateconverted' ],
			'date_contacted' => $lead[ 'date_contacted' ],
			'lost' => $lead[ 'lost' ],
			'junk' => $lead[ 'junk' ],
			'public' => $is_public,
			'type' => $is_individual,
		);
		echo json_encode( $data_lead );
	}

	function leadstatuses() {
		$leadstatuses = $this->Leads_Model->get_leads_status();
		echo json_encode( $leadstatuses );
	}

	function leadsources() {
		$leadsources = $this->Leads_Model->get_leads_sources();
		echo json_encode( $leadsources );
	}

	function products() {
		$products = $this->Products_Model->get_all_products();
		$settings = $this->Settings_Model->get_settings_ciuis();
		$data_products = array();
		foreach ( $products as $product ) {
			$data_products[] = array(
				'product_id' => $product[ 'id' ],
				'code' => $product[ 'code' ],
				'name' => $product[ 'productname' ],
				'description' => $product[ 'description' ],
				'price' => $product[ 'sale_price' ],
				'tax' => $product[ 'vat' ],
				'purchase_price' => $product[ 'purchase_price' ],
				'category_name' => $product[ 'name' ],
				'stock' => $product[ 'stock' ]
			);
		};
		echo json_encode( $data_products );
	}

	function product( $id ) {
		$product = $this->Products_Model->get_product_by_id( $id ); 
		if (!$product[ 'categoryid' ] || $product[ 'categoryid' ] == 0) {
			$product[ 'categoryid' ] = null;
			$product[ 'name' ] = null;
			$product[ 'id' ] = $id;
		} else {
			$product = $this->Products_Model->get_products( $id ); 
		}
		//
		$total_product_sales = $this->db->from( 'items' )->where( 'product_id = ' . $product[ 'id' ] . '' )->get()->num_rows();
		$this->db->select_sum( 'total' );
		$this->db->from( 'items' );
		$this->db->where( 'product_id = ' . $product[ 'id' ] . '' );
		$netearnings = $this->db->get()->row()->total;
		if ( !empty( $netearnings ) ) {
			$total = $netearnings;
		} else {
			$total = 0;
		}
		$this->db->select_sum( 'tax' );
		$this->db->from( 'items' );
		$this->db->where( 'product_id = ' . $product[ 'id' ] . '' );
		$total_tax_products = $this->db->get()->row()->tax;
		if ( !empty( $netearnings ) ) {
			$total_tax = $total_tax_products;
		} else {
			$total_tax = 0;
		}
		$data_product = array(
			'id' => $product[ 'id' ],
			'code' => $product[ 'code' ],
			'productname' => $product[ 'productname' ],
			'description' => $product[ 'description' ],
			'productimage' => $product[ 'productimage' ],
			'purchase_price' => $product[ 'purchase_price' ],
			'sale_price' => $product[ 'sale_price' ],
			'stock' => $product[ 'stock' ],
			'categoryid' => $product[ 'categoryid' ],
			'vat' => $product[ 'vat' ],
			'status_id' => $product[ 'status_id' ],
			'category_name' => $product[ 'name' ],
			'total_sales' => $total_product_sales,
			'net_earning' => $total - $total_tax,
		);
		echo json_encode( $data_product );
	}

	function lang( $lang ) {
		$this->lang->load( $lang, $lang );
		$all_lang_array = $this->lang->language;
		echo json_encode( $all_lang_array );
	}

	function custom_fields_by_type( $type ) {
		$custom_fields = $this->Fields_Model->custom_fields_by_type( $type );
		$data_custom_fields = array();
		foreach ( $custom_fields as $field ) {
			$data_custom_fields[] = array(
				'id' => $field[ 'id' ],
				'name' => $field[ 'name' ],
				'type' => $field[ 'type' ],
				'order' => $field[ 'order' ],
				'data' => json_decode( $field[ 'data' ] ),
				'relation' => $field[ 'relation' ],
				'permission' => $field[ 'permission' ] === 'true' ? true : false,
				'active' => $field[ 'active' ] === 'true' ? true : false,
			);
		};
		if ( $custom_fields ) {
			echo json_encode( $data_custom_fields );
		} else {
			echo json_encode( false );
		}
	}

	function custom_fields() {
		$custom_fields = $this->Fields_Model->custom_fields();
		$data_custom_fields = array();
		foreach ( $custom_fields as $field ) {
			$data_custom_fields[] = array(
				'id' => intval( $field[ 'id' ] ),
				'name' => $field[ 'name' ],
				'type' => $field[ 'type' ],
				'order' => intval( $field[ 'order' ] ),
				'data' => json_decode( $field[ 'data' ] ),
				'relation' => $field[ 'relation' ],
				'icon' => $field[ 'icon' ],
				'permission' => $field[ 'permission' ] === 'true' ? true : false,
				'active' => $field[ 'active' ] === 'true' ? true : false,
			);
		};
		echo json_encode( $data_custom_fields );
	}

	function custom_field_data_by_id( $id ) {
		$field = $this->Fields_Model->custom_field_data_by_id( $id );
		$data_custom_field = array(
			'id' => intval( $field[ 'id' ] ),
			'name' => $field[ 'name' ],
			'type' => $field[ 'type' ],
			'order' => intval( $field[ 'order' ] ),
			'data' => json_decode( $field[ 'data' ] ),
			'relation' => $field[ 'relation' ],
			'icon' => $field[ 'icon' ],
			'permission' => $field[ 'permission' ] === 'true' ? true : false,
			'active' => $field[ 'active' ] === 'true' ? true : false,
		);
		echo json_encode( $data_custom_field );
	}

	function custom_fields_data_by_type( $type, $id ) {
		$fields = $this->Fields_Model->custom_fields_by_type( $type );
		$data_custom_fields = array();
		foreach ( $fields as $field ) {
			$data = $this->Fields_Model->custom_fields_data_by_type( $type, $id, $field[ 'id' ] );
			if ( $data ) {
				switch ( $field[ 'type' ] ) {
					case 'input':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'date':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'number':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'textarea':
						$data_last = $data[ 'data' ];
						$selected_opt = 0;
						break;
					case 'select':
						$data_last = json_decode( $field[ 'data' ] );
						$selected_opt = json_decode( $data[ 'data' ] );
						break;
				}
				if ( $field[ 'icon' ] != null ) {
					$icon = $field[ 'icon' ];
				} else {
					$icon = 'mdi mdi-info-outline';
				}
			} else {
				$data_last = json_decode( $field[ 'data' ] );
				$selected_opt = null;
			}
			if ( $field[ 'icon' ] != null ) {
				$icon = $field[ 'icon' ];
			} else {
				$icon = 'mdi mdi-info-outline';
			}
			$data_custom_fields[] = array(
				'id' => $field[ 'id' ],
				'name' => $field[ 'name' ],
				'type' => $field[ 'type' ],
				'order' => $field[ 'order' ],
				'data' => $data_last,
				'selected_opt' => $selected_opt,
				'relation' => $field[ 'relation' ],
				'icon' => $icon,
				'permission' => $field[ 'permission' ] === 'true' ? true : false,
				'active' => $field[ 'active' ] === 'true' ? true : false,
			);
		};
		echo json_encode( $data_custom_fields );
	}

	function search() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$input = $this->input->post( 'input' );
			if (!$input || $input == '' || strlen($input) < 2) {
				echo false;
			} else {
				echo json_encode($this->Search_Model->search_data($input));
			}
		} else {
			redirect( 'panel/' );
		}
	}

	function timer() {
		$status = $this->input->post( 'status' );
		if ($status == 'start') {
			$id = $this->Tasks_Model->start_timer($this->session->usr_id);
			if ($id) {
				$data['success'] = true;
				$data['type'] = 'Success';
				$data['message'] = lang('timer_started');
				echo json_encode($data);
			} else {
				$data['success'] = false;
				$data['type'] = lang('success');
				$data['message'] = lang('errormessage');
				echo json_encode($data);
			}
		} else if ($status == 'stop') {
			$task = $this->input->post( 'task' );
			$action = $this->input->post( 'action' );
			if ($task == '' || !$task) {
				$data['success'] = false;
				$data['message'] = lang('selectinvalidmessage'). ' ' .lang('task');
				echo json_encode($data);
			} else {
				if ($action == 'assign' || $action == 'stop') {
					if ($action == 'assign') {
						$params = array(
							//'relation' => 'task',
							'task_id' => $task,
							'note' => $this->input->post( 'note' ),
						);
						$message = lang('task_assigned');
					}
					if ($action == 'stop') {
						$date = new DateTime();
						$params = array(
							//'relation' => 'task',
							'task_id' => $task,
							'note' => $this->input->post( 'note' ),
							'end' => $date->format('Y-m-d H:i:s')
						);
						$message = lang('timer_stopped');
					}
					$timer = $this->Tasks_Model->get_timer();
					$result = $this->Tasks_Model->stop_timer($timer['id'], $params);
					if ($result) {
						$data['success'] = true;
						$data['type'] = lang('success');
						$data['message'] = $message;
						$data['params'] = $params;
						echo json_encode($data);
					} else {
						$data['success'] = false;
						$data['type'] = lang('error');
						$data['message'] = lang('errormessage');
						echo json_encode($data);
					}
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			}
		}
	}

	function get_timer() {
		$timer = $this->Tasks_Model->get_timer();
		if ($timer) {
			//$total = time()-$timer['start'];
			$date1 = new DateTime($timer['start']);
			$diffs = $date1->diff(new DateTime());
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
			$result = array(
				'started' => $timer['start'],
				'task_id' => $timer['task_id'],
				'total' => $total,
				'task' => $timer['name'],
				'note' => $timer['note'],
			);
			$data['success'] = true;
			$data['status'] = true;
			$data['result'] = $result;
			echo json_encode($data);
		} else {
			$data['success'] = true;
			$data['status'] = false;
			echo json_encode($data);
		}
	}

	function get_open_tasks() {
		$tasks = $this->Tasks_Model->get_all_tasks_for_timer();
		$data_tasks = array();
		foreach ( $tasks as $task ) {
			$data_tasks[] = array(
				'id' => $task[ 'id' ],
				'name' => $task[ 'name' ],
				'status_id' => $task[ 'status_id' ],
			);
		};
		echo json_encode( $data_tasks );
	}

	function load_config() {
		$settings = $this->Settings_Model->get_rebranding_data();
		echo json_encode( $settings );
	}
}