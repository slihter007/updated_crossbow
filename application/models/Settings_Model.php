<?php
class Settings_Model extends CI_Model {
	function get_settings( $settingname ) {
		$this->db->select( '*,countries.shortname as country,languages.name as language, currencies.name as currencyname,currencies.code as currency,settings.settingname as settingname ' );
		$this->db->join( 'languages', 'settings.languageid = languages.foldername', 'left' );
		$this->db->join( 'currencies', 'settings.currencyid = currencies.id', 'left' );
		$this->db->join( 'countries', 'settings.country_id = countries.id', 'left' );
		return $this->db->get_where( 'settings', array( 'settingname' => $settingname ) )->row_array();
	}

	function get_settings_ciuis() {
		$this->db->select( '*,countries.shortname as country,languages.name as language, currencies.name as currencyname,currencies.code as currency,settings.settingname as settingname ' );
		$this->db->join( 'languages', 'settings.languageid = languages.foldername', 'left' );
		$this->db->join( 'currencies', 'settings.currencyid = currencies.id', 'left' );
		$this->db->join( 'countries', 'settings.country_id = countries.id', 'left' );
		return $this->db->get_where( 'settings', array( 'settingname' === 'ciuis' ) )->row_array();
	}
	
	function get_settings_ciuis_origin() {
		return $this->db->get_where( 'settings', array( 'settingname' === 'ciuis' ) )->row_array();
	}

	function get_payment_modes() {
		//$this->db->group_by('relation'); 
		return $this->db->get_where('payment_modes', array())->result_array();
	}

	function update_settings( $settingname, $params ) {
		$this->db->where( 'settingname', $settingname );
		$staffname = $this->session->staffname;
		$loggedinuserid = $this->session->usr_id;
		$this->db->insert( 'logs', array(
			'date' => date( 'Y-m-d H:i:s' ),
			'detail' => ( '<a href="staff/staffmember/' . $loggedinuserid . '"> ' . $staffname . '</a> ' . lang( 'updatedsettings' ) . '' ),
			'staff_id' => $loggedinuserid
		) );
		$response = $this->db->update( 'settings', $params );
	}

	function update_appconfig() {
		$this->db->where('name', 'inv_prefix')->update('appconfig', array('value' => $this->input->post('inv_prefix')));
		$this->db->where('name', 'inv_suffix')->update('appconfig', array('value' => $this->input->post('inv_suffix')));
		$this->db->where('name', 'project_prefix')->update('appconfig', array('value' => $this->input->post('project_prefix')));
		$this->db->where('name', 'project_suffix')->update('appconfig', array('value' => $this->input->post('project_suffix')));
		$this->db->where('name', 'order_prefix')->update('appconfig', array('value' => $this->input->post('order_prefix')));
		$this->db->where('name', 'order_suffix')->update('appconfig', array('value' => $this->input->post('order_suffix')));
		$this->db->where('name', 'expense_suffix')->update('appconfig', array('value' => $this->input->post('expense_suffix')));
		$this->db->where('name', 'expense_prefix')->update('appconfig', array('value' => $this->input->post('expense_prefix')));
		$this->db->where('name', 'proposal_suffix')->update('appconfig', array('value' => $this->input->post('proposal_suffix')));
		$this->db->where('name', 'proposal_prefix')->update('appconfig', array('value' => $this->input->post('proposal_prefix')));
		$this->db->where('name', 'tax_label')->update('appconfig', array('value' => $this->input->post('tax_label')));

		// $this->db->where('name', 'custom_css')->update('appconfig', array('value' => $this->input->post('custom_css')));
		// $this->db->where('name', 'custom_footer_js')->update('appconfig', array('value' => $this->input->post('custom_footer_js')));
		// $this->db->where('name', 'custom_header_js')->update('appconfig', array('value' => $this->input->post('custom_header_js')));
		if(ini_get('allow_url_fopen')) {
			chmod("./assets/css/custom_css.css", 0777);
			chmod("./assets/js/custom_footer_js.txt", 0777);
			chmod("./assets/js/custom_header_js.txt", 0777);
			file_put_contents("./assets/css/custom_css.css", $this->input->post('custom_css', true));
			file_put_contents("./assets/js/custom_footer_js.txt", $this->input->post('custom_header_js', true));
			file_put_contents("./assets/js/custom_header_js.txt", $this->input->post('custom_footer_js', true));
		}
	}

