<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set");return false;}

$json_data = json_decode($_POST["json_data"]);

$table_object = new TableObject($json_data->table_name);

foreach($json_data->deleteArray as $deleteId) {
	echo $deleteId."\n";
	
	$table_object->delete_by_id($deleteId);
}

//print_r($json_data);




