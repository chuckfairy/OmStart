<?php require_once("../../../../../omstart/initialize.php");
//Check if isset
if(!isset($_GET["file"])) {return false;}

//Set File Location
$filelocation = SRC.$_GET["file"];

//Check if directory
if(is_dir($filelocation) || !is_file($filelocation)) {
	$session->set_message("File given is directory or not a file");
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


//Display file data
$detailsHTML='';
$detailsHTML.= "<h2>File <weak>{$_GET["file"]}</weak></h2>";
$detailsHTML.=  "<h3>File Owner <weak>{$filePermName}</weak></h3>";
$detailsHTML.= "<h3>File Oct <weak>{$fileoct}</weak></h3>";
$detailsHTML.= "<h3>File Group <weak>{$fileGroup}</weak></h3>";
$detailsHTML.= "<h3>Permissions <weak>{$filePermisions}</weak></h3>";
$detailsHTML.= "<h3><button type='button' id='editorSaveFile' data='{$_GET["file"]}'>Save</button>";
$detailsHTML.= "<h3><button type='button' id='editorDeleteFile' data='{$_GET["file"]}'>Delete</button>";

//Return data for file details and actual file data
$json_return = json_encode([
	"details" => htmlspecialchars($detailsHTML),
	"filedata" => $fileData
]);

echo $json_return;