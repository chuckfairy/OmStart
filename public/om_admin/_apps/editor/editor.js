//Get navigator and set functionality
var browserNav = document.getElementById("fileEditorDirs");
var workingDir = document.getElementById("workingDir");
var fileInfoDiv = document.getElementById("fileInfo");

//Global File data
var currentDirPath = "";
var currentFile = "";

//Controllers
var opendir_url = "../../controllers/core/opendir.php";
var file_page = "_apps/editor/controllers/readfile.php?file=";
var save_file_page = "_apps/editor/controllers/savefile.php";
var new_file_page  = "_apps/editor/controllers/newfile.php"

function getFile(file_path) {	
	var url = (file_page + file_path);
	fileEditorBox.show();
	ajaxGetPage(url, loadFile);
}

function loadFile(responseText) {
	//Get Json Data
	var json_data = JSON.parse(responseText);
	fileInfoDiv.innerHTML = htmlDecode(json_data.details);					
	
	//Set ace mode
	var extension = currentFile.split(".");
	extension = extension[extension.length - 1];
	if(extension === "js") {extension = "javascript";}
	codeEditor.setValue(json_data.filedata);
	codeEditor.getSession().setMode("ace/mode/" + extension);
	
	var editorSaveFile = document.getElementById("editorSaveFile");
	editorSaveFile.onclick = function(){saveFile()}
}

function saveFile() {
	var filename  = currentFile.split("?file=")[1];
	var filedata = codeEditor.getSession().getValue();
	var postData  = {
		filename: filename,
		filedata: filedata
	}
	console.dir(postData);
	
	ajaxPost(postData, save_file_page, function(responseText) {
		console.log(responseText);
	});
}

/*
function dirCollapse(xElement) {
	var files_in_dir = xElement.getElementsByTagName("span");
	for(var f = 0; f < files_in_dir.length; f++) {
		if(files_in_dir[f].style.display !== "block") {
			files_in_dir[f].style.display = "block";
		} else {
			files_in_dir[f].style.display = "none";
		}
	}
}

//Back Button
document.getElementById("fileEditorBack").onclick = function() {
	if(currentDirPath == "") {return true;}
	var path_array = currentDirPath.split("/");

	//Dir has a back dir	
	if(path_array.length > 1) {
		//Pop previous dir value
		path_array.pop();
		path_array.pop();
		var newDir = path_array.join("/");
		//No / for home
		if(newDir !== "") {newDir = newDir + "/";} 
		currentDirPath =  newDir;
		console.log(currentDirPath);
		ajaxPost({dir_path: currentDirPath}, opendir_url, loadDirs);
		workingDir.innerHTML = currentDirPath;
	}
}
*/

//Open, check, and load a file path from Omni
function editorOpen(file_path) {
	console.log(file_path);
	currentFile = file_path;
	getFile(file_path);
}

/****************************Dependencies****************************/
var codeEditorWrap = document.getElementById("aceEditor");
codeEditor = ace.edit("aceEditor");
