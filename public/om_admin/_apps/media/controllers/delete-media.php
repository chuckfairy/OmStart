<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set.");return false;}

$json_data = json_decode($_POST["json_data"]);

//Find Config Data
$om_config = new TableObject("om_config");
$config_data = $om_config::find_by("om_table_name", $json_data->table_name);
if(!isset($config_data[0])) {
	$session->set_message("Config table not found"); 
	return false;
}

$table_object = new TableObject($json_data->table_name);

//Loop through each Id
foreach($json_data->ids as $thisId) {
	$table_object->clear_filters();
	$found_media = $table_object::find_by("id", $thisId);
	
	//Check found Media
	if(!isset($found_media[0])) {$session->set_message("Media not found");return false;}

	//Unlink file and delete database entry
	unlink(LOCAL_ROOT.$config_data[0]["data"].$found_media[0]["img_url"]);
	$table_object->delete_by_id($thisId);
}