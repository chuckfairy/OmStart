<?php require_once("../../../../../omstart/initialize.php");


$json_data = json_decode($_POST["json_data"]);
$json = array();

$cmd = "cd ".$json_data->cwd."; ".$json_data->exec." ; echo '%%%%';pwd;";
$output = shell_exec($cmd);

$json_split  = explode("%%%%", $output);

if(!isset($json_split[1])) {
	$json["output"]= $output;
	$json["cwd"] = SRC;
} else {
	$json["output"]= $json_split[0];
	$json["cwd"] = trim($json_split[1]).DS;
}



echo json_encode($json);
?>