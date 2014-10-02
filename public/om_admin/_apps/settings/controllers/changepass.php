<?php require_once("../../../../../omstart/initialize.php");

//Check if data set and if username is long enough
if(!isset($_POST["new_pass"], $_POST["new_pass_check"], $_POST["old_pass"])) {return false;}

if($_POST["new_pass"] !== $_POST["new_pass_check"]) {
	$session->set_message("New password not the same as check");
	redirect_to(SITE_ROOT."om_admin");
}

$Admin->change_password($_POST["new_pass"], $_POST["old_pass"]);

redirect_to(SITE_ROOT."om_admin");