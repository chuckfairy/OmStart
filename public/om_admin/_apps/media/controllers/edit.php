<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set.");return false;}

$json_data = json_decode($_POST["json_data"]);
$table_object = new TableObject($json_data->table_name);


//Edit Media		
$media = $table_object::find_by("id", $json_data->id);
if(!isset($media[0])) {$session->set_message("Media not found");return false;}
$media = $media[0];

$om_config = new TableObject("om_config");
$om_config->clear_filters();
$config_data = $om_config::find_by("om_table_name", $json_data->table_name)[0];
?>
<a href="javascript:;" onclick="mediaToggler.setPage('gallery');" class="mediaBackHome">Back to pictures</a>
<form id="editMediaForm" enctype="multipart/form-data" method="post" action="_apps/media/controllers/upload.php" id="editMediaForm">
	
	<input type="hidden" name="mediaId" value="<?php echo $media['id'];?>"> 
	<input type="hidden" name="table_name" value="<?php echo $json_data->table_name;?>">
	
	<h2>Title <input type="text" name="mediaTitle" value="<?php echo $media['title'];?>"></h2>
	
	<h2>File <input type="file" name="mediaFile"></h2>
	<img src="<?php echo SITE_ROOT.$config_data["data"].$media["img_url"];?>">
		
		
	<a id="deleteMediaButton"href="javascript:;" onclick="MediaObject.deleteMedia([<?php echo $media['id'];?>])">Delete</a>	
	<button type="submit" id="updateMediaButton">Update</button>

</form>