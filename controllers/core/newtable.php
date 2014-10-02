<?php
require_once("../../omstart/initialize.php");

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$database->query($json_data->create_query);
}


?>