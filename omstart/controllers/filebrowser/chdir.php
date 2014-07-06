<?php
require_once("../../initialize.php");

if(isset($_POST["json_data"])) {	
	$dirData = json_decode($_POST["json_data"]);
	$currentDir = $dirData->dirUrl;
	$dirPath = SITE_SRC.$currentDir;	
	//Check if it is recursive ../
	$dir_array = explode("/", $dirPath);
	$filename = array_pop($dir_array);
	if($filename == "..") {
		array_pop($dir_array);
		$currentDir = array_pop($dir_array); 
		array_push($dir_array,  $currentDir);
		$dirPath = join("/", $dir_array); 
		if($currentDir == SRC_DIR) {$currentDir = "";}
	} 
		
	echo "<h3>".$dirPath."</h3>";
	
	//Try showing the dir contents
	try {
		$dirHandle = opendir($dirPath);
		while(false !== ($filename = readdir($dirHandle))){
			//Remove save Directory		
			if($filename == ".") {continue;}
			
			//Remove .DS_STORE files
			if(preg_match("/.DS_STORE/i", $filename)) {
				continue;
			}
			
			//Check if dir or file
			if(is_dir($dirPath.DS.$filename)) {
				echo "<tr><td><a href='#dir-{$currentDir}/{$filename}' class='file-dir'>{$filename}</a></td>".
					 "<td>Directory</td>";
			} else {
				echo "<tr><td><a href='#file-{$currentDir}/{$filename}' class='file-link'>{$filename}</a></td>". 
					 "<td>File</td></tr>";
			}
		}
	} catch(Exception $e) {echo $e;}
}




?>