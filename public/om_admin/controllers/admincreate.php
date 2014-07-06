<?php 

require_once("../../../omstart/initialize.php");

if(isset($_POST["admincreate"])) {
	$adminTable = new TableObject("om_admin");
	$adminTable->username = $_POST["username"];
	$adminTable->hash = password_encrypt($_POST["password"]);
	$adminTable->email    = $_POST["email"];
	var_dump($adminTable);
	$adminTable->save();
}





?>