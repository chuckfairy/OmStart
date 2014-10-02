<?php require_once("../../initialize.php");

if(!$session->is_logged_in()) {
	redirect_to(SITE_ROOT);
}

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	if(empty($json_data->message)) {return false;}
	$session->set_message(strval($json_data->message));
}

?>