<?php
namespace App\Core;

class AppRequirements
{  
    private $extensions      = null;
    private $permissions     = null;  
    private $versions        = null;  
    private $error          = null;  
    public $version_error  = false;
    public $writable_error  = false;
    public $extension_error  = false;

    //check files permission, extensions and version
    public function check($files = []) {  
        foreach ($files as $file) {
            if (!file_exists($file) && !is_writable($file)) { 
                $this->permissions[$file] = '<i class="fa fa-times red"></i>';
                $this->error[] = true;
                $this->writable_error = true;
            } else { 
                $this->permissions[$file] = '<i class="fa fa-check green"></i>';
            }
        }       
        $this->checkExtension([
            'pdo',
            'mysqli',
            //'mcrypt', 
            // 'pdo_mysql',
             //'mcrypt',
            // 'fileinfo',
            // 'sqlite3',
            'openssl',
            'json',
            'session',
            // 'Core', 
             'curl',
             'mbstring',
             'zip',
             //'allow_url_fopen',
            // 'dom', 
             'gd', 
            // 'hash',
             'iconv',
            // 'pcre',    
            // 'simplexml' 
        ]); 

        $this->checkPhpVersion('5.6.40', '<');
        $this->checkMySqlVersion('4.1.20', '<');
        $this->checkSafeMode();
        return [ 
            'permissions' => $this->permissions,
            'extensions'  => $this->extensions,
            'versions'    => $this->versions,
            'errors'      => $this->error,
            'version_error'    => $this->version_error,
            'version_error_msg'    => 'PHP or MySQL version doesn\'t match with application requirements',
            'writable_error'    => $this->writable_error,
            'writable_error_msg'    => 'Please give write permissions to below listed files',
            'extension_error'    => $this->extension_error,
            'extension_error_msg'    => 'Please enable all extensions.',           
        ];
    }

    //check the version
    public function checkPhpVersion($required = null, $condition = null) {
        if (version_compare(phpversion(), $required, $condition)) {
            //unsuccess
            $this->versions['PHP'] =  "<i class='fa fa-times red'></i> You need <strong> PHP version $required </strong>";
            $this->error[] = true;
            $this->version_error = true;
        } else {
            //success
            $this->versions['PHP'] = "<i class='fa fa-ok green'></i> You have<strong class='green'> PHP ".phpversion()." </strong> (required <strong> $required</strong> or greater)";
        } 
    }

    //check mysql version
    public function checkMySqlVersion($required = null, $condition = null) {
        ob_start(); 
        phpinfo(INFO_MODULES); 
        $mysql = ob_get_contents(); 
        ob_end_clean(); 
        $mysql = stristr($mysql, 'Client API version'); 
        preg_match('/[1-9].[0-9].[1-9][0-9]/', $mysql, $search);    
        if (version_compare($search[0],  $required, $condition)) {
            $this->versions['MySQL'] =  "<i class='fa fa-times red'></i> You need <strong class='red'> MySql version $required </strong>";
            $this->error[] = true;
            $this->version_error = true;
        } else {    
            $this->versions['MySQL'] = "<i class='fa fa-ok green'></i> You have<strong class='green'> MySQL ".$search[0]." </strong> (required <strong> $required</strong> or greater)"; 
        }         
    }

    //check safe mode
    public function checkSafeMode() { 
        if (!ini_get('safe_mode')) {
            $this->versions['safe_mode'] = '<strong class="red">Disabled</i> '; 
        } else {   
            $this->versions['safe_mode'] = '<strong class="green">Enable</strong>'; 
        }      
    }

    //check extension
    public function checkExtension($extensions = null) { 
        foreach($extensions as $ext) {
            if (!extension_loaded($ext)) { 
                $this->extensions[$ext] = '<i class="fa fa-times red"></i>'; 
                $this->error[] = true;
                $this->extension_error = true;
            } else {   
                $this->extensions[$ext] = '<i class="fa fa-check green"></i>'; 
            }
        }
    }
}
 