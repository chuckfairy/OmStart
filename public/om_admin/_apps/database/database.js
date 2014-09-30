var databaseEditorWrap = document.getElementById("databaseEditorWrap");
var tableLinks = document.getElementById("databaseTableLinks");
var databaseEditorDiv = document.getElementById("databaseEditorDiv");
var deleteTablesButton;

var databaseToggleLinks = document.getElementById("databasePagesLinks").getElementsByTagName("a");

var databaseToggleDivs = databaseEditorWrap.getElementsByTagName("div");
var databasePageToggler = new PageToggler(databaseToggleLinks, databaseToggleDivs, {keyAttribute: "data"});
var currentTable ="";

function getTable(tableLink) {
	
	if(typeof(tableLink) == "string") {currentTable = tableLink;}
	else {currentTable = tableLink.target.innerHTML;}
	
	if(!isset(currentTable)) {return false;}
	var url = "_apps/database/controllers/get-table.php?table=" +	currentTable;
	ajaxGetPage(url, loadTable);
}

function loadTable(responseText) {
	databaseEditorDiv.innerHTML = responseText;
	databasePageToggler.setPage("editor");
	var omtableInstance = new OmTable(currentTable, "OmTable");
	
	//Table insert and search functionality
	var tableActionKeys = 
			document.getElementById("tableActionKeys").getElementsByTagName("a");
	var tableActionsDivs = 
			document.getElementById("tableActions").getElementsByTagName("div");
	var tableActionToggler = 
			new PageToggler(tableActionKeys, tableActionsDivs, {keyAttribute:"data"});
	loadInsert();
	
	deleteTablesButton = document.getElementById("deleteTablesButton");
	deleteTablesButton.onclick = function() {
		omtableInstance.deleteSetup();
	}
}

var insertUrl = "../../controllers/core/save.php";
var databaseInsertForm;

function loadInsert() {
	databaseInsertForm = document.getElementById("databaseInsertForm");
	
	var tableInsertSubmit = document.getElementById("tableInsertSubmit");
	tableInsertSubmit.onclick = function(){databaseInsert();}
}

function databaseInsert() {
	
	var inputs = databaseInsertForm.getElementsByTagName('input');
	var postData = {table_name: currentTable};
	
	for (i = 0; i < inputs.length; ++i) {
	    // deal with inputs[index] element.
	    var fieldValue = inputs[i].value;
	    var fieldName = inputs[i].name;
	    if(fieldValue ==="") {continue;}
	    postData[fieldName] = fieldValue;
	} 
	console.log(postData);
	
	ajaxPost(postData, insertUrl, function(){
		getTable(currentTable);
	});
}






addEvent("click", tableLinks, function(e) {getTable(e);});


