<?php require_once("../../omstart/initialize.php");

if(isset($_GET["table"], $_GET["id"])) {
	if($database->validate_table($_GET["table"])) {$table = escape($_GET["table"]);}
	else {echo "Table not valid";return false;}
	
	if(in_array($table, $database->tables)) {
		$table_object = new TableObject($table);
		$table_data = $table_object->find_by_id(escape($_GET["id"]));
		while($table = $database->assoc($table_data)) {
			echo json_encode($table);
/*
			foreach($table as $field => $value) {
				echo "<".$field.">".$value."</".$field.">";
			}
*/
			
			return true;
		}
	}
} 

echo "false";