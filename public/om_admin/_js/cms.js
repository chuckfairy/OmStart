
"use strict"
/****************************TABLE EDITOR****************************/
function OmTable(table, tableId) {
	//Variables for table info
	this.table = table;
	this.tableObject = document.getElementById(tableId);
	this.rows = this.tableObject.getElementsByTagName("tr");
	this.fields = [];
	this.data   = [];
	this.tdTags = [];
	
	//Setup
	for(var i=0; i < this.rows.length; i++) {
		//Set field names
		var thTags = this.rows[i].getElementsByTagName("th");
		if(i===0) {
			var thTags = this.rows[i].getElementsByTagName("th");
			for(var ip=0;ip < thTags.length;ip++) {
				this.fields.push(thTags[ip].innerHTML.toLowerCase());
			}
			continue;
		}
		
		this.tdTags[i] = this.rows[i].getElementsByTagName("td");
		for(var ip=0; ip < this.fields.length;ip++) {
			if(ip ===0){this.data[i] = {};}
			this.data[i][this.fields[ip]] = this.tdTags[i][ip].innerHTML;
			var textInput = document.createElement("input");
			textInput.type = "text";
			textInput.name = this.fields[ip];
			textInput.style.display = "none";
			textInput.setAttribute("value", this.data[i][this.fields[ip]]);
			textInput.setAttribute("class", "quickEdit")
			this.tdTags[i][ip].appendChild(textInput); 
		}
	}
	this.init();
}

//Editor functions
OmTable.prototype = {
	//Initialize sets up buttons functionality
	init: function(){
		this.buttonEnable();
	},
	
	//SingleEdit save argument is td that is active
	singleEdit: function(e){
		console.log(e);
		var field = e.getAttribute("name");
		var value = e.value;
		console.log(field);
		var postData = {
			"table": this.table,
			"id": this.currentId,
		};
		e.style.display = "none";
		postData[field] = value;
		console.log(postData);
		var thisParent = e.parentNode;
		thisParent.innerText = value;
		thisParent.appendChild(e);
		this._ajaxPost(postData, "../omstart/controllers/save.php");
	},



	buttonEnable: function(){
		var e = this;
		var i = 1;
		//Single Edit + Save by Double Click
		for(i;i < this.tdTags.length;i++) {
			$(this.tdTags[i]).on("dblclick", function() {
				e.currentId = this.parentNode.getAttribute("id");
				e.singleInput = this.getElementsByTagName("input")[0];
				e.singleInput.style.display = "block";
				e.singleInput.style.zIndex = "5";
				$(e.singleInput).trigger("focus");
				e.singleInput.onblur= function(){
					this.style.zIndex = -1;
					e.singleEdit(this);
				}
			});
		}
	},
	/**************Private**************/	
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
}


/****************************Nav Bar Functions****************************/
var omSidebar;
var omTables;
function sidebarSetup() {
	omSidebar = document.getElementById("sidebar");
	$(".xbox").click(sidebarChange);
	$("#showStart").click(sidebarChange);
	
	var omH3 = omSidebar.getElementsByTagName("h3");
	
	$(omH3).each(function() {
		var section = $("#" + this.getAttribute("data")).get(0);
		section.style.display = "none";
		$(this).click(function(){
			if(section.style.display === "none") {
				$(section).show();
			} else {
				$(section).hide();
			}
		})
	});
	
	//Preserve sidebar state
	if(sessionStorage.getItem("sidebar") == "0") {sidebarChange();}
	
	omTables = document.getElementById("OmTables");
	var omTableLinks = omTables.getElementsByTagName("a");
	
	//OmConfigs Div
	var omConfigs = document.createElement("div");
	omConfigs.setAttribute("id", "omConfigs");
	omConfigs.innerHTML = "<h2>OmConfigs</h2>";
	//UserConfigs Div
	var userTables = document.createElement("div");
	userTables.setAttribute("id", "userTables");
	userTables.innerHTML = "<h2>User Tables</h2>";
	
	
	$(omTableLinks).each(function(){
		//Find om_ database configs
		if(this.innerHTML.match(/^\om_/i)) {omConfigs.appendChild(this);} 
		else {userTables.appendChild(this);}	
	});
	omTables.appendChild(userTables);
	omTables.appendChild(omConfigs);
} 

//Side Bar hide + Show
function sidebarChange() {
	if(omSidebar.style.left == "-12em") {
		$(omSidebar).css("left", "0");	
		$(".adminContent").css({"padding-left": "12em"});
		$("#showStart").css({"display": "none"});
		sessionStorage.setItem("sidebar", "1");
	} else {
		$(omSidebar).css("left", "-12em");
		$(".adminContent").css({"padding": "0"});
		$("#showStart").css({"display": "block", "opacity": "1"});
		sessionStorage.setItem("sidebar", "0");
	}
}




/****************************Home Functionality****************************/
var adminContent;

function homeSetup() {	
	adminContent = $(".adminContent");
	var lastLocation = location.href.split("#");
	
	$("a").click(function(){
		if(typeof(this.href.split("#")[1]) != "undefined") {
			location.href = "#" + this.href.split("#")[1];
			homePageChange();
		} 
	});
}

function homePageChange() { 
	var href =  location.href.split("#");
	if(typeof(href[1]) == "undefined") {href[1] = "home";}
	if(href[1] == ""){href[1] = "home";}
	$(adminContent).each(function(){
		if(this.getAttribute("data") == href[1]) {$(this).show();
		} else {$(this).hide();}
	});
}


