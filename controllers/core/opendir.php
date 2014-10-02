<?php require_once("../../omstart/initialize.php");

function _display_dir($dir) {
	$dirHandle = opendir($dir);
	while(false !== ($filename = readdir($dirHandle))):
		//Remove save Directory		
		if(preg_match("/^[\.]/", $filename)) {continue;}
		$icon = is_dir($dir.$filename)? "folder.png": "file.png";
		$type = is_dir($dir.$filename)? "dir": "file";
		$delim = is_dir($dir.$filename)? "/":"";
		$filename = $filename.$delim;
		
		//echo $dir.$filename;
		echo "<span class='browserFile' data='{$dir}{$filename}' data-type='{$type}'>".
			"<img src='".ICONS.$icon."'>".
			"<a href='javascript:void(0)'>".$filename."</a></span>";
	endwhile;
}

if(isset($_POST["json_data"])) {
	$json_data = json_decode($_POST["json_data"]);
	$dir_path = $json_data->dir_path;
	$url = SRC.$dir_path;
	if(!is_dir($url)) {return false;}
	
	$dirHandle = opendir($url);
	while(false !== ($filename = readdir($dirHandle))):
		//Remove save Directory		
		if(preg_match("/^[\.]/", $filename)) {continue;}
		if(is_dir(SRC.$dir_path.DS.$filename.DS)) {
?>
	<div class="browserDir" data-type="dir" data="<?php echo $dir_path.$filename.DS;?>">	
		<img src="<?php echo ICONS."folder.png";?>">
		<a href="javascript:void(0);"><?php echo $filename;?></a>
		<div><?php _display_dir($dir_path.$filename.DS);?></div>
	</div>
	
<?php } else { ?>
	<span class="browserFile" data-type="file" data="<?php echo $dir_path.$filename;?>">
		<img src="<?php echo ICONS."file.png";?>">
		<a href="javascript:void(0)"><?php echo $filename;?></a>
	</span>

<?php
} endwhile;}?>



