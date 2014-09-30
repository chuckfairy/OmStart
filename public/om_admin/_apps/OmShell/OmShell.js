var execUrl = "_apps/OmShell/controllers/exec.php";
var cwd = SRC;
var currentPrompt;

var omshellWrap = document.getElementById("omshellWrap");
var omshellLinks = omshellWrap.getElementsByTagName("a");
var omshellPages = omshellWrap.getElementsByTagName("div");
omshellToggler = new PageToggler(omshellLinks, omshellPages, {keyAttribute: "data"});


//Core shell prompts
function OmShell() {}

OmShell.prototype.createPrompt = function(shell) {
	var prompt = document.createElement("div");
	prompt.className = "prompt";
	prompt.innerHTML = "<span>root@omstart</span>";
	var promptInput = document.createElement("input");
	promptInput.type = "text";
	
	var thisOm = this;
	promptInput.onfocus = function() {
		promptInput.onkeypress = function(e) {
			if(typeof e == 'undefined' && window.event) { e = window.event; }
		    if(e.keyCode === 13) {
		    	if(currentPrompt.ThisInput.value === "clear") {
			    	shell.clear();
				}
				
				//No special commands so exec
		    	shell.exec();
		    }
		}		
	}
	
	promptInput.onblur = function() {
		promptInput.onkeypress = null;
	}
	
	prompt.appendChild(promptInput);
	prompt.input = promptInput;
	return prompt;
}

//SQL Shell used for connecting to database and executing
function SQLSHELL() {
	
	//Elements
	this.window = document.getElementById("SQL_SHELL");

	//Controller urls
	this.execUrl = "_apps/OmShell/controllers/sql.php";
	
	//Initialize
	this.init();
}

SQLSHELL.prototype = {
	
	init: function() {
		this.core = new OmShell();
		this.prompt = this.core.createPrompt(this);
		this.window.appendChild(this.prompt);
	},
	
	exec: function() {
		var exec = this.prompt.input.value;
		var postData = {exec: exec};
		var thisOm = this;
		ajaxPost(postData, thisOm.execUrl, function(responseText) {
			thisOm.output(responseText);
		});
	},
	
	clear: function() {
		this.window.innerHTML = "";
		this.prompt = this.core.createPrompt(this);
		this.prompt.input.focus();
	},
	
	output: function(responseText) {
		var output = document.createElement("div");
    	output.innerText = responseText;
    	this.window.appendChild(output);
    	
    	//Close old prompt
    	this.prompt.input.blur();
    	
    	//Setupt new prompt
    	var newprompt = this.core.createPrompt(this);
    	this.window.appendChild(newprompt);
    	this.prompt = newprompt;
    	this.prompt.input.focus();
	}
}

var OmShellSQL = new SQLSHELL();



var SH_SHELL = document.getElementById("SH_SHELL");

function createPrompt() {
	var prompt = document.createElement("div");
	prompt.className = "prompt";
	prompt.innerHTML = "<span>root@omstart</span>";
	var promptInput = document.createElement("input");
	promptInput.type = "text";
	
	promptInput.onfocus = function() {
		promptInput.onkeypress = function(e) {
			if (typeof e == 'undefined' && window.event) { e = window.event; }
		    if (e.keyCode === 13) {
		    	shellExec();
		    }
		}		
	}
	
	promptInput.onblur = function() {
		promptInput.onkeypress = null;
	}
	
	prompt.appendChild(promptInput);
	prompt.ThisInput = promptInput;
	return prompt;
}

function shellExec() {
	//Clear Command
	if(currentPrompt.ThisInput.value === "clear") {
    	SH_SHELL.innerHTML = "";
    	var prompt = createPrompt();
    	SH_SHELL.appendChild(prompt);
    	prompt.ThisInput.focus();
    	currentPrompt.blur();
    	currentPrompt = prompt;
    	return true;
	}

    var postData = {
    	exec: currentPrompt.ThisInput.value,
    	cwd: cwd.toString()	
    };
    
    console.log(postData);
       
    ajaxPost(postData, execUrl, function(responseText) {
    	
    	var json_response = JSON.parse(responseText);
    	
    	var output = document.createElement("div");
    	output.innerText = json_response.output;
    	SH_SHELL.appendChild(output);
    	
    	cwd = trim(json_response.cwd);
    	
    	var prompt = createPrompt();
    	SH_SHELL.appendChild(prompt);
    	prompt.ThisInput.focus();
    	currentPrompt.ThisInput.blur();
    	currentPrompt = prompt;
    });
}

var startPrompt = createPrompt();
SH_SHELL.appendChild(startPrompt);
currentPrompt = startPrompt;