/****************************TABLE EDITOR****************************/
function OmTable(table, tableId) {
	//Variables for table info
	this.table = table;
	this.tableObject = document.getElementById(tableId);
	this.rows = this.tableObject.getElementsByTagName("tr");
	this.fields = [];
	this.data   = [];
	this.tdTags = [];
	
	//Controller Urls
	this.deleteUrl = "_apps/database/controllers/delete-records.php";	
		
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
		this.deleteSetupOn = false;
	},
	
	//SingleEdit save argument is td that is active
	singleEdit: function(e){
		var field = e.getAttribute("name");
		var value = e.value;
		console.log(field);
		var postData = {
			"table_name": this.table,
			"id": this.currentId,
		};
		e.style.display = "none";
		postData[field] = value;
		console.log(postData);
		var thisParent = e.parentNode;
		thisParent.innerText = value;
		thisParent.appendChild(e);
		ajaxPost(postData, "../../controllers/core/save.php");
	},

	singleEditSetup: function(e) {
		this.currentId = e.target.parentNode.getAttribute("data");
		e.singleInput = e.target.getElementsByTagName("input")[0];
		e.singleInput.style.display = "block";
		e.singleInput.style.zIndex = "5";
		e.singleInput.focus();
		
		var thisOm = this;
		e.singleInput.onblur= function(){
			this.style.zIndex = -1;
			thisOm.singleEdit(this);
		}
	},
	
	deleteSetup: function() {
		if(this.deleteSetupOn) {return true;}
		
		var deleteSubmit = document.createElement("a");
		deleteSubmit.className = "recordDeleteSubmit"; 
		deleteSubmit.innerHTML = "x";
		deleteSubmit.href = "javascript:;";
		
		for(var i=0; i < this.rows.length; i++) {
			var thisRow = this.rows[i];
			var deleteTd = document.createElement("td");
			if(i !== 0) {
				var radioButton = document.createElement("input");
				radioButton.type = "checkbox";
				radioButton.setAttribute("data", thisRow.getAttribute("data"));
				deleteTd.appendChild(radioButton);
			} else {	
				deleteTd.appendChild(deleteSubmit);
			}
			
			thisRow.insertBefore(deleteTd, thisRow.firstChild);
			this.deleteSetupOn = true;
		}
		
		var thisOm = this;
		deleteSubmit.onclick = function() {thisOm.deleteTables();}
	},
	
	deleteTables: function() {
		
		var deleteChecks = [];
		for(var i=0; i < this.rows.length; i++) {
			if(i === 0) {continue;} //No first row
			
			var thisRow = this.rows[i];
			var deleteTd = thisRow.firstChild;
			var deleteButton = deleteTd.getElementsByTagName("input")[0];
			
			if(deleteButton.checked) {
				var deleteId = deleteButton.getAttribute("data");
				deleteChecks.push(deleteId);
			}			
		}
				
		var postData = {
			table_name: this.table,
			deleteArray: deleteChecks
		}
		
		var thisOm = this;
		ajaxPost(postData, this.deleteUrl, function(responseText) {
			getTable(thisOm.table);
		});
	},

	buttonEnable: function(){
		var thisOm = this;
		//Single Edit + Save by Double Click
		var singleEditSetup = this.singleEditSetup;
		var tds = this.tdTags;
		
		addEvent("dblclick", thisOm.tableObject, function(e){
			thisOm.singleEditSetup(e);
		});
	}
}

/****************************New Creator****************************/
var tableCreator;
function tableCreatorSetup() {
	
	//Table Creator
	tableCreator = document.getElementById("tableCreate");
	var tableCreatorRow = tableCreator.getElementsByTagName("div")[0]; //Use as cloner
	
	//Create new Table
	var tableCreatorSubmit = document.getElementById("newTableCreate");
	tableCreatorSubmit.onclick = function() {
		var creatorRows = tableCreator.getElementsByTagName("div");
		
		var tableTitle = document.getElementById("tableTitle").value;
		
		//No Table title error
		if(tableTitle == "") {
			console.log("Table Title is empty");
			return true;
		}
		
		//Empty data error
		if(typeof(creatorRows[0]) == "undefined") {
			console.log("Table Data is Empty ");
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
				console.log("Table Row Data Empty");
			} 
			//Add user input together		
			createQuery += textInputs[0].value + " " + typeInput.value + "(" + parseInt(textInputs[1].value) + ")" + textInputs[2].value;		
		}	
		
		createQuery += ");";
		postData = {create_query: createQuery}
		ajaxPost(postData, "../../controllers/core/newtable.php");
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

//Delete Button functionality
function tableCreatorButtons() {
	var creatorRows = tableCreator.getElementsByTagName("div");
	
	for(var i = 0; i < creatorRows.length; i++) {
		var deleteButton = creatorRows[i].getElementsByTagName("a")[0];
		deleteButton.onclick = function() {
			var thisParent = this.parentNode;
			console.log(thisParent);
			tableCreator.removeChild(thisParent);
		}		
	}
}

//Toggle display
var tableCreatorWrap = document.getElementById("tableCreatorWrap");
function tableCreatorToggle() {
	if(tableCreatorWrap.style.display === "none") {
		tableCreatorWrap.style.display = "block";
	} else {
		tableCreatorWrap.style.display = "none";
	}
}

tableCreatorSetup();

tableCreatorWrap.style.display = "none";
document.getElementById("newTableToggle").onclick = function(){tableCreatorToggle();}


