<?php require_once("../../../../../omstart/initialize.php");

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$Admin->create_admin($json_data->username, $json_data->password, $json_data->email);
}