<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set");return false;}

$json_data = json_decode($_POST["json_data"]);

$dir_path = $json_data->dir_path;
$dir_url = SRC.$dir_path;
$new_dir = $dir_url.DS.$json_data->dir_name;
if(is_dir($new_dir)) {$session->set_message("Dir name taken"); return false;}

$php_oct = get_php_oct($dir_url);

if($php_oct === 6 || $php_oct === 7) {
	mkdir($new_dir);
	chmod($new_dir, 0755);
	if(is_dir($new_dir)) {
		$session->set_message("Directory ".$json_data->dir_name." created.");
		return true;
	}
} else {
	$session->set_message("PHP cannot write to this directory");
	return false;
}