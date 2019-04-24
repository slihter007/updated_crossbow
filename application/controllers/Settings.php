<?php
if ( !defined( 'BASEPATH' ) )exit( 'No direct script access allowed' );
include_once APPPATH.'/third_party/script/script_update_config.php';
include_once APPPATH.'/third_party/script/script_update_functions.php';
class Settings extends CIUIS_Controller {

	function index() {
		$data[ 'title' ] = lang( 'settings' );
		if ( $this->session->userdata( 'admin' ) ) {
			$this->load->view( 'inc/header', $data );
			$this->load->view( 'settings/index', $data );
			$this->load->view( 'inc/footer', $data );
		} else {
			redirect( 'panel' );
		}
	}

	function update( $settingname ) {
		if ( isset( $settingname ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$is_demo = $this->Settings_Model->is_demo();
				if (!$is_demo) {
					$config[ 'upload_path' ] = './uploads/ciuis_settings/';
					$config[ 'allowed_types' ] = 'gif|jpg|png|jpeg';
					switch ( $_POST[ 'pushState' ] ) {
						case 'true':
							$PushState = 0;
							break;
						case 'false':
							$PushState = 0;
							break;
					}
					switch ( $_POST[ 'voicenotification' ] ) {
						case 'true':
							$VoiceNotification = 1;
							break;
						case 'false':
							$VoiceNotification = 0;
							break;
					}
					switch ( $_POST[ 'paypalenable' ] ) {
						case 'true':
							$paypalenable = 1;
							break;
						case 'false':
							$paypalenable = 0;
							break;
					}
					switch ( $_POST[ 'paypalsandbox' ] ) {
						case 'true':
							$paypalsandbox = 1;
							break;
						case 'false':
							$paypalsandbox = 0;
							break;
					}
					switch ( $_POST[ 'authorize_enable' ] ) {
						case 'true':
							$authorize_enable = 1;
							break;
						case 'false':
							$authorize_enable = 0;
							break;
					}
					switch ( $_POST[ 'two_factor_authentication' ] ) {
						case 'true':
							$two_factor_authentication = 1;
							break;
						case 'false':
							$two_factor_authentication = 0;
							break;
					}
					$is_mysql = $this->input->post('is_mysql');
					if ($is_mysql == '1' || $is_mysql == 'true') {
						$is_mysql = '1';
					} else {
						$is_mysql = '0';
					}
					$params = array(
						'crm_name' => $this->input->post( 'crm_name' ),
						'company' => $this->input->post( 'company' ),
						'email' => $this->input->post( 'email' ),
						'address' => $this->input->post( 'address' ),
						'city' => $this->input->post( 'city' ),
						'town' => $this->input->post( 'town' ),
						'state' => $this->input->post( 'state' ),
						'country_id' => $this->input->post( 'country_id' ),
						'zipcode' => $this->input->post( 'zipcode' ),
						'phone' => $this->input->post( 'phone' ),
						'fax' => $this->input->post( 'fax' ),
						'vatnumber' => $this->input->post( 'vatnumber' ),
						'taxoffice' => $this->input->post( 'taxoffice' ),
						'currencyid' => $this->input->post( 'currencyid' ),
						'termtitle' => $this->input->post( 'termtitle' ),
						'termdescription' => $this->input->post( 'termdescription' ),
						'dateformat' => $this->input->post( 'dateformat' ),
						'languageid' => $this->input->post( 'languageid' ),
						'default_timezone' => $this->input->post( 'default_timezone' ),
						'smtphost' => $this->input->post( 'smtphost' ),
						'smtpport' => $this->input->post( 'smtpport' ),
						'emailcharset' => $this->input->post( 'emailcharset' ),
						'smtpusername' => $this->input->post( 'smtpusername' ),
						//'smtppassoword' => $this->input->post( 'smtppassoword' )?$this->input->post( 'smtppassoword' ),
						'sendermail' => $this->input->post( 'sendermail' ),
						'sender_name' => $this->input->post( 'sender_name' ),
						'email_encryption' => $this->input->post( 'email_encryption' ),
						'accepted_files_formats' => $this->input->post( 'accepted_files_formats' ),
						'allowed_ip_adresses' => $this->input->post( 'allowed_ip_adresses' ),
						'pushState' => $PushState,
						'voicenotification' => $VoiceNotification,
						'paypalenable' => $paypalenable,
						'authorize_enable' => $authorize_enable,
						'paypalemail' => $this->input->post( 'paypalemail' ),
						'paypalsandbox' => $paypalsandbox,
						'paypalcurrency' => $this->input->post( 'paypalcurrency' ),
						'authorize_login_id' => $this->input->post( 'authorize_login_id' ),
						'authorize_transaction_key' => $this->input->post( 'authorize_transaction_key' ),
						'authorize_record_account' => $this->input->post( 'authorize_record_account' ),
						'paypal_record_account' => $this->input->post( 'paypal_record_account' ),
						'two_factor_authentication' => $two_factor_authentication,
						'is_mysql' => $is_mysql,
					);
					if ($this->input->post( 'smtppassoword' ) != '********') {
						$params['smtppassoword'] = $this->input->post( 'smtppassoword' );
					}
					$this->Settings_Model->update_settings( $settingname, $params );
					$this->Settings_Model->update_appconfig();
					$datas['success'] = true;
					$datas['message'] = lang('settingsupdated');
					echo json_encode($datas);
				} else {
					$datas['success'] = false;
					$datas['message'] = lang('demo_error');
					echo json_encode($datas);
				}
			}
		}
	}

