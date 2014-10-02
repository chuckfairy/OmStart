<?php require_once("../../../../../omstart/initialize.php");

//Check if data set and if username is long enough
if(!isset($_POST["new_username"], $_POST["password"])) {return false;}

$Admin->change_username($_POST["new_username"], $_POST["password"]);

redirect_to(SITE_ROOT."om_admin");