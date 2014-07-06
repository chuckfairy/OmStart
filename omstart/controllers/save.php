<?php require_once("../initialize.php");?>

<?php

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$table_object = new TableObject(escape($json_data->table));
	$fields = $table_object->get_fields();
	foreach($json_data as $field => $value) {
		if(in_array($field, $fields)) {
			echo $field.":".$value."<br/>";
			$table_object->$field = htmlspecialchars($value);
		}
	}
	$table_object->save();
	echo "<hr/>".json_encode($table_object->get_attributes());
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
	echo json_encode($table_object->get_attributes());
}

?>