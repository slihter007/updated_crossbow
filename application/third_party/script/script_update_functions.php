<?php
function ausCustomPostGetHeaders($url, $refer, $post_info)
    {
    $USER_AGENT="Mozilla/5.0 (Windows NT 6.3; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0"; //set user agent
    $CONNECT_TIMEOUT=120;

    $server_response_array=array();
    $formatted_headers_array=array();

    if (filter_var($url, FILTER_VALIDATE_URL) && !empty($post_info))
        {
        if (empty($refer) || !filter_var($refer, FILTER_VALIDATE_URL)) //use original URL as refer when no valid refer URL provided
            {
            $refer=$url;
            }
        }
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $USER_AGENT);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $CONNECT_TIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, $CONNECT_TIMEOUT);
    curl_setopt($ch, CURLOPT_REFERER, $refer);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_info);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

    curl_setopt($ch, CURLOPT_HEADERFUNCTION,
        function($curl, $header) use (&$formatted_headers_array)
            {
            $len=strlen($header);
            $header=explode(":", $header, 2);
            if (count($header)<2) //ignore invalid headers
            return $len;

            $name=strtolower(trim($header[0]));
            $formatted_headers_array[$name]=trim($header[1]);

            return $len;
            }
        );

    $result=curl_exec($ch);
    curl_close($ch);

    $server_response_array['headers']=$formatted_headers_array;
    $server_response_array['body']=$result;

    return $server_response_array;
    }


function ausGetRawDomain($url)
    {
    $raw_domain=null;

    if (!empty($url))
        {
        $url_array=parse_url($url);
        if (empty($url_array['scheme']))
            {
            $url="http://".$url;
            $url_array=parse_url($url);
            }

        if (!empty($url_array['host']))
            {
            $raw_domain=$url_array['host'];

            $raw_domain=trim(str_ireplace("www.", "", filter_var($raw_domain, FILTER_SANITIZE_URL)));
            }
        }

    return $raw_domain;
    }


function ausGenerateScriptSignature()
    {
    $script_signature=null;
    $root_ips_array=gethostbynamel(ausGetRawDomain(AUS_ROOT_URL));

    if (!empty($root_ips_array)) 
        {
        $script_signature=hash("sha256", gmdate("Y-m-d").AUS_PRODUCT_ID.AUS_PRODUCT_KEY.implode("", $root_ips_array));
        }

    return $script_signature;
    }


function ausVerifyServerSignature($notification_server_signature)
    {
    $result=false;
    $root_ips_array=gethostbynamel(ausGetRawDomain(AUS_ROOT_URL));

    if (!empty($notification_server_signature) && !empty($root_ips_array) && hash("sha256", implode("", $root_ips_array).AUS_PRODUCT_KEY.AUS_PRODUCT_ID.gmdate("Y-m-d"))==$notification_server_signature) //server signature valid
        {
        $result=true;
        }

    return $result;
    }


function ausCheckSettings()
    {
    $notifications_array=array();

    if (!filter_var(AUS_ROOT_URL, FILTER_VALIDATE_URL) || !ctype_alnum(substr(AUS_ROOT_URL, -1))) //invalid AUS installation URL
        {
        $notifications_array[]=AUS_CORE_NOTIFICATION_INVALID_ROOT_URL;
        }

    if (!filter_var(AUS_PRODUCT_ID, FILTER_VALIDATE_INT)) 
        {
        $notifications_array[]=AUS_CORE_NOTIFICATION_INVALID_PRODUCT_ID;
        }

    if (empty(AUS_PRODUCT_KEY) || AUS_PRODUCT_KEY=="some_random_key") 
        {
        $notifications_array[]=AUS_CORE_NOTIFICATION_INVALID_PRODUCT_KEY;
        }

    return $notifications_array;
    }


