<?php require_once("../initialize.php");

if(!empty($_POST["title"])) {
	$table_object = new TableObject($database::escape($_POST["table"]));
	$table_object->id = $_POST["id"];
	$table_object->code  = preg_replace("^(</?div>)^", "", $_POST["code"]);
	$table_object->content   = $_POST["contentUrl"];
	//$table_object->content;
	$table_object->title = $_POST["title"];
	$table_object->tags  = $_POST["tags"];	
	echo $table_object->code;
	blog_file($table_object->code, $_POST["table"].DS.$table_object->content);
	$table_object->save();
}