	function is_demo() {
		$data = $this->db->get_where('settings', array())->row_array();
		if ($data['is_demo'] == '1') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function get_version_detail() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get( 'versions' )->row_array();
	}

	function db_backup( $params ) {
		$this->db->insert( 'db_backup', $params );
		return $this->db->insert_id();
	}

	function get_backup() {
		$this->db->order_by( 'id', 'desc' );
		return $this->db->get_where( 'db_backup', array( '' ) )->result_array();
	}
	
	function get_db_backup($id) {
		return $this->db->get_where( 'db_backup', array( 'id' => $id ) )->row_array();
	}

	function get_currencies() {
		return $this->db->get_where( 'currencies', array( '' ) )->result_array();
	}
	function get_languages() {
		return $this->db->get_where( 'languages', array( '' ) )->result_array();
	}
	function get_department( $id ) {
		return $this->db->get_where( 'departments', array( 'id' => $id ) )->row_array();
	}

	function get_departments() {
		return $this->db->get_where( 'departments', array( '' ) )->result_array();
	}
	function add_department( $params ) {
		$this->db->insert( 'departments', $params );
		return $this->db->insert_id();
	}
	function update_department( $id, $params ) {
		$this->db->where( 'id', $id );
		$response = $this->db->update( 'departments', $params );
	}
	function delete_department( $id ) {
		$response = $this->db->delete( 'departments', array( 'id' => $id ) );
	}
	function get_menus() {
		if (!if_admin) {
			return $this->db->get_where( 'menu', array( 'main_menu' => '0' ) )->result_array();
		}else{
			return $this->db->get_where( 'menu', array( 'main_menu' => '0', 'show_staff' => '0') )->result_array();
		};
		
	}
	function get_submenus( $id ) {
		if (!if_admin) {
			return $this->db->get_where( 'menu', array( 'main_menu' => $id ) )->result_array();
		}else{
			return $this->db->get_where( 'menu', array( 'main_menu' => $id, 'show_staff' => '0') )->result_array();
		};
	}

	function get_crm_lang() {
        $this->db->limit(1, 0);
        $query = $this->db->get('settings');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->languageid;
        }
    }
	function default_timezone() {
        $query = $this->db->get('settings');
        $row = $query->row();
        return $row->default_timezone;
    }
	
	function two_factor_authentication() {
        $query = $this->db->get('settings');
        $row = $query->row();
        return $row->two_factor_authentication;
    }
	
	function get_currency() {
        $this->db->limit(1, 0);
        $query = $this->db->get('settings');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $currencyid =  $row->currencyid;
        }
		$this->db->limit(1, 0);
        $query = $this->db->get_where( 'currencies', array( 'id' => $currencyid ));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->code;
        }
    }
	public function load_config() {
        $this->db->limit(1, 0);
        $query = $this->db->get('settings');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            return FALSE;
        }
    }

    function get_rebranding_data() {
		$configs = $this->db->get_where('branding', array())->result_array();
		$data = array();
		foreach ($configs as $config) {
			$data[$config['name']] = $config['value'];
		}
		return $data;
	}

    function get_payment_gateway_data() {
		$payments = $this->get_payment_modes();
		$data = array();
		foreach ($payments as $payment) {
			$data[$payment['name']] = $payment['value'];
			if ($payment['name'] == 'authorize_aim_active' || 
				$payment['name'] == 'paypal_active' || 
				$payment['name'] == 'stripe_active' || 
				$payment['name'] == 'payu_money_active' || 
				$payment['name'] == 'ccavenue_active' || 
				$payment['name'] == 'paypal_test_mode_enabled' ||
				$payment['name'] == 'payu_money_test_mode_enabled' ||
				$payment['name'] == 'ccavenue_test_mode' ||
				$payment['name'] == 'razorpay_active' ||
				$payment['name'] == 'razorpay_test_mode_enabled' ||
				$payment['name'] == 'authorize_test_mode_enabled' //||
				//$payment['name'] == 'payu_money_active' ||
				//$payment['name'] == 'payu_money_active'
				) {
					if ($payment['value'] == '1') {
						$data[$payment['name']] = TRUE;
					} else if ($payment['value'] == '0') {
						$data[$payment['name']] = FALSE;
					}
			}
			if ($payment['name'] == 'primary_bank_account') {
				if ($payment['value']) {
					$bank = $this->db->get_where('accounts', array('id' => $payment['value']))->row_array();
					if (count($bank) > 0) {
						$data['bank'] = $bank['name'];
					}
				}
			}
		}
		return $data;
	}
}