function ausGetVersion($version_number=null)
    {
    $notifications_array=array();
    $aus_core_notifications=ausCheckSettings();

    if (empty($aus_core_notifications))
        {
        $post_info="product_id=".rawurlencode(AUS_PRODUCT_ID)."&product_key=".rawurlencode(AUS_PRODUCT_KEY)."&version_number=".rawurlencode($version_number)."&user_local_path=".rawurlencode(dirname(AUS_DIRECTORY))."&script_signature=".rawurlencode(ausGenerateScriptSignature());

        $content_array=ausCustomPostGetHeaders(AUS_ROOT_URL."/aus_callbacks/get_version.php", null, $post_info);
        if (!empty($content_array['headers']['notification_server_signature']) && ausVerifyServerSignature($content_array['headers']['notification_server_signature']))
            {
            $notifications_array['notification_case']=$content_array['headers']['notification_case'];
            $notifications_array['notification_text']=$content_array['headers']['notification_text'];
            if (!empty($content_array['headers']['notification_data'])) 
                {
                $notifications_array['notification_data']=json_decode($content_array['headers']['notification_data'], true);
                }
            }
        else 
            {
            $notifications_array['notification_case']="notification_no_connection";
            $notifications_array['notification_text']=AUS_NOTIFICATION_NO_CONNECTION;
            }
        }
    else 
        {
        $notifications_array['notification_case']="notification_script_corrupted";
        $notifications_array['notification_text']=implode("; ", $aus_core_notifications);
        }

    return $notifications_array;
    }


function ausGetAllVersions()
    {
    $notifications_array=array();
    $aus_core_notifications=ausCheckSettings(); 

    if (empty($aus_core_notifications))
        {
        $post_info="product_id=".rawurlencode(AUS_PRODUCT_ID)."&product_key=".rawurlencode(AUS_PRODUCT_KEY)."&user_local_path=".rawurlencode(dirname(AUS_DIRECTORY))."&script_signature=".rawurlencode(ausGenerateScriptSignature());

        $content_array=ausCustomPostGetHeaders(AUS_ROOT_URL."/aus_callbacks/get_all_versions.php", null, $post_info);
        if (!empty($content_array['headers']['notification_server_signature']) && ausVerifyServerSignature($content_array['headers']['notification_server_signature'])) 
            {
            $notifications_array['notification_case']=$content_array['headers']['notification_case'];
            $notifications_array['notification_text']=$content_array['headers']['notification_text'];
            if (!empty($content_array['headers']['notification_data'])) //additional data returned
                {
                $notifications_array['notification_data']=json_decode($content_array['headers']['notification_data'], true);
                }
            }
        else 
            {
            $notifications_array['notification_case']="notification_no_connection";
            $notifications_array['notification_text']=AUS_NOTIFICATION_NO_CONNECTION;
            }
        }
    else 
        {
        $notifications_array['notification_case']="notification_script_corrupted";
        $notifications_array['notification_text']=implode("; ", $aus_core_notifications);
        }

    return $notifications_array;
    }

