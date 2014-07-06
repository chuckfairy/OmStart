function trim(text) {return text.replace(/^\s+|\s+$/g, '');}

function encodeFormData(data) {
	if (!data) return "";
	var pairs = [];
	for(var name in data) {
		if(!data.hasOwnProperty(name))continue;
		if(typeof data[name] === "function")continue;
		if(typeof data[name] === "undefined")continue;
		var value = data[name].toString();
		value = encodeURIComponent(value).replace("%20", "+");
		pairs.push(name + "=" + value);
	}
	return pairs.join("&");
}

function htmlDecode(input){
  var e = document.createElement('div');
  e.innerHTML = input.replace(/&amp;/g, "&").replace(/&gt;/g, ">").replace(/&lt;/g, "<").replace(/&quot;/g, '"');
  return e;
}

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

function successBox(type, text){
	$("#successBox span").html(type);
	$("#successBox p").html(text);
	$("#successBox").css({"opacity": 1, "z-index":1000});
	setTimeout(function(){$("#successBox").css("opacity", 0);},7000);
	setTimeout(function(){$("#successBox").css("z-index", -5);},9000);
}

function failBox(text) {
	$("#failBox p").html(text);
	$("#failBox").css({"opacity": 1, "z-index":1000});
	setTimeout(function(){$("#failBox").css("opacity", 0);},5000);
	setTimeout(function(){$("#failBox").css("z-index", -5);},7000);
}

