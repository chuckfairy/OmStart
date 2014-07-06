<?php

/*
function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$path = "./{$class_name}.php";
	if(file_exists($path)) {
		require_once($path);
	} else {
		die("The file {$class_name}.php could not be found");
	}
}
*/

function redirect_to($location = NULL) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}

function validate_presences($required_fields) {
	global $session;
	foreach($required_fields as $field) {
		$value = trim($_POST[$field]);
		if (empty($value)) {
			$session->set_error(field_as_text($field) . " can't be blank");
		}
	}
}

function escape($string) {
	global $database;
	$safe_string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	//$safe_string = mysqli_real_escape_string($database::$link, $string);
	return $safe_string;
}

function validate_email($email_address) {
	global $session;
	if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
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

function clean_file($file_name) {
	$unacceptable = array(
		"+","/","%","#", '"',
		":","?","@","&"," ", "'"
	);
	$safe_file_name = str_replace($unacceptable, "_", $file_name);
	return $safe_file_name;
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

function blog_file($file_content, $file_name) {
	$url = SITE_ROOT.$file_name;
	echo $url;
	file_exists($url)? unlink($url) : null;
	$fh = fopen($url, "w");
	chmod($url, 0755);
	fwrite($fh, htmlspecialchars($file_content));
	if(fclose($fh)) {return true;} else {return false;}
}

/***********************PDO Setup****************/

function get_available_drivers() {
	$drivers = array();
	foreach(PDO::getAvailableDrivers() as $driver){array_push($drivers, $driver);}
	return $drivers;
}
