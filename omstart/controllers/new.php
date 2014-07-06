<?php
require_once("../initialize.php");

if(isset($_POST["table"])) {
	$database->query($_POST["table"]);
}







?>
