<?php require_once("../../../../../omstart/initialize.php");

$json_data = json_decode($_POST["json_data"]);
$output = $database->query($json_data->exec);

print_r($output);