function ausDownloadFile($file_type="version_upgrade_file", $version_number=null)
    {
    $notifications_array=array();
    $aus_core_notifications=ausCheckSettings(); 

    if (empty($aus_core_notifications)) 
        {
        if (class_exists("ZipArchive"))
            {
            $post_info="product_id=".rawurlencode(AUS_PRODUCT_ID)."&product_key=".rawurlencode(AUS_PRODUCT_KEY)."&version_number=".rawurlencode($version_number)."&user_local_path=".rawurlencode(dirname(AUS_DIRECTORY))."&file_type=".rawurlencode($file_type)."&script_signature=".rawurlencode(ausGenerateScriptSignature());

            $content_array=ausCustomPostGetHeaders(AUS_ROOT_URL."/aus_callbacks/download_file.php", null, $post_info);
            if (!empty($content_array['headers']['notification_server_signature']) && ausVerifyServerSignature($content_array['headers']['notification_server_signature']))
                {
                $notifications_array['notification_case']=$content_array['headers']['notification_case'];
                $notifications_array['notification_text']=$content_array['headers']['notification_text'];
                if (!empty($content_array['headers']['notification_data'])) //additional data returned
                    {
                    $notifications_array['notification_data']=json_decode($content_array['headers']['notification_data'], true);
                    }

                if (!empty($content_array['body'])) //file downloaded
                    {
                    if (!empty($content_array['headers']['content-disposition'])) //get name of ZIP archive
                        {
                        $zip_file_name=str_ireplace("filename=", "", stristr($content_array['headers']['content-disposition'], "filename="));;
                        }

                    if (empty($zip_file_name)) //name of ZIP archive could not be parsed, use some hardcoded name
                        {
                        $zip_file_name="$file_type.zip"; //$file_type is string like version_install_file
                        }

                    $script_root_directory=dirname(AUS_DIRECTORY); 
                    $zip_archive_local_destination="$script_root_directory/$zip_file_name"; //download archive right to root directory

                    $zip_file=@fopen($zip_archive_local_destination, "w+");
                    $fwrite=@fwrite($zip_file, $content_array['body']);
                    if (filter_var($fwrite, FILTER_VALIDATE_INT)) //zip archive saved, extract it
                        {
                        $zip_file=new ZipArchive;
                        // Update will happen only from /SQLupdate/update.sql file and no other file.
                        if ($zip_file->open("$script_root_directory/$zip_file_name")===true) //everything ok, extract zip archive
                            {
                                $newfile = $zip_file->extractTo($script_root_directory);
                                for($i = 0; $i < $zip_file->numFiles; $i++) 
                                {   
                                    $checkimage = glob($zip_file->getNameIndex($i)."*.{sql}", GLOB_BRACE);
                                    if(!empty($checkimage) && strcmp($checkimage[0], "SQLupdate/update.sql") == 0){

                                        foreach ($checkimage as $sql) {
                                            $ci =& get_instance();
                                            $sql = file_get_contents($sql);

                                            $sqls = explode(';', $sql);
                                            array_pop($sqls);

                                            foreach($sqls as $statement){
                                                $statment = $statement . ";";
                                                $ci->db->query($statement);
                                            }
                                        }
                                        
                                        rename($checkimage[0], $zip_file->getNameIndex($i) . "update" . ".sql." . date("Y-m-d_H-i-s")); // Rename the /SQLupdate/update.sql file to /SQLupdate/update.sql.dt_timestamp
                                     
                                    }
                                }
                         
                            $zip_file->close();

                            if (AUS_DELETE_EXTRACTED=="YES") //delete zip archive after extracting
                                {
                                $removed_files_total=ausDeleteFileDirectory($script_root_directory, array($zip_file_name));
                                if (!filter_var($removed_files_total))
                                    {
                                    $notifications_array['notification_case']="notification_zip_delete_failed";
                                    $notifications_array['notification_text']=AUS_NOTIFICATION_ZIP_DELETE_ERROR;
                                    }
                                }
                            }
                        else //zip archive can't be opened
                            {
                            $notifications_array['notification_case']="notification_zip_extract_failed";
                            $notifications_array['notification_text']=AUS_NOTIFICATION_ZIP_EXTRACT_ERROR;
                            }
                        }
                    else //saving zip archive failed
                        {
                        $notifications_array['notification_case']="notification_zip_extract_failed";
                        $notifications_array['notification_text']=AUS_NOTIFICATION_ZIP_EXTRACT_ERROR;
                        }
                    }
                }
            else //no proper response received
                {
                $notifications_array['notification_case']="notification_no_connection";
                $notifications_array['notification_text']=AUS_NOTIFICATION_NO_CONNECTION;
                }
            }
        else
            {
            $notifications_array['notification_case']="notification_ziparchive_class_missing";
            $notifications_array['notification_text']=AUS_NOTIFICATION_ZIPARCHIVE_CLASS_MISSING;
            }
        }
    else //script is not properly configured
        {
        $notifications_array['notification_case']="notification_script_corrupted";
        $notifications_array['notification_text']=implode("; ", $aus_core_notifications);
        }
    return $notifications_array;
    }


//delete files and directories from specified directory ($files_array is an array of files and/or sub-directories to be deleted from $root_directory)
function ausDeleteFileDirectory($root_directory, $files_array)
    {
    $removed_records=0;
    if (!empty($files_array))
        {
        $files_array=array_filter($files_array); //remove empty files (if any) from $files_array to prevent parent directory from being deleted too
        $files_array=array_diff($files_array, array(".", "..", "")); //remove dot files (if any) from $files_array to prevent parent directory from being deleted too when $files_array contains "."
        $files_array=array_values($files_array); //re-index array to prevent errors of undefined array indices
        }

    if (!empty($root_directory) && is_dir($root_directory) && !empty($files_array)) //proceed deleting files/directories
        {
        foreach ($files_array as $file)
            {
            if (is_file("$root_directory/$file") && unlink("$root_directory/$file")) //this is a file, delete
                {
                $removed_records++;
                }

            if (is_dir("$root_directory/$file")) //this is a directory, enter it and delete all files inside first
                {
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$root_directory/$file", FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path)
                    {
                    $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
                    }

                if (rmdir("$root_directory/$file"))
                    {
                    $removed_records++;
                    }
                }
            }
        }
    return $removed_records;
    }
