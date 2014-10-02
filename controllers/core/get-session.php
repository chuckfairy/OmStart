<?php require_once("../../initialize.php");

if(!$session->is_logged_in()) {
	redirect_to(SITE_ROOT);
}
//echo "<ul>";
$session->output_messages("li");
//echo "</ul>";


?>