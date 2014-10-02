<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set");return false;}

$json_data = json_decode($_POST["json_data"]);

$dirlocation = SRC.$json_data->dir_name;
$filelocation = $dirlocation.DS.$json_data->file_name;
$rename = $dirlocation.DS.$json_data->rename;

//Check if dir is writeable
if(!is_dir($dirlocation)) {
	$session->set_message("Directory not valid");
	return false;
}

$dirOct = get_php_oct($dirlocation);
if($dirOct !== 6 && $dirOct !== 7) {
	$session->set_message("Directory not writeable");
	return false;
}

//Check if data is file or directory
if(!is_dir($filelocation) && !is_file($filelocation)) {
	$session->set_message("Data given is not a directory and is not a file");
	return false;
}

//Check oct
$thisOct = get_php_oct($filelocation);

if($thisOct === 6 || $thisOct === 7) {
	//move file to trash
	rename($filelocation, $rename);
	if(is_file($rename) || is_dir($rename)) {
		$session->set_message("File Renamed Successfully");
		return true;
	}
} else {
	$session->set_message("PHP does not have permission to write file");
	return false;
}