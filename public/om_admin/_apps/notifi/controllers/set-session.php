<?php require_once("../../../../../omstart/initialize.php");

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	if(empty($json_data->message)) {return false;}
	$session->set_message(strval($json_data->message));
}

?>