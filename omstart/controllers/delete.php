<?php require_once("../initialize.php");?>

<?php
print_r($_POST);

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$table_object = new TableObject($json_data->table);
	if($found_table = $table_object->find_by_id($json_data->id)) {
		$table_object->delete_by_id($json_data->id);
		echo "<h2>DELETED</h2>";
	}
	return false;
}

if(isset($_POST["table"])  && isset($_POST["id"])) {
	$table_object = new TableObject(escape($_POST["table"]));
	if($found_table = $table_object->find_by_id(escape($_POST["id"]))) {
		$table_object->delete_by_id(escape($_POST["id"]));
		//return true;
	}
	return false;
}

?>