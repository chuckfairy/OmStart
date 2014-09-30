<?php require_once("../../../../../omstart/initialize.php");

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$dir_path = $json_data->dir_path;
	$url = SRC.$dir_path;
	if(!is_dir($url)) {return false;}
?>

<table class="omniTable" id="omniFileTable">
	<tr>
		<th>File Name</th>
		<th>Last Change</th>
		<th>Size</th>
	</tr>
	

<?php
	$dirHandle = opendir($url);
	while(false !== ($filename = readdir($dirHandle))):
		//Remove save Directory		
		if(preg_match("/^[\.]/", $filename)) {continue;}
		if(is_dir(SRC.$dir_path.DS.$filename.DS)) {
?>
<tr data-type="dir" data="<?php echo $dir_path.DS.$filename;?>">
	<td>	
		<img src="<?php echo "_assets/folder.png";?>">
		<a href="javascript:;"><?php echo $filename;?></a>
	</td>

	<td>
		<?php echo date ("F d Y H:i:s", filemtime(SRC.$dir_path.DS.$filename.DS));?>
	</td>
	
	<td>+</td>

</tr>
	
<?php } else { ?>
<tr data-type="file" data="<?php echo $dir_path.DS.$filename;?>">
	<td>
		<img src="<?php echo "_assets/file.png";?>">
		<a href="javascript:void(0)"><?php echo $filename;?></a>
	</td>
	
	<td>
		<?php echo  date ("F d Y H:i:s", filemtime(SRC.$dir_path.DS.$filename));?>
	</td>
	
	<td>
		<?php echo filesize(SRC.$dir_path.DS.$filename);?>
	</td>
</tr>

<?php } endwhile;?>
</table>
<?php }?>