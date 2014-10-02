<?php require_once("../../../../../omstart/initialize.php");

//Check Data
if(!isset($_POST["json_data"])) {$session->set_message("No data sent");return false;}
$json_data = json_decode($_POST["json_data"]);

//Set File Location
$filelocation = SRC.$json_data->filename;

//Check if isn't directory
if(!is_file($filelocation)) {
	$session->set_message("Directory is not found ".$filelocation);
	return false;
}

$php_oct = get_php_oct($filelocation);
if($php_oct === 6 ||
   $php_oct === 7
) {
	//Save file with new data
	file_put_contents($filelocation, $json_data->filedata);
} else {
	$session->set_message("PHP does not have writeable permissions");
}