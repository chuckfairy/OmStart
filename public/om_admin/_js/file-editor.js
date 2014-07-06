function classToggler(e, classOn, classOff) {
	if(e.hasAttribute("class",classOff)) {
		$(e).removeClass(classOff);
		$(e).addClass(classOn);
	}
}

function getSessionStorage() {
    try {if(!!window.sessionStorage) return window.sessionStorage;} 
    catch(e) {
    	failBox(e);
        return false;
    }
}

/****************************OmBox****************************/

function OmBox(boxId, topId) {
	//if(typeof id == "undefined" || typeof topId == "undefined") {return false;}
	this.window = document.getElementById(boxId);
	this.boxTop = document.getElementById(topId);
	this.init();
}

OmBox.prototype = {
	//Initialization of buttons and functions
	init: function() {
		this.buttonEnable();
		return this;
	},
	
	buttonEnable: function() {
		var thisOm = this;
	
		//Box Show and Hide
		this.boxTopLinks = this.boxTop.getElementsByTagName("a");
		for(var i = 0; i < this.boxTopLinks.length;i++) {
			if(this.boxTopLinks[i].className == "OmBoxX") {
				this.boxTopLinks[i].onclick = function(){thisOm.close()};
			}	
			
			if(this.boxTopLinks[i].className == "OmBoxFullsize") {
				this.boxTopLinks[i].onclick = function(){thisOm.fullSize()};
			}	
			
			if(this.boxTopLinks[i].className = "OmBoxHeader") {
				this.boxTopLinks[i].onmousedown = function(e){
					document.body.onmousemove = function(e) {
						var mousePosition = getMouseXY(e);
						thisOm.window.style.left = -(mousePosition["x"] * .5) + "px";	
						thisOm.window.style.top = -(mousePosition["y"]) + "px";
										
						document.body.onmouseup = function() {document.body.onmousemove = null;}
					}					
				};
			}
		}		
	},
	//Show Hide and Fullscreen Functions
	show: function() {
		this.window.style.display = "block";
	},
	
	close: function() {
		this.window.style.display = "none";	
	},
	
	fullSize: function() {
		if(this.window.style.width == "100%") {
			this.window.style.top = "2em"
			this.window.style.width  = "95%";
			this.window.style.maxHeight = "80%";
			this.window.style.height = "none";	
			document.body.style.overflow = "auto";
		} else {
			this.window.style.top = "0"
			this.window.style.width  = "100%";
			this.window.style.maxHeight = "100%";
			this.window.style.height = "100%";
			document.body.style.overflow = "hidden";
		}
	},
	
	move: function(e) {
		
		console.log(mousePosition);
		
		
	}
}







/****************************OmEditor****************************/
function OmEditor() {
	this.data = {};
	
	//Set Up Ace Editor
	this.aceEditor = new ace.edit("editor");
	this.aceEditor.setTheme("ace/theme/pastel_on_dark");
	this.aceEditor.storage = [0, 1, 2]; 
	//Ace Tab auto set to HTML
	this.currentTab = 0;	
	
	//RenderElement
	this.renderElement = document.getElementById("htmlrender");
		
	//Start it up
	this.init();
}

