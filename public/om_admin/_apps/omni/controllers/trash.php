<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set");return false;}

$json_data = json_decode($_POST["json_data"]);

$filelocation = SRC.$json_data->file_path;
$dir_array = explode("/", $filelocation);
$filename = array_pop($dir_array);

//Check if directory
if(!is_file($filelocation) && !is_dir($filelocation)) {
	$session->set_message($filelocation." Data given is not a file");
	return false;
}

if(is_dir($filelocation)) {
	$session->set_message("Directory deletion not supported");
	return false;
}

//Check oct
$thisOct = get_php_oct($filelocation);

if($thisOct === 6 || $thisOct === 7) {
	//move file to trash
	if(is_file($filelocation)) {
		rename($filelocation, TRASH.$filename);
		if(!is_file($filelocation)) {
			$session->set_message("File {$filename} trashed Successfully");
			return true;
		}
	}
} else {
	$session->set_message("PHP does not have permission to write file");
	return false;
}