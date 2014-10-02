<?php

function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$path = "./{$class_name}.php";
	if(file_exists($path)) {
		require_once($path);
	} else {
		die("The file {$class_name}.php could not be found");
	}
}

function redirect_to($location = NULL) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}

function timestamp() {return date("m/d/Y H:i:s");}

function escape($string) {
	global $database;
	$safe_string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	//$safe_string = mysqli_real_escape_string($database::$link, $string);
	return $safe_string;
}

function validate_email($email_address) {
	if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {return true;} 
	else {
		global $session;
		$session->set_message("Email is not valid");
		return false;
	}
}

function assoc_array($mysql_result, $field="id") {
	global $database;
	$assoc_array = array();
	while($mysql_data = $database->assoc($mysql_result)) {
		array_push($assoc_array, $mysql_data[$field]);
	}
	return $assoc_array;
}

/***********************File Functions****************/

function is_picture($file) {
	$file_extensions = array(
		"gif",
		"png",
		"jpg",
		"tiff"
	);
	if(preg_match("/./", $file)) {
		$file = preg_split('/\./', $file);
		$extension = array_pop($file);
	}
	foreach($file_extensions as $valid_extension) {
		if($extension == $valid_extension) {
			return true;
		}
	}
	return false;
}

function is_extension($extension, $file) {
	if(preg_match("/./", $file)) {
		$file = preg_split('/\./', $file);
		$file_extension = array_pop($file);
		if($extension == $file_extension) {return true;}
	}
	return false;
}

function get_extension($file) {
	$file = preg_split('/\./', $file);
	$extension = array_pop($file);
	if(!$extension) {
		return false;
	} else {
		return $extension;
	}
}

function clean_file($file_name) {
	$unacceptable = array(
		"+","/","%","#", '"',
		":","?","@","&"," ", "'"
	);
	$safe_file_name = str_replace($unacceptable, "_", $file_name);
	return $safe_file_name;
}

function clean_query($file_name) {
	$unacceptable = array(
		"+","/","%","#", '"',
		":","?","@","&", "'"
	);
	$safe_file_name = str_replace($unacceptable, "_", $file_name);
	return $safe_file_name;
}

function get_lines($url) {
	$fh = fopen($url, "r");
	if($fh) {
		$lines = array();
		$i = 0;
		while(!feof($fh)){
			$content = fgets($fh);
			$lines[$i] = $content;$i++;
		}
		fclose($fh);
		return $lines;
	}
}

function get_file_owner($file_name) {
	$file_owner = posix_getpwuid(fileowner($file_name));
	return isset($file_owner["name"])? $file_owner["name"] : false;
}

function get_file_group($file_name) {
	$file_group = posix_getgrgid(filegroup($file_name));
	return isset($file_group["name"])? $file_group["name"] : false;
}

function get_file_oct($file_name) {
	return decoct(fileperms($file_name) & 0777);
}

function get_php_oct($file_name) {
	//Check if it 
	if(!is_file($file_name) && !is_dir($file_name)) {
		return false;
	}
	
	$foct   = get_file_oct($file_name);
	$fowner = get_file_owner($file_name);
	$fgroup = get_file_group($file_name);
	$filepermissions = "";		
	
	if($fowner === "www"||
	   $fowner === "_www" ||
	   $fowner === "www-data"
	) {$filepermissions = substr($foct, 0,1);}
	//check group
	elseif($fgroup === "www"||
		   $fgroup === "_www" ||
		   $fgroup === "www-data"
	) {
		empty($filepermissions)? $filepermissions = substr($foct, 1,1):null;	
	}
	else {$filepermissions = substr($foct,-1);}
	
	return (int) $filepermissions;
}

/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = true) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	//Bad files
	else{return false;}
}


/***********************CMS Functions****************/

function table_output() {
	global $database;
	foreach($database->get_tables() as $table) {
		echo "<a href='#'>{$table}</a>";
	}
	return true;
}


/***********************Password Functions****************/

function password_encrypt($password) {
  	$hash_format = "$2y$10$";   // Blowfish with a "cost" of 10
	$salt = generate_salt();
	$format_and_salt = $hash_format.$salt;
	$hash = crypt($password, $format_and_salt);
	return $hash;
}

function generate_salt() {
	// MD5 returns 32 characters
	$unique_random_string = sha1(uniqid(mt_rand(), true));		
	$base64_string = base64_encode($unique_random_string);
	$modified_base64_string = str_replace('+', '.', $base64_string);
	// Truncate string to the correct length
	$salt = substr($modified_base64_string, 0, 30);
	return $salt;
}

function password_check($password, $existing_hash) {
	// existing hash contains format and salt at start
	$hash = crypt($password, $existing_hash);
	if ($hash === $existing_hash) {return true;} 
	else {return false;}
}		


/***********************Blog Functions****************/

function curl_get_messages() {

	$ch = curl_init();	
	
	//curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch,  CURLOPT_URL, CONTROLLER."messenger".DS."messages.php");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	
	$content = curl_exec($ch);
	$err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
	curl_close($ch);	
	
	return $content;
}

/***********************PDO Setup****************/

function get_available_drivers() {
	$drivers = array();
	foreach(PDO::getAvailableDrivers() as $driver){array_push($drivers, $driver);}
	return $drivers;
}

/***********************Page Functionality Actions****************/

function action_choices() {
	$actions = new TableObject("actions");
	$action_codes = $actions::find_all();
	foreach($action_codes as $action) {
		echo "<a href=javascript:void(0)>{$action['type']}</a>";
	}
}

function petition_flag_choices() {
	$flags = new TableObject("flags_petition");
	$flags->clear_filters();
	$flags = $flags::find_all();
	foreach($flags as $flag) {
		echo "<a href=javascript:void(0)>{$flag['type']}</a>";
	}
}


/***********************File functions****************/

function imageCreateFromAny($filepath='') { 
    // [] if you don't have exif you could use getImageSize() 
    $type = exif_imagetype($filepath); 
    $allowedTypes = array( 
        1,  // [] gif 
        2,  // [] jpg 
        3,  // [] png 
        6   // [] bmp 
    ); 
    if (!in_array($type, $allowedTypes)) { 
        return false; 
    } 
    switch ($type) { 
        case 1 : 
            $im = imageCreateFromGif($filepath); 
        break; 
        case 2 : 
            $im = imageCreateFromJpeg($filepath); 
        break; 
        case 3 : 
            $im = imageCreateFromPng($filepath); 
        break; 
        case 6 : 
            $im = imageCreateFromBmp($filepath); 
        break; 
    }    
    return $im;  
}
