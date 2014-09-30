<?php
require_once("../../../../../omstart/initialize.php");

if(!isset($_GET["table"])) {
	$session->set_message("Table Not Set");
	return false;
}

$media_table = new TableObject($_GET["table"]);
$medias = $media_table::find_all();

$om_config = new TableObject("om_config");
$config_data = $om_config::find_by("om_table_name", $_GET["table"])[0];

?>

<h2>Gallery <blue><?php echo $_GET["table"];?></blue></h2>

<a href="javascript:;" onclick="mediaToggler.setPage('home')" class="mediaBackHome">Back to home</a>

<h3><a href="javascript:;" id="newMediaToggle">New Media</a></h3>
		
<span id="newMedia">
	<form id="uploadPictureForm" action="_apps/media/controllers/upload.php" method="post" enctype="multipart/form-data">
	<input type="hidden" name="table_name" value="<?php echo $_GET["table"];?>">
	
	<h3>Picture File</h3>
	<input type="file" name="mediaFile" id="mediaFile">
	
	<h3>Title</h3>
	<input type="text" name="mediaTitle">
	
	<button id="uploadSubmit" type="submit">Upload</button>
	</form>
</span>


<table class="picturesTable" id="mediaTable">
	<tr>
		<th>Picture</th>
		<th>Title</th>
		<th>Editor</th>
	</tr>
<?php foreach($medias as $media) {?>
	<tr data="<?php echo $media["id"];?>">
		<td><img src="<?php echo SITE_ROOT.$config_data["data"].$media['img_url'];?>"></td>
		<td><?php echo $media["title"];?></td>
		<td><a href="javascript:;" onclick="MediaObject.editMedia(<?php echo $media['id'];?>);">Edit</a></td>				
	</tr>
<?php }?>
</table>