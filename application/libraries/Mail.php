<?php

class Mail {

    public function __construct() {
        $this->CI = &get_instance();
        $this->CI->load->library('email');
    }

    public function send_email($to, $from_name, $subject, $message, $attachment_path = '') {
        $settings = $this->CI->Settings_Model->get_settings( 'ciuis' );
        $encryption = '';
        if ($settings['email_encryption'] == 1) {
            $config['smtp_crypto'] = 'ssl';
            //$encryption = 'ssl';
        } 
        if ($settings['email_encryption'] == 2) {
            $config['smtp_crypto'] = 'tls';
            //$encryption = 'tls';
        }
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = "html";
        $config['charset'] = $settings['emailcharset'];
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $config['smtp_timeout'] = '200';
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $settings['smtphost'];
        $config['smtp_port'] = $settings['smtpport'];
        $config['smtp_user'] = $settings['smtpusername'];
        $config['smtp_pass'] = $settings['smtppassoword'];
        
        $body = $message;
        $this->CI->email->initialize( $config );
        $this->CI->email->from($settings['sendermail'], $settings['sender_name']);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($body);
        $this->CI->email->attach($attachment_path);
        $send = $this->CI->email->send();
        if ($send) {
            $return = array(
                'success' => true,
                'message' => lang('email_sent_success')
            );
            return $return;
        } else {
            $return = array(
                'success' => false,
                'message' => lang('errormessage')
            );
            return $return;
        }
    }

}