/****************************New Creator****************************/
var tableCreator;
function tableCreatorSetup() {
	var creatorPanels = $(".newContentWrap");

	//Inital setup of pages to icon
	$(creatorPanels).each(function(){
		var thisData = this.getElementsByTagName("div")[0];
		var creatorIcon = this.getElementsByTagName("a")[0];
		creatorIcon.style.width = "6em";
		creatorIcon.onclick = function(){newPanelChange(this);}	
		$(thisData).hide();
		this.style.width = "6em";
		
	});
	
	//Table Creator
	tableCreator = $(".tableCreate").get(0);
	if(typeof(tableCreator) == "undefined") {return false;}
	var tableCreatorRow = tableCreator.getElementsByTagName("div")[0]; //Use as cloner
	
	//Create new Table
	var tableCreatorSubmit = document.getElementById("newTableCreate");
	tableCreatorSubmit.onclick = function() {
		var creatorRows = tableCreator.getElementsByTagName("div");
		
		var tableTitle = document.getElementById("tableTitle").value;
		
		//No Table title error
		if(tableTitle == "") {
			failBox("Table Title is empty");
			return true;
		}
		
		//Empty data error
		if(typeof(creatorRows[0]) == "undefined") {
			failBox("Table Data is Empty ");
			return true;
		} 
		
		//Create query
		var createQuery = "CREATE TABLE " + tableTitle + "(";
		
		//Get each Data
		for(var i = 0; i < creatorRows.length; i++) {
			if(i !== 0) {createQuery += ",";}
			var textInputs = creatorRows[i].getElementsByTagName("input");
			var typeInput = creatorRows[i].getElementsByTagName("select")[0];	
			
			if(textInputs[0].value == "" || textInputs[1].value == "") {
				failBox("Table Row Data Empty");
			} 
			//Add user input together		
			createQuery += textInputs[0].value + " " + typeInput.value + "(" + parseInt(textInputs[1].value) + ")" + textInputs[2].value;		
		}	
		
		createQuery += ");";
		postData = {"table": createQuery}
		ajaxPost(postData, "../omstart/controllers/new.php");
		console.log(createQuery);
	}
	
	//New Table Row
	var newTableButton = document.getElementById("addDataField");
	newTableButton.onclick = function() {
		var newTableRow = tableCreatorRow.cloneNode(true);
		tableCreator.appendChild(newTableRow);
		tableCreatorButtons();
	}
}

//Change from icon to full page
function newPanelChange(e) {
	if(e.style.width == "6em"){
		e.style.width = "3em";
		var thisParent = e.parentNode;
		thisParent.style.width = "95%"
		var thisData = thisParent.getElementsByTagName("div")[0];
		$(thisData).fadeIn(1000);
	} else {
		e.style.width = "6em";
		var thisParent = e.parentNode;
		thisParent.style.width = "6em"
		var thisData = thisParent.getElementsByTagName("div")[0];
		$(thisData).slideUp(1000);
	}
}

//Delete Button functionality
function tableCreatorButtons() {
	if(typeof(tableCreator) == "undefined") {return false;}
	var creatorRows = tableCreator.getElementsByTagName("div");
	if(typeof(creatorRows) == "undefined") {return true;} 
	
	for(var i = 0; i < creatorRows.length; i++) {
		var deleteButton = creatorRows[i].getElementsByTagName("a")[0];
		deleteButton.onclick = function() {
			var thisParent = this.parentNode;
			console.log(thisParent);
			tableCreator.removeChild(thisParent);
		}		
	}
}


/****************************Animations and UI****************************/

function successBox(type, text){
	$("#successBox span").html(type);
	$("#successBox div").html(text);
	$("#successBox").css({"opacity": 1, "z-index":200});
	setTimeout(function(){$("#successBox").css({"opacity": 0});},7000);
	setTimeout(function(){$("#successBox").css("z-index", -5);},9000);
}

function failBox(text) {
	$("#failBox p").html(text);
	$("#failBox").css({
		"opacity": 1, "z-index":500,
		"right": "10%"	
	});
	setTimeout(function(){$("#failBox").css({
		"opacity": 0,
		"right": "0"	
	});},7000);
	setTimeout(function(){$("#failBox").css("z-index",-5);},9000);
}

function showImport() {
	if(importPanel.style.opacity == 0) {
		$(importPanel).css({"opacity": 1,"z-index": 500,"-webkit-transform": "rotateY(0deg)"});
	} else {
		$(importPanel).css({"opacity": 0,"z-index": -5,"-webkit-transform": "rotateY(90deg)"});
	}
};

//Initializer of entire cms page
function init() {
	var currentLocation = location.href;
	currentLocation = currentLocation.split("?")[0];
	currentLocation = location.href.split("#")[0];
	currentLocation = currentLocation.split("public/")[1];
	currentLocation = currentLocation.split("?")[0];
	
	console.log(currentLocation);
	if(currentLocation == "table.php") {
		//CRUD for table
		var table = location.href.split("table=")[1];
		new OmTable(table, "OmTable");
		//setUpOmTable();
	} else if (currentLocation == "file-editor.php") {

	} else {
		//Home page layout
		homeSetup();
		homePageChange();
		//Table creator functions like delete and create
		tableCreatorSetup();
		tableCreatorButtons();
	}
	sidebarSetup();	
}


/****************************Utility Functions****************************/
function trim(text) {return text.replace(/^\s+|\s+$/g, '');}

function ajaxPost(postData, url) {		
	var request = new XMLHttpRequest();
	request.open("POST", url);
	request.onreadystatechange = function() {
		if(request.readyState === 4 && request.status===200) {
			var responseText = trim(this.response);
			successBox("Post Successful", responseText);
		}
	};
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	postData = encodeFormData(postData);
	request.send(postData);
}

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


$(document).ready(function() {init();});