/****************************OmEditor****************************/
function OmEditor(table, dir) {
	this.data = {};
	
	//Get Database info
	this.table = table || "blog";
	this.dir = dir || "blog";
	
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
		this.getData();
		this.buttonEnable();
		$(document).ready(function(){setTimeout(function(){$("#loadingPage").slideUp(1000);}, 2000);});
	},
	
	//Loads page data into storage for tab use
	loadAce: function(html) {
		//Decode html to grab tags
		html = htmlDecode(html);
		thisCSS = html.getElementsByTagName("style")[0];
		thisJS  = html.getElementsByTagName("script")[0];
		//Setup CSS and JS
		if(typeof(thisCSS) !== "undefined") {
			this.aceEditor.storage[1] = thisCSS.innerHTML || "#something{display:none;}";
			html.removeChild(thisCSS);
		}
		
		if(typeof(thisJS) !== "undefined") {
			this.aceEditor.storage[2] = thisJS.innerHTML || "//comment";
			html.removeChild(thisJS);
		}
		//Html
		this.aceEditor.storage[0] = html.innerHTML;
		console.log(this.aceEditor.storage);
		this.aceEditor.setValue("");
		this.aceEditor.setValue(this.aceEditor.storage[0]);
		this.aceTabChange(0);
		return true;
	},
	
	//Get all code {HTML, CSS, JS}
	aceGetData: function() {
		this.aceTabChange(0);		
		thisCSS = document.createElement("style");
		thisJS  = document.createElement("script");
		thisCSS.innerHTML = this.aceEditor.storage[1];
		thisJS.innerHTML  = this.aceEditor.storage[2];
						
		data = document.createElement("div");
		data.appendChild(thisCSS);
		data.appendChild(thisJS);
		data.innerHTML += this.aceEditor.storage[0];
		return data.innerHTML;	
	},
	
	//Changes tabs from html, css, and js
	aceTabChange: function(index) {
		var aceValue = this.aceEditor.getValue().toString();
		this.aceEditor.storage[this.currentTab] = aceValue.toString();
			
		//Ace data change
		this.currentTab = index;
		this.aceEditor.setValue("");
		this.aceEditor.setValue(this.aceEditor.storage[index]);
		
		//Class Exchange
		$(this.fileTabs).addClass("editorTypeOff");
		$(this.fileTabs).removeClass("editorTypeOn");
		classToggler(this.fileTabs[index], "editorTypeOn", "editorTypeOff");
		
		//Ace Highlighting set
		switch(this.currentTab) {
			case 0: this.aceEditor.session.setMode("ace/mode/html");break;
			case 1: this.aceEditor.session.setMode("ace/mode/css");break;
			case 2: this.aceEditor.session.setMode("ace/mode/javascript");break;
		}
	},
	
	//Grabs all page data currently holds data in XmlHTTPRequest object
	getData: function() {
		var pages = document.getElementById("fileList").getElementsByTagName("a");		
        for(var i=0; i < pages.length; i++) {
			id = pages[i].href.split("blogId")[1];
			this._ajaxGet(id, this);	
		} 
	},
	
	//Load blog post data
	loadData: function() {
		data = this.data[this.currentPage];
		console.log(data);
		$("#workingPost a").html(data.url);
		$("#workingPost a").get(0).href = "blog/" + data.url;	
		$("#postTitle").html(data.title);
		document.getElementById("postId").value = data.id;
		document.getElementById("postTitle").value = data.title;
		document.getElementById("postTags").innerHTML = data.tags;
	},
	
	//Done currently by a direct post. New just loads the UI
	new: function() {
		if(this.newPanel.style.opacity == 0) {
			$(this.newPanel).css("display, block");
			setTimeout(function(){$(this.newPanel).css({"opacity": 1,"z-index": 5,"-webkit-transform": "rotateY(0deg)"});}, 700);
			$(this.newPanel).show("fast");
		} else {
			$(this.newPanel).css({"opacity": 0,"z-index": -1,"-webkit-transform": "rotateY(90deg)"}).hide("slow");
		}
	},
	
	//Save sends to controller writeblog.php
	save: function() {
		var code = this.aceGetData();
		var postData = {
			"table": this.table,
			"id": document.getElementById("postId").value,
			"title": document.getElementById("postTitle").value,
			"tags": document.getElementById("postTags").value,
			"code": code
		};
		postData["url"] = this.data[this.currentPage].url;
		console.log(postData);
		var request = this._ajaxPost(postData, "../omstart/controllers/save.php");
		console.log(code);
		this.render(code);
	}, 
	
	//HTML render theme set elsewhere
	render: function(html) {$("#htmlrender").html(html);},

	//All buttons
	buttonEnable: function() {
		//Save and Delete Icons
		this.saveButton   = document.getElementById("saveIcon");
		this.deleteButton = document.getElementById("deleteIcon");
		this.newButton    = document.getElementById("newIcon");
		this.newPanel     = document.getElementById("newPanel");
		//Enable
		$(this.saveButton)  .on("click", $.proxy(this.save, this));
		$(this.deleteButton).on("click", $.proxy(this._delete, this));
		$(this.newButton)   .on("click", $.proxy(this.new, this));
		$("#newExit")       .on("click", $.proxy(this.new, this));
		
		var e = this;
		
		//Set up tabs
		this.fileTabs = document.getElementById("editorNav").getElementsByTagName("a"); 
		this.fileList = document.getElementById("fileList").getElementsByTagName("a");
		$(this.fileTabs).addClass("editorTypeOff");
		$(this.fileList).addClass("fileOff");
		
		
		//Blog Post Links
		$(this.fileList).on("click", function() {
			$(e.fileList).addClass("fileOff");
			$(e.fileList).removeClass("fileOn");
			classToggler(this, "fileOn", "fileOff");	
			id = this.href.split("blogId")[1];
			e.currentPage = id;
			//e._ajaxGetPage(e.data[id].url, e);
			e.loadAce(e.data[id].code);
			e.loadData();
		});
		
		//Ace Tabs
		$(this.fileTabs).on("click", function() {
			$(e.fileTabs).addClass("editorTypeOff");
			$(e.fileTabs).removeClass("editorTypeOn");
			classToggler(this, "editorTypeOn", "editorTypeOff");
			index = $(this).index();
			e.aceTabChange(index);			
		})
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
	
	_ajaxGetPage: function(page, Om) {
		console.log(page);
		var request = new XMLHttpRequest();
		request.open("GET", "blog/" + encodeURIComponent(page));	
		//request.setRequestHeader("Content-Type", "application/html");
		request.responseType = "document";
		request.onreadystatechange = function() {
			if(request.readyState === 4 && request.status===200) {
				var responseText = this.response;
				try{
					Om.loadAce(responseText)
					Om.loadData();
					successBox("HTML GET Successful", responseText.body);
				} catch(e) {failBox(e);}
			}
		}
		request.send(null);
	},
	
	_ajaxPost: function(postData, url) {		
		var request = new XMLHttpRequest();
		request.open("POST", url);
		request.onreadystatechange = function() {
			if(request.readyState === 4 && request.status===200) {
				var responseText = trim(this.response);
				successBox("Post Successful", responseText);
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




var omEditorObject = new OmEditor("blog", "blog");




