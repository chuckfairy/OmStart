<?php require_once("../../omstart/initialize.php");

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$table_object = new TableObject($json_data->table_name);
	
	$fields = $table_object->get_fields();
	foreach($json_data as $field => $value) {
		if(in_array($field, $fields)) {
			$table_object->$field = $value;
		}
	}
	
/*
	//Timestamp on saves
	if($table_object::is_field("timestamp")) {
		$table_object->timestamp = date("m/d/Y H:i:s");
	}
*/
	
	$table_object->save();
}

if(isset($_POST["table"])) {
	$table_object = new TableObject(escape($_POST["table"]));
	$fields = $table_object->get_fields();
	foreach($_POST as $field => $value) {
		if(in_array($field, $fields)) {
			$table_object->$field = $value;
		}
	}
	$table_object->save();
}

?>