	function db_backup() {
		$version = $this->Settings_Model->get_version_detail();
        $this->load->helper('file');
        $this->load->dbutil();
        $date = date('Y-m-d_H-i-s');
        $prefs = array('format' => 'zip','ignore'=> array('db_backup', 'versions', 'sessions'), 'filename' => 'DB-backup_' . $date);

        $backup = $this->dbutil->backup($prefs);
        if (!write_file('./uploads/backup/DB-backup_' . $date . '.zip', $backup)) {
            $data['success'] = false;
            $data['message'] = lang('errormessage');
        } else {
        	$persentVersion = $version['versions_name'];
        	write_file('./uploads/backup/DB-backup_' . $date . '.txt', $persentVersion);
        	$zip = new ZipArchive();
	        $this->zip->read_file('./uploads/backup/DB-backup_' . $date . '.txt');
	        $this->zip->archive('./uploads/backup/DB-backup_' . $date . '.zip');
	        unlink('./uploads/backup/DB-backup_' . $date . '.txt');
            $data['success'] = true;
            $data['message'] = lang('backup'). ' '. lang('createmessage');
        }
        $activity = array(
            'staff_id' => $this->session->userdata( 'usr_id' ),
            'version' => $version['versions_name'],
            'created' => date( 'Y-m-d H:i:s' ),
            'filename' => $prefs['filename']
        );
        $this->Settings_Model->db_backup($activity);
        echo json_encode($data);
    }

    function download_backup($file) {
    	if (is_file('./uploads/backup/' . $file)) {
    		$this->load->helper('file');
    		$this->load->helper('download');
    		$data = file_get_contents('./uploads/backup/' . $file);
    		force_download($file, $data);
    	} else {
    		$this->session->set_flashdata( 'ntf4', lang('filenotexist'));
    		redirect('settings/index');
    	}
    }

    function get_backup() {
    	$settings = $this->Settings_Model->get_backup();
		echo json_encode($settings);
    }

    function remove_backup( $id ) {
		if ( isset( $id ) ) {
			$backup = $this->Settings_Model->get_db_backup($id);
			$file = $backup['filename'].'.zip';
			if ($backup['id'] == $id) {
				$response = $this->db->delete( 'db_backup', array( 'id' => $id ) );
				if ( $response ) {
					if (file_exists('./uploads/backup/' . $file)) {
						unlink('./uploads/backup/' . $file);
					}
					$data['success'] = true;
					$data['message'] = lang('backup'). ' ' . lang('deletemessage');
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('errormessage');
					echo json_encode($data);
				}
			} else {
				$data['success'] = false;
				$data['message'] = lang('backup_remove_error');
				echo json_encode($data);
			}
		} else {
			$data['success'] = false;
			$data['message'] = lang('errormessage');
			echo json_encode($data);
		}
	}

