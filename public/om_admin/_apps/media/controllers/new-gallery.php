<?php

require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data sent"); return false;}

$json_data =  json_decode($_POST["json_data"]);

//$table_object = new TableObject();

$directory_location = LOCAL_ROOT.$json_data->directory;

//Error check for table name + directory + permissions
if(in_array($json_data->table_name, $database::$tables)) {
	$session->set_message("Table Name Taken");
	return false;
}

$json_data->table_name = clean_file($json_data->table_name);

if(!is_dir($directory_location)) {
	$session->set_message("Path is not a directory");
	return false;
}

$php_oct = get_php_oct($directory_location);
echo $php_oct;
if($php_oct !== 6 && $php_oct !== 7) {
	$session->set_message("Php does not have writeable permissions on directory");
	return false;
} 



$tableQuery = "CREATE TABLE ".$json_data->table_name." ( ";
$tableQuery.= "id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, ";
$tableQuery.= "img_url VARCHAR(255) NOT NULL, ";
$tableQuery.= "title VARCHAR(255) NOT NULL)";
$database->query($tableQuery);

$om_config = new TableObject("om_config");

$om_config->type = "media";
$om_config->om_table_name = $json_data->table_name;
$om_config->data = $json_data->directory;
$om_config->save();