//OmEditor Public Functions
OmEditor.prototype = {
	//Initialized Called once loads data and buttons
	init: function() {
		//this.getData();
		EditorOmBox = new OmBox("fileBrowser", "fileBrowserTop");
		this.buttonEnable();
	},
	
	//Loads data from file
	loadAce: function(fileData) {
		this.aceEditor.storage[this.currentTab] = fileData.innerHTML;
		console.log(this.aceEditor.storage);
		this.aceEditor.setValue("");
		this.aceEditor.setValue(this.aceEditor.storage[this.currentTab]);
		return true;
	},
	
	//Get all code {HTML, CSS, JS}
	aceGetData: function() {
		data.innerHTML += this.aceEditor.storage[0];
		return data.innerHTML;	
	},
	
	//Changes tabs from html, css, and js
	aceTabChange: function(index) {
		var aceValue = this.aceEditor.getValue();
		this.aceEditor.storage[this.currentTab] = aceValue.toString();
			
		//Ace data change
		this.currentTab = index;
		this.aceEditor.setValue("");
		this.aceEditor.setValue(this.aceEditor.storage[index]);
		
		//Ace Highlighting set
		switch(this.currentTab) {
			case 0: this.aceEditor.session.setMode("ace/mode/html");break;
			case 1: this.aceEditor.session.setMode("ace/mode/css");break;
			case 2: this.aceEditor.session.setMode("ace/mode/javascript");break;
		}
	},
	
	//Grabs all page data currently holds data in XmlHTTPRequest object
	getData: function(fileUrl) {
		//THIS MUST BE REPLACED WITH A GET THAT WORKS WITH SFTP//
		this._ajaxGetPage(fileUrl, this, true);	
	},
	
	//Load blog post data
	loadData: function(responseData) {
		this.render(responseData);
	},
	//Add file to editor
	addFile: function(fileElement) {
		var fileUrl = fileElement.href.split("#file-")[1];
		//Find the dirs and chdir
		if(typeof(fileUrl) == "undefined") {
			var dirUrl = fileElement.href.split("#dir-")[1];
			this.chdir(dirUrl);
			return true;
		}
		//	
		console.log(this.data);	
		fileElement.setAttribute("id", this.data.length + 1);
		this.getData(fileUrl);
		this.fileTabsLinks.appendChild(fileElement.cloneNode(true));
		console.log(this.fileTabsLinks);
	},
	
	chdir: function(dirUrl){
		this.loaded = false;
		var postData = {};
		postData["dirUrl"] = dirUrl;
		this._ajaxPost(postData, "../omstart/controllers/filebrowser/chdir.php", this.loadDir);
	},
	
	loadDir: function(dirData, Om) {
		this.fileTable.innerHTML = dirData;	
		Om.buttonEnable();
	},
	
	//This switches between working files like a tab.
	setCurrentFile: function(fileId) {},
	
	//Done currently by a direct post. New just loads the UI
	new: function() {},
	
	//Save sends to controller writeblog.php
	save: function() {
		var code = this.aceGetData();
		var postData = {
			"fileUrl": this.data[this.currentTab].url,
			"data": code
		};
		var request = this._ajaxPost(postData, "../omstart/controllers/save.php");
		this.render(renderCode);
	}, 
	
	//HTML render theme set elsewhere
	render: function(renderCode) {
		//$("#renderWrap iframe").html(renderCode);			
	},

	//All buttons enable. Directories must be reactivated on change
	buttonEnable: function() {
		var thisOm = this;
		//Save and Delete Icons
		this.saveButton   = document.getElementById("saveIcon");
		this.deleteButton = document.getElementById("deleteIcon");
		this.HDButton    = document.getElementById("HDIcon");
		
		//Enable Save and Delete
		$(this.saveButton)  .on("click", $.proxy(this.save, this));
		$(this.deleteButton).on("click", $.proxy(this._delete, this));
		
		//Enable OmBox and FileBrowser HD button
		$(this.HDButton)   .on("click", function() {EditorOmBox.show();});		
		
		
		
		//File Browser 
		this.fileTable = document.getElementById("fileTable");
		//$(this.fileTable).bind(thisOm.chdir, $.proxy(this.buttonEnable, this));
		this.fileTableLinks = this.fileTable.getElementsByTagName("a");
		
		//File add and get data
		$(this.fileTableLinks).each(function(){
			$(this).on("click", $.proxy(function(){thisOm.addFile(this);}, this));
		});
		
		//Working Files Tabs chooser
		this.fileTabs = document.getElementById("fileTabs");
		this.fileTabsHider = document.getElementById("tabScroll");
		
		this.fileTabsLinks = document.getElementById("fileTabsLinks");
		
		//this.fileTabsLinks = this.fileTabsLinks.getElementsByTagName("a");
		//Hide or show working files
		this.fileTabsHider.onclick = $.proxy(this.fileTabToggle, this);
		this.fileTabsLinks.style.display = "none";
		//this.fileTabsHider.onclick = document.bind(thisOm.fileTabToggle);
		
		
		//File Tab Links get data set in working file chooser
		/*
$(this.fileTabsLinks).each(function(){this.on("click", function(thisOm) {
			pointer = this.getAttribute("id");
			thisOm.setCurrentFile(pointer);
		})});
*/


		//col-resize for code editor and Window frame
		this.colResize = document.getElementById("col-resize");
		this.colResize.onmousedown = function(e, thisOm) {
			document.body.onmousemove = function(e) {
				var mousePosition = getMouseXY(e);
				var newWidth = window.innerWidth - mousePosition["x"];
				if(newWidth <= 200) {
					$("#renderWrap").css("display", "none");
					$("#writerWrap").css("width", "100%");
					//$("#editor").css("width", (100 - ));
				} else {
					$("#renderWrap").css("display", "block");
					$("#renderWrap").css("width", newWidth);
					$("#writerWrap").css("width", mousePosition["x"]);
					//$("#editor").css("width", (mousePosition["x"] - 18));	
				}			
				document.body.onmouseup = function() {document.body.onmousemove = null;}
			}
		}
	},
	
	/****************************Private Functions****************************/
	//Get JSON
	_ajaxGet: function(id, Om) {
		//console.log(this);
		var request = new XMLHttpRequest();
		request.open("GET", "../omstart/controllers/read.php?table=" + this.table + "&id=" + parseInt(id));
		request.setRequestHeader("Content-Type", "application/json");
		request.onreadystatechange = function() {
			if(request.readyState === 4 && request.status===200) {
				var responseText = JSON.parse(trim(this.response));
				Om.data[id] = responseText;
			}
		};
		request.send(null);
	},
	
	_ajaxGetPage: function(page, Om, uriENCODE) {
		page = page || "";
		uriENCODE = uriENCODE || null;
		if(uriENCODE !== true) {page = encodeURIComponent(page);}
		//else {page = decodeURIComponent(page);}
		
		var request = new XMLHttpRequest();
		request.open("GET", "../"+page, true);	
		//request.setRequestHeader("Content-Type", "application/html");
		request.responseType = "text";
		request.onreadystatechange = function() {
			if(request.readyState === 4 && request.status===200) {
				var responseText = this.response;
				try{
					//console.log(request);
					Om.loadData(responseText);
					successBox("HTML GET Successful", this.statusText);
				} catch(e) {failBox(e);}
			}
		}
		request.send(null);
	},
	
	_ajaxPost: function(postData, url, callback) {	
		var thisOm = this;
		var request = new XMLHttpRequest();
		request.open("POST", url);
		//request.responseType = "document";
		request.onreadystatechange = function() {
			if(request.readyState === 4 && request.status===200) {
				console.log(this);
				var responseText = trim(this.response);
				successBox("Post Successful", responseText);
				if(typeof(callback) != "undefined") {
					callback(responseText, thisOm);
				}
			}
		};
		request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		postData = "json_data=" + encodeURIComponent(JSON.stringify(postData));
		request.send(postData);
	},
		
	//Delete by id
	_delete: function() {
		if(typeof(this.currentPage) !== "undefined") {
			var postData = {
				"table": this.table,
				"id": this.currentPage
			};	
			var request = this._ajaxPost(postData, "../omstart/controllers/delete.php");
		} 
		else{failBox("No Post selected");}
	},
	
}

var omEditorObject = new OmEditor();
console.log(omEditorObject);



/****************************Utils****************************/
function getMouseXY(e) {
	var mouseCoords = {};
	mouseCoords["x"] = e.pageX;
	mouseCoords["y"] = e.pageY;

    if (mouseCoords["x"] < 0){mouseCoords["x"] = 0;}
    if (mouseCoords["y"] < 0){mouseCoords["y"] = 0;}  

    return mouseCoords;
}