	function restore_database() {
        if (isset( $_POST )) {
        	ini_set('max_execution_time', 0); 
        	ini_set('memory_limit','2048M');
        	$version = $this->Settings_Model->get_version_detail();
            $this->load->helper('file'); 
            $this->load->helper('unzip');
            $this->load->database();

            $config['upload_path'] = './uploads/temp/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '9000';
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('upload_file')) {
                $error = $this->upload->display_errors('', ' ');
                $type = 'error';
                $message = $error;
                $this->session->set_flashdata( 'ntf4', $message);
                redirect('settings/index');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $backup = "./uploads/temp/" . $data['upload_data']['file_name'];
            }
            if (!unzip($backup, "./uploads/temp/", true, true)) {
                $type = 'error';
                $message = lang('backup_restore_error');
            } else {
            	$backup = str_replace('.zip', '', $backup);
            	$userVersion = file_get_contents($backup . ".txt");
            	if ($userVersion == $version['versions_name']) {
            		$this->load->dbforge();
	                $file_content = file_get_contents($backup . ".sql");
	                $this->db->query('USE ' . $this->db->database . ';');
	                foreach (explode(";\n", $file_content) as $sql) {
	                    $sql = trim($sql);
	                    if ($sql) {
	                        $this->db->query($sql);
	                    }
	                }
	                $this->session->set_flashdata( 'ntf1', lang('restoresuccess'));
	                unlink($backup . ".sql");
	                unlink($backup . ".txt");
            		unlink($backup . ".zip");
	                redirect('settings/index');
            	} else {
            		unlink($backup . ".sql");
	                unlink($backup . ".txt");
            		unlink($backup . ".zip");
            		$this->session->set_flashdata( 'ntf4', lang('version_missmatch_error'));
            		redirect('settings/index');
            	}
            }
        }
    }

    function restore_backup($id) {
        if ($id) {
        	ini_set('max_execution_time', 0); 
        	ini_set('memory_limit','2048M');
        	$backupDetails = $this->Settings_Model->get_db_backup($id);
        	$version = $this->Settings_Model->get_version_detail();
        	if ($version['versions_name'] == $backupDetails['version']) {
        		$backup =  "./uploads/backup/" . $backupDetails['filename'].'.zip';
	        	if( is_file($backup) ) {
	        		$this->load->helper('file'); 
		            $this->load->helper('unzip');
		            $this->load->database();
		            if (!unzip($backup, "./uploads/backup/", true, true)) {
		                $data['success'] = false;
						$data['message'] = lang('backup_restore_error');
						echo json_encode($data);
		            } else {
		                $this->load->dbforge();
		                $backup = str_replace('.zip', '', $backup);
		                $file_content = file_get_contents($backup . ".sql");
		                $this->db->query('USE ' . $this->db->database . ';');
		                foreach (explode(";\n", $file_content) as $sql) {
		                    $sql = trim($sql);
		                    if ($sql) {
		                        $this->db->query($sql);
		                    }
		                }
		                unlink($backup . ".sql");
		                unlink($backup . ".txt");
		                $data['success'] = true;
						$data['message'] = 'backup_restore_error';
						echo json_encode($data);
		            }
	        	} else {
	        		$data['success'] = false;
					$data['message'] = lang('backup_restore_error');
					echo json_encode($data);
	        	}
        	} else {
        		$data['success'] = false;
        		$data['message'] = lang('version_missmatch_error');
        		echo json_encode($data);
        	}
        } else {
        	$data['success'] = false;
        	$data['message'] = lang('errormessage');
        	echo json_encode($data);
        }
    }

    function replace_files() {
    	if (isset( $_POST )) {
    		$this->load->helper('file'); 
            $this->load->helper('unzip');

            $config['upload_path'] = './';
            $config['allowed_types'] = 'zip';
            $config['max_size'] = '9000';
            $config['overwrite'] = TRUE;
            chmod("./application/view/timesheets/index.php", 0777);

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('upload_file')) {
                $error = $this->upload->display_errors('', ' ');
                $type = 'error';
                $message = $error;
                $this->session->set_flashdata( 'ntf4', $message);
                redirect('settings/index');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $backup = './' . $data['upload_data']['file_name'];
                if (!unzip($backup, './', true, true)) {
	                $type = 'error';
	                $message = lang('backup_restore_error');
	            } else {
	            	unlink($backup);
	            	$this->session->set_flashdata( 'ntf1', lang('upload_success'));
	            	$this->output->delete_cache();
	            	redirect('settings');
	            }
            }
    	}
    }

	function version_details() {
		$settings = $this->Settings_Model->get_version_detail();
		echo json_encode($settings);
	}

	function version_detail() {
		$version_notifications_array = ausGetAllVersions();
		$settings = $this->Settings_Model->get_version_detail();
		if ($version_notifications_array['notification_case'] == "notification_operation_ok") {
			$version = $version_notifications_array['notification_data']['product_versions'];
			$list_array = array();
			$list_array_log = array();
			$flag = false;
			foreach (array_reverse($version) as $key => $value) {
				if ($flag) {
					if ($settings['versions_name'] < $value['version_number'] && $value['version_status'] == 1) {
						$value['version_number'];
						$list_array[] = array('version_number' => $value['version_number']);
					}
					break;
				} else {

					if ($settings['versions_name'] < $value['version_number'] && $value['version_status'] == 1) {
						$value['version_number'];
						$list_array[] = array('version_number' => $value['version_number']);
						$flag = true;
					}

					if ($settings['versions_name'] == $value['version_number']) {
						$flag = true;
					}
				}
			}
			$msg = "";
			$updated = "available";
			if (empty($list_array)) {
				$list_array[0] = array('version_number' => $settings['versions_name']);
				$msg = 'Already updated';
				$updated ="";
			}
		} else {
			$msg = 'alreadyupdated';
			$updated = "";
			$list_array[0] = array('version_number' => $settings['versions_name']);
			$list_array_log[0] = '';
		}
		$download_version = ausGetVersion($list_array[0]['version_number']);
		$version_changelog = $download_version['notification_data'];
		if (!$version_changelog) {
			$version_changelog = NULL;
		} else {
			$version_changelog = $download_version['notification_data']['version_changelog'];
		}
		$version_details  =	array(
			'settings' => $settings,
			'version' => $list_array[0],
			'msg' => $msg,
			'updated' => $updated,
			'version_changelog' => $version_changelog
		);
		echo json_encode($version_details);
	}

	function download_update() {
		$data[ 'title' ] = lang( 'settings' );
		if ($this->session->userdata('admin')) {
			ini_set('max_execution_time', 0);
			ini_set('memory_limit','2048M');
			$download_notifications_array=ausDownloadFile('version_upgrade_file', $_POST['version_number']);
			if ($download_notifications_array['notification_case']=="notification_operation_ok") //'notification_operation_ok' case returned - operation succeeded
        	{
        		$download_notifications_array1=ausDownloadFile('version_upgrade_query', $_POST['version_number']);
	        	if ($download_notifications_array1['notification_case']=="notification_operation_ok") 
	        	{	
	        		$version = $this->Settings_Model->get_version_detail();
	       			$this->db->where( 'id', '1' );
					$this->db->update('versions',array('versions_name'=>$_POST['version_number'],'last_version'=>$version['versions_name'],'last_updated'=>date('Y-m-d')));
		       		$this->session->set_flashdata( 'ntf1', lang('softwareversionupdate'));
		       		$this->output->delete_cache();
		       		redirect( 'settings' );
		       	}
	    	   	else 
        		{
            		$this->session->set_flashdata( 'ntf4', lang('programfiles'));
       				redirect( 'settings' );
        		}
	        }
    		else //Other case returned - operation failed
        	{
       			$this->session->set_flashdata( 'ntf4', lang('programfiles'));
       			redirect( 'settings' );
        	}
		}else{
			redirect( 'panel' );
		}
	}

	function sendTestEmail() {
		$emailInput = $this->input->post('email');
		if (!empty($emailInput) || $emailInput != '') {
			$email = $this->security->xss_clean( $emailInput );
			$this->load->library('mail');
			$body = '<p>This is test SMTP email.</p> <p>If you are receiving this email that means your smtp setting are correct</p>';
			$data = $this->mail->send_email($email, $email, 'SMTP setup testing', $body);
			$param = array(
				'from_name' => $email,
				'email' => $email,
				'subject' => 'SMTP setup testing',
				'message' => $body,
				'created' => date( "Y.m.d H:i:s" ),
				'status' => 0
			);
			if ($data['success'] == true) {
				$return['success'] = true;
				$return['message'] = lang( 'mail_successfully_sent' );
				if ($email) {
					$this->db->insert( 'email_queue', $param );
				}
				echo json_encode($return);
			} else {
				$return['success'] = false;
				$return['message'] = lang( 'wrong_email_settings_msg' );
				echo json_encode($return);
			}
		} else {
			$return['success'] = false;
			$return['message'] = lang('invalidmessage'). ' ' .lang('email');
			echo json_encode($return);
		}
	}

	function email_test() {
		$setconfig = $this->Settings_Model->get_settings_ciuis();
		$subject = 'Test';
		$to = $setconfig[ 'sendermail' ];
		$data = 'test';
		$body = 'Your email working good.';
		$result = send_email( $subject, $to, $data, $body );
		if ( $result ) {
			echo lang( 'mail_successfully_sent' );
		} else {
			echo lang( 'mail_not_sent' );
		}
	}

	function save_config() {
		if (isset($_FILES['applogo']) && $_FILES['applogo']['name'] != '') {
			$config[ 'upload_path' ] = './uploads/ciuis_settings/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff';
			$new_name = preg_replace("/[^a-z0-9\_\-\'\"\.]/i", '', basename($_FILES["applogo"]['name']));
			$config['file_name'] = $new_name;
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'applogo' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'app_logo' => $image_data[ 'file_name' ] ) );
			$this->db->where('name', 'app_logo')->update('branding', array('value' => $image_data['file_name']));
		}
		if (isset($_FILES['navlogo']) && $_FILES['navlogo']['name'] != '') {
			$config[ 'upload_path' ] = './uploads/ciuis_settings/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff';
			$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["navlogo"]['name']));
			$config['file_name'] = $new_name;
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'navlogo' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'logo' => $image_data[ 'file_name' ] ) );
			$this->db->where('name', 'nav_logo')->update('branding', array('value' => $image_data['file_name']));
		}
		if (isset($_FILES['favicon']) && $_FILES['favicon']['name'] != '') {
			$config[ 'upload_path' ] = './assets/img/images/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
			$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["favicon"]['name']));
			$config['file_name'] = $new_name;
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'favicon' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$this->db->where(array('name' => 'favicon_icon'));
			$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
		}
		if (isset($_FILES['admin_login_image']) && $_FILES['admin_login_image']['name'] != '') {
			$config[ 'upload_path' ] = './assets/img/images/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
			$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["admin_login_image"]['name']));
			$config['file_name'] = $new_name;
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'admin_login_image');
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$this->db->where(array('name' => 'admin_login_image'));
			$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
		}
		if (isset($_FILES['client_login_image']) && $_FILES['client_login_image']['name'] != '') {
			$config[ 'upload_path' ] = './assets/img/images/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff|ico';
			$new_name = preg_replace("/[^a-z0-9\_\-\.]/i", '', basename($_FILES["client_login_image"]['name']));
			$config['file_name'] = $new_name;
			$this->load->library( 'upload', $config);
			$this->upload->do_upload( 'client_login_image');
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$this->db->where(array('name' => 'client_login_image'));
			$response = $this->db->update( 'branding', array('value' => $image_data[ 'file_name' ] ) );
		}
		$support = '0';
		if ($this->input->post('support_button') == 'true') {
			$support = '1';
		}
		$this->db->where('name', 'meta_keywords')->update('branding', array('value' => $this->input->post('meta_keywords')));
		$this->db->where('name', 'meta_description')->update('branding', array('value' => $this->input->post('meta_description')));
		$this->db->where('name', 'title')->update('branding', array('value' => $this->input->post('title')));
		$this->db->where('name', 'admin_login_text')->update('branding', array('value' => $this->input->post('admin_login_text')));
		$this->db->where('name', 'client_login_text')->update('branding', array('value' => $this->input->post('client_login_text')));
		$this->db->where('name', 'enable_support_button_on_client')->update('branding', array('value' => $support));
		$this->db->where('name', 'support_button_title')->update('branding', array('value' => $this->input->post('support_button_title')));
		$this->db->where('name', 'support_button_link')->update('branding', array('value' => $this->input->post('support_button_link')));
		redirect('settings');
	}

	function change_logo() {
		if ( isset( $_POST ) ) {
			$config[ 'upload_path' ] = './uploads/ciuis_settings/';
			$config[ 'allowed_types' ] = 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'file_name' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'logo' => $image_data[ 'file_name' ] ) );
			redirect( 'settings' );
		}
	}

	function change_app_logo() {
		if ( isset( $_POST ) ) {
			$config[ 'upload_path' ] = './uploads/ciuis_settings/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'file_name' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'app_logo' => $image_data[ 'file_name' ] ) );
			$this->session->set_flashdata( 'ntf1', lang('logo'). ' '.lang('updatemessage'));
			redirect( 'settings' );
		}
	}

	function change_nav_logo() {
		if ( isset( $_POST ) ) {
			$config[ 'upload_path' ] = './uploads/ciuis_settings/';
			$config[ 'allowed_types' ] = 'jpg|png|jpeg|gif|tiff';
			$this->load->library( 'upload', $config );
			$this->upload->do_upload( 'navLogo' );
			$data_upload_files = $this->upload->data();
			$image_data = $this->upload->data();
			$response = $this->db->update( 'settings', array( 'settingname' => 'ciuis', 'logo' => $image_data[ 'file_name' ] ) );
			$this->session->set_flashdata( 'ntf1', lang('logo'). ' '.lang('updatemessage'));
			redirect( 'settings' );
		}
	}

	function addlanguage() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'langcode' => $_POST[ 'langcode' ],
				'name' => $_POST[ 'name' ],
				'foldername' => $_POST[ 'foldername' ],
			);
			$this->db->insert( 'languages', $params );
			$data[ 'insert_id' ] = $this->db->insert_id();;
			return json_encode( $data );
		}
	}

	function create_custom_field() {
		if ( isset( $_POST ) && count( $_POST ) > 0 ) {
			$params = array(
				'name' => $this->input->post( 'name' ),
				'type' => $this->input->post( 'type' ),
				'order' => $this->input->post( 'order' ),
				'data' => $this->input->post( 'data' ),
				'relation' => $this->input->post( 'relation' ),
				'icon' => $this->input->post( 'icon' ),
				'permission' => $this->input->post( 'permission' ),
			);
			$response = $this->Fields_Model->create_new_field( $params );
			if ( $response ) {
				echo lang('custom_field_created');
			} else {
				echo lang('custom_field_not_created');
			}
		}
	}

	function update_custom_field( $id ) {
		if ( isset( $id ) ) {
			if ( isset( $_POST ) && count( $_POST ) > 0 ) {
				$params = array(
					'name' => $this->input->post( 'name' ),
					'type' => $this->input->post( 'type' ),
					'order' => $this->input->post( 'order' ),
					'data' => $this->input->post( 'data' ),
					'relation' => $this->input->post( 'relation' ),
					'icon' => $this->input->post( 'icon' ),
					'permission' => $this->input->post( 'permission' ),
				);
				$response = $this->Fields_Model->update_custom_field( $id, $params );
				if ( $response ) {
					echo lang('custom_field_updated');
				} else {
					echo lang('custom_field_not_updated');
				}
			}
		} else {
			echo 'Custom field is not updated';
		}
	}

	function update_custom_field_status( $id, $value ) {
		if ( isset( $id ) ) {
			$this->db->where( 'id', $id );
			$response = $this->db->update( 'custom_fields', array( 'active' => $value ) );
		} else {
			echo 'Custom field status is not updated';
		}
	}

	function remove_custom_field( $id ) {
		if ( isset( $id ) ) {
			$response = $this->db->delete( 'custom_fields', array( 'id' => $id ) );
			if ( $response ) {
				echo lang('custom_field_removed');
			} else {
				echo lang('custom_field_not_removed');
			}
		} else {
			echo 'Custom field is not removed';
		}
	}

	function get_payment_gateways() {
		$payments = $this->Settings_Model->get_payment_modes();
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
		echo json_encode($data);
	}

	function update_payment_gateway($payment) {
		if (isset($payment) && isAdmin()) {
			if (isset($_POST) && count($_POST) > 0 ) {
				$data = $this->input->post('payment', TRUE);
				$type = $payment;
				$hasError = false;
				if (($type == 'ccavenue') && ($data['ccavenue_active'] == 'true')) {
					if ($data['ccavenue_marchent_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('ccavenue_marchent_key');
					} else if ($data['ccavenue_working_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('ccavenue_working_key');
					} else if ($data['ccavenue_working_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang('ccavenue');
					}
				}
				if (($type == 'payumoney') && ($data['payu_money_active'] == 'true')) {
					if ($data['payu_money_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payu_money_key');
					} else if ($data['payu_money_salt'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payu_money_salt');
					} else if ($data['payu_money_record_account'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang('payumoney');
					}
				}
				if (($type == 'stripe') && ($data['stripe_active'] == 'true')) {
					if ($data['stripe_api_secret_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('stripe_api_secret_key');
					} else if ($data['stripe_api_publishable_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('stripe_api_publishable_key');
					} else if ($data['stripe_record_account'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang('stripe');
					}
				}
				if (($type == 'authorize_aim') && ($data['authorize_aim_active'] == 'true')) {
					if ($data['authorize_aim_api_login_id'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('authorize_aim_api_login_id');
					} else if ($data['authorize_aim_api_transaction_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('authorize_aim_api_transaction_key');
					} else if ($data['authorize_aim_payment_record_account'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang('authorize_aim');
					}
				}
				if (($type == 'razorpay') && ($data['razorpay_active'] == 'true')) {
					if ($data['razorpay_key'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('razorpay_key');
					} else if ($data['razorpay_key_secret'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('razorpay_key_secret');
					} else if ($data['razorpay_payment_record_account'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang('razorpay');
					}
				}
				if (($type == 'paypal') && ($data['paypal_active'] == 'true')) {
					if ($data['paypal_username'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('paypal_username');
					} else if ($data['paypal_currency'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('paypalcurrency');
					} else if ($data['paypal_payment_record_account'] == '') {
						$hasError = true;
						$return['message'] = lang('required_message').' '.lang('payment_account').' '.lang('for').' '.lang('paypal');
					}
				}
				if ($hasError) {
					$return['success'] = false;
					echo json_encode($return);
				}
				if (!$hasError) {
					foreach ($data as $key=>$value) {
						if ($value == 'true') {
							$value = '1';
						} else if ($value == 'false') {
							$value = '0';
						}
						$data = array('value' => $value);
						$this->db->where('name', $key)->update('payment_modes', $data);
					}
					$return['success'] = true;
					$return['message'] = lang('payment_gateway').' '.lang('updatemessage');
					echo json_encode($return);
				}
			} else {
				$return['message'] = lang('errormessage');
				$return['error'] = isAdmin();
				echo json_encode($return);
			}
		} else {
			$return['message'] = lang('errormessage');
			$return['error'] = isAdmin();
			echo json_encode($return);
		}
	}

	function execute_mysql_query() {
		if (isset($_POST) && count($_POST) > 0 ) {
			$mysql_query = $this->input->post('mysql_query');
			$hasError = false;
			$data['message'] = '';
			if ($mysql_query == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' '. lang('query');
			}
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				if ($this->db->query($mysql_query)) {
					$data['success'] = true;
					$data['message'] = lang('query_success');
					echo json_encode($data);
				} else {
					$data['success'] = false;
					$data['message'] = lang('query_error');
					echo json_encode($data);
				}
			}
		}
	}

	function get_smtp_password() {
		if (isset($_POST) && count($_POST) > 0 ) {
			$password = $this->input->post('password');
			$hasError = false;
			$data['message'] = '';
			if ($password == '') {
				$hasError = true;
				$data['message'] = lang('invalidmessage'). ' '. lang('password');
			} 
			if ($hasError) {
				$data['success'] = false;
				echo json_encode($data);
			}
			if (!$hasError) {
				$this->db->from( 'staff' );
				$this->db->where( 'email', $this->session->userdata('email') );
				$this->db->where( 'password', md5( $password ) );
				$login = $this->db->get()->result();
				if ( is_array( $login ) && count( $login ) == 1 ) {
					$settings = $this->Settings_Model->get_settings_ciuis_origin();
					$data['password'] = $settings['smtppassoword'];
					$data['success'] = true;
					echo json_encode($data);
				} else {
					$data['message'] = lang('incorrect_password');
					$data['success'] = false;
					echo json_encode($data);
				}
			}
		}
	}
}