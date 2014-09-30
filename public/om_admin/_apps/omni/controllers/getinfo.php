<?php require_once("../../../../../omstart/initialize.php");

if(!isset($_POST["json_data"])) {$session->set_message("No data set");return false;}

$json_data = json_decode($_POST["json_data"]);

$filelocation = SRC.$json_data->file_path;
$filename = $json_data->file_path;

//Check if directory
if(!is_dir($filelocation) && !is_file($filelocation)) {
	$session->set_message("Data given is not a directory and is not a file");
	return false;
}

//Get file data and permissions
$fileoct = get_file_oct($filelocation);
$filePermName = get_file_owner($filelocation);
$fileData = file_get_contents($filelocation);
$fileGroup = get_file_group($filelocation);

//Check oct
$thisOct = get_php_oct($filelocation);


switch($thisOct) {
	case 4: $filePermisions = "Read";break;
	case 5: $filePermisions = "Read Exec";break;
	case 6: $filePermisions = "Read Write";break;
	case 7: $filePermisions = "Read Write Exec";break;
}

//Check if dir or file
$icon='';
if(is_file($filelocation)) {
	$icon = "<img src='".ICONS."file.png"."'>";
} else {
	$icon = "<img src='".ICONS."folder.png"."'>";
}

//Display file data
$detailsHTML = "<div class='omniInfoDiv'>";
$detailsHTML.= $icon;
$detailsHTML.= "<h2>File <weak>{$filename}</weak></h2>";
$detailsHTML.= "<h3>File Owner <weak>{$filePermName}</weak></h3>";
$detailsHTML.= "<h3>File Group <weak>{$fileGroup}</weak></h3>";
$detailsHTML.= "<h3>Permissions <weak>{$filePermisions}</weak></h3>";
$detailsHTML.= "<h3>File Oct <weak>{$fileoct}</weak></h3>";
$detailsHTML.= '<h3><a href="javascript:OmniObject.openFilePrompt('."'{$filename}'".')">Open</a></h3>';
$detailsHTML.= "</div>";
echo $detailsHTML;