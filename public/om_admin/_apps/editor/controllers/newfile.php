<?php require_once("../../../../../omstart/initialize.php");

!isset($_POST["json_data"]) {$session->set_message("No data sent");return false;}

$json_data = json_decode($_POST["json_data"]);

//Set File Location
$dirlocation = SRC.$json_data->dir_path;

//Check if isn't directory
if(!is_dir($dirlocation)) {
	$session->set_message("Directory is not found ".$dirlocation);
	return false;
}

