<?php require_once("../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No Data Set"); return false;}

$json_data = json_decode($_POST["json_data"]);

//Attempt Login
$Admin->attempt_login($json_data->username, $json_data->password);
if($Admin->is_logged_in()) {
	echo "true";
}

?>
