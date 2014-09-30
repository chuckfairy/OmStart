<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set");return false;}

$json_data = json_decode($_POST["json_data"]);

$dir_path = $json_data->dir_path;
$dir_url = SRC.$dir_path;
$new_file = $dir_url.DS.$json_data->file_name;
if(is_file($new_file)) {$session->set_message("Dir name taken"); return false;}

$php_oct = get_php_oct($dir_url);

if($php_oct === 6 || $php_oct === 7) {
	touch($new_file);
	chmod($new_file, 0755);
	if(is_file($new_file)) {
		$session->set_message("File ".$json_data->file_name." created.");
		return true;
	}
} else {
	$session->set_message("PHP cannot write to this directory");
	return false;
}