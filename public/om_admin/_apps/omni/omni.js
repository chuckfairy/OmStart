//Omni filesystem manager
//Uses ajax functions within controllers
function Omni(){
	//Data attributes
	this.currentDirPath ="";
	this.currentFileSelect = "";

	//Elements
	this.omniDir        = document.getElementById("omniDir");
	this.omniWorkingDir = document.getElementById("omniWorkingDir");
	this.omniOpenPrompt = document.getElementById("omniOpenPrompt");
	this.omniOpenLinks  = document.getElementById("omniOpenLinks");
	this.omniContext    = new OmContext("omniContext");
	this.omniFileTable;
	
	//URLS
	this.openDirUrl = "_apps/omni/controllers/opendir.php";
	this.mkdirUrl = "_apps/omni/controllers/mkdir.php";
	this.touchUrl = "_apps/omni/controllers/touch.php";
	this.infoUrl = "_apps/omni/controllers/getinfo.php";
	this.trashUrl = "_apps/omni/controllers/trash.php";
	this.renameUrl = "_apps/omni/controllers/rename.php";
	
	this.init();
};


Omni.prototype = {
		
	//Set up navigation of dirs, file opener, and open root
	init: function() {
		this.setUpNavigation();
		this.setUpOpener();
		this.setUpContextToggle();
		this.openDir("");
		
		var thisOm;
		var cf = this.contextLoad;
		var contextFunction = cf.bind(this);
		this.omniContext.addContext(this.omniDir, function(eData, e){
			contextFunction(eData, e);
		});
		return this;	
	},	
	
	//Set up navigation on omniTable and 
	setUpNavigation: function() {
		var thisOm = this;
		
		//Navigation for omniTable
		function tableNav(e) {
			//Get targets parent either of TD or A + IMG
			var thisTarget = e.target;
			if(thisTarget.tagName === "TD") {
				var thisParent = thisTarget.parentNode;
			} else if(thisTarget.tagName === "A" || "IMG") {
				var thisParent = thisTarget.parentNode.parentNode;
			} else {
				return false;
			}
			
			//Find data type of either file or dir
			var dataType = thisParent.getAttribute("data-type");
			if(!isset(dataType)) {return false;}
			var path = thisParent.getAttribute("data");
			
			//open dir if it is dir
			if(dataType === "dir") {thisOm.openDir(path);}	
			
			//Open file prompt if it is a file
			if(dataType === "file") {thisOm.openFilePrompt(path);}		
		}
		//Add listener on click for omniDir
		addEvent("click", this.omniDir, tableNav);
		
		//Navigation for workingDir paths
		function workingDirNav(e) {
			var thisTarget = e.target;
			var path = thisTarget.getAttribute("data");
			thisOm.openDir(path);
		}		
		//add listener on click for omniWorkingDir
		addEvent("click", this.omniWorkingDir, workingDirNav);			
	},
	
	setUpOpener: function() {
		var thisOm = this;
		addEvent("click", this.omniOpenLinks, function(e) {
			var thisTarget = e.target;
			if(thisTarget.tagName === "IMG") {
				thisTarget = thisTarget.parentNode;
			}			
			appName = thisTarget.getAttribute("data");
			
			//OmMedia only takes images * OmMedia.js
			if(appName === "OmMedia") {
				
			}
			
			//Editor takes pretty everythingm * editor.js
			if(appName === "editor") {
				editorOpen(thisOm.currentFileSelect);
			}
			
			//Emulator takes public locations * emulator.js
			if(appName === "emulator") {
				emulatorOpen(thisOm.currentFileSelect);
			}
			
			//Close Omni and prompt
			thisOm.omniOpenPrompt.style.display = "none";
			omniBox.close();
		});	
		//Close prompt initially
		this.omniOpenPrompt.style.display = "none";
	},
	
	//Context menu changes based on what is right clicked
	setUpContextToggle: function() {
		this.omniContextKeys = document.getElementById("omniContextKeys");
		var contextKeys = this.omniContextKeys.getElementsByTagName("a");
		var contextDivs = this.omniContext.contextBox.getElementsByTagName("div");
		
		this.contextToggler = new PageToggler(contextKeys, contextDivs, {keyAttribute: "data"});
	},
		
	//Load dir data into omniDir and setup path system
	loadDirs: function(responseText) {
		var thisOm = this;
		this.omniDir.innerHTML = responseText;
		
		//Set up path system using currentDirPath
		this.omniWorkingDir.innerHTML = "<a href='javascript:;' data=''>home</a>";
		var path_array = this.currentDirPath.split("/");		
		var currentPath ="";
		for(var i = 0; i < path_array.length; i++) {
			var thisPath = path_array[i];
			//Set current path
			if(thisPath === "") {continue;}
			
			if(currentPath !== "") {currentPath+= "/" + thisPath;}
			else {currentPath = thisPath;}
			
			//Create path link and set data and innerHTML
			var pathLink = document.createElement("a");
			pathLink.setAttribute("data", currentPath);
			pathLink.href = "javascript:;";
			pathLink.innerHTML = thisPath;
			
			//Append Link to working dir
			this.omniWorkingDir.appendChild(pathLink);
		}
		
		//Get Elements
		this.omniFileTable = document.getElementById("omniFileTable");
		this.omniFileTbody = this.omniFileTable.getElementsByTagName("tbody")[0];
		this.omniFileTbody.oncontextmenu = function(e) {
			if(e.target.tagName === "A" || e.target.tagName === "TD") {
				thisOm.contextToggler.setPage("file");
			} else {
				thisOm.contextToggler.setPage("home");
			}
		}
		
	},	
	
	//Open dir based on path 
	openDir: function(dir_path) {
		var thisOm = this;
		if(dir_path === "") {this.currentDirPath ="";}
		else {this.currentDirPath = dir_path;}
		ajaxPost({dir_path: dir_path}, this.openDirUrl, function(responseText) {
			thisOm.loadDirs(responseText);
		});
	},
	
	//Opens the file prompt which uses variable currentFileSelect
	//To open file in different app
	openFilePrompt: function(file_path) {
		this.currentFileSelect = file_path;
		console.log(this.currentFileSelect);
		this.omniOpenPrompt.style.display = "block";
	},
	
	closeFilePrompt: function() {
		this.omniOpenPrompt.style.display = "none";		
	},
	
	contextLoad: function(eData, e) {
		//If File or directory add new menu items
		
		if(eData === "mkdir") {
			this.mkdir();
		}
		else if(eData === "touch") {
			this.touch();
		}
		
		else if(eData === "getinfo") {
			this.getInfo(e);
		}
		
		else if(eData === "trash") {
			this.trash(e);
		}
		
		else if(eData === "rename") {
			this.rename(e);
		}
	},
	
	//Make: create and appends input td to 
	mk: function() {
		//Create new row and input
		mkdirTr = document.createElement("tr");
		mkdirTr.innerHTML = "<td></td><td></td><td></td>";
		mkdirInput = document.createElement("input");

		//Append to first child
		mkdirTr.firstChild.appendChild(mkdirInput);
		//Append to table
		this.omniFileTbody.appendChild(mkdirTr);
		mkdirInput.focus();
		mkdirTr.input = mkdirInput;
		return mkdirTr;
	},
	
	mkdir: function() {
		var mkdirEl = this.mk();

		var thisOm = this;
		mkdirEl.input.onblur = function() {
			if(mkdirEl.input.value === "") {return false;}
			var postData = {
				dir_path: thisOm.currentDirPath,	
				dir_name: mkdirEl.input.value		
			}
			
			ajaxPost(postData, thisOm.mkdirUrl, function(responseText) {
				thisOm.openDir(thisOm.currentDirPath);
			});
			
			thisOm.omniFileTbody.removeChild(mkdirEl);
		}		
	},
	
	touch: function() {
		var mkfileEl = this.mk();
		
		var thisOm = this;
		mkfileEl.input.onblur = function() {
			if(mkfileEl.input.value === "") {return false;}
			var postData = {
				dir_path: thisOm.currentDirPath,	
				file_name: mkfileEl.input.value		
			}
			
			ajaxPost(postData, thisOm.touchUrl, function(responseText) {
				thisOm.openDir(thisOm.currentDirPath);
			});
			
			thisOm.omniFileTbody.removeChild(mkfileEl);
		}
	},
	
	getInfo: function(e) {
		//Set up info OmBox wraps
		var getInfoSpan = document.createElement("span");
		var getInfoDiv = document.createElement("div");
		getInfoSpan.appendChild(getInfoDiv);
		
		console.log(e.target);
		//Get url from targeted element
		var fileUrl;
		if(e.target.tagName === "TD" ||
		   e.target.tagName === "A" || 
		   e.target.tagName === "IMG") {
			var thisParent = e.target.parentNode;
			
			//If a or img go back one more parent
			if(e.target.tagName === "A" || "IMG") {
				thisParent = thisParent.parentNode;
			}
			
			fileUrl = thisParent.getAttribute("data");
		} 
		//Get info of current directory
		else {
			fileUrl = this.currentDirPath;
		}
		
		//Get info from path
		ajaxPost({file_path: fileUrl}, this.infoUrl, function(responseText) {
			getInfoDiv.innerHTML = responseText;
			var getInfoBox = new OmBox(getInfoSpan, {
				title: ("Info " + this.currentDirPath),
				theme: "wannaBe",
				showDefault: true
			});
			document.body.appendChild(getInfoBox.window);
		});		
	},
	
	getSelectedFileUrl: function(e) {
		var fileUrl
		if(e.target.tagName === "TD" ||
		   e.target.tagName === "A" || 
		   e.target.tagName === "IMG") {
			var thisParent = e.target.parentNode;
			
			//If a or img go back one more parent
			if(e.target.tagName === "A" || e.target.tagName === "IMG") {
				thisParent = thisParent.parentNode;
			}
			
			fileUrl = thisParent.getAttribute("data");
			if(fileUrl.substr(1) === "/") {fileUrl = fileUrl.substr(1);}
		}
		
		return fileUrl || false;
	},
	
	trash: function(e) {
		var fileUrl = this.getSelectedFileUrl(e);
		if(!fileUrl) {return false;}
		
		var thisOm = this;
		ajaxPost({file_path: fileUrl}, this.trashUrl, function(responseText) {
			console.log(responseText);
			thisOm.openDir(thisOm.currentDirPath);
			Notifications.notifi();
		});	
	},
	
	rename: function(e) {
		if(e.target.tagName !== "TD" &&
		   e.target.tagName !== "A" &&
		   e.target.tagName !== "IMG") { return false;}
		var thisParent = e.target.parentNode;
		
		//If a or img go back one more parent
		if(e.target.tagName === "A" || e.target.tagName === "IMG") {
			thisParent = thisParent.parentNode;
		}
		var thisTd = thisParent.getElementsByTagName("td")[0];
		var thisFile = thisTd.getElementsByTagName("a")[0];
		thisFile = thisFile.innerHTML;
		
		var thisRename = document.createElement("input");
		thisRename.type = "text";
		thisRename.value = thisFile;
		thisTd.innerHTML = "";
		thisTd.appendChild(thisRename);
		
		//Set up rename functionality
		thisRename.focus();
		var thisOm = this;
		thisRename.onblur = function() {
			var newName = thisRename.value;
			if(thisFile === newName) {return true;}
			var postData = {
				dir_name: thisOm.currentDirPath,
				file_name: thisFile,
				rename: newName
			}
			
			ajaxPost(postData, thisOm.renameUrl, function(responseText) {
				thisOm.openDir(thisOm.currentDirPath);
			});
		}
				   	
	},
	
	sortTable: function(eData) {
		
	}
}

var OmniObject = new Omni();

/**************Omni Select**************/

function OmniSelect() {
	
	
	
}

