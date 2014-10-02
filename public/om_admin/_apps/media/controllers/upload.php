<?php require_once("../../../../../omstart/initialize.php");
print_r($_POST);

if(isset($_POST["mediaTitle"])) {
	
	//om_config directorty data
	$om_config = new TableObject("om_config");
	$media_data = $om_config::find_by("om_table_name", $_POST["table_name"])[0];
	$om_config->clear_filters();
	
	//Grab needed data
	$gallery_table = new TableObject($_POST["table_name"]);
	
	$media_dir = $media_data["data"];
	echo $media_dir;
	
	//Update
	if(isset($_POST["mediaId"])) {
		$found_tattoo = $gallery_table::find_by("id", $_POST["mediaId"]);
		if(!isset($found_tattoo[0])) {$session->set_message("Media Not Found");}
		$gallery_table->id = $_POST["mediaId"];
	} 
	
	//Upload File if set
	if(!empty($_FILES["mediaFile"]["tmp_name"])) {
		$gallery_table->img_url = clean_file($_POST["mediaTitle"].substr($_FILES["mediaFile"]["name"],-4));
		move_uploaded_file($_FILES["mediaFile"]["tmp_name"], 
			LOCAL_ROOT.$media_dir.$gallery_table->img_url);	
	} else {
		if(!isset($gallery_table->id)) {$session->set_message("No Picture uploaded"); return false;}
	}
	
	//Set data
	$gallery_table->title = $_POST["mediaTitle"];
	$gallery_table->save();
}

//header("HTTP/1.0 204 No Content");