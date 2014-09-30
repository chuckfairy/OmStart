/****************************Notifi****************************/
//Notifi takes argument of id or html object for the wrapper.
//It will create its own div for data
function Notifi(idOrObject) {

	//Check if object
	if(typeof(idOrObject) === "object") {this.wrapper = idOrObject;}
	//Check if id get from DOM
	else if(typeof(idOrObject) === "string") {
		this.wrapper = document.getElementById(idOrObject);
	} 
	//No data set
	else {
		console.log("No context id or object set");
		return false;
	}
	
	//HTML elements
	this.dataDiv = document.createElement("div");
	
	//Controller urls
	this.setUrl = "_apps/notifi/controllers/set-session.php";
	this.getUrl = "_apps/notifi/controllers/get-session.php";
	
	this.init();
}
//API functions
Notifi.prototype = {
	init: function() {
		if(isset(this.wrapper.childNodes[0])) {this.show();} 
		else {this.close();}
		
		this.setUpNotifi();
	},
	
	//Initial setup make sure it doesn't have any messages
	setUpNotifi: function() {
		
		
		this.dataDiv.innerHTML = this.wrapper.innerHTML;
		this.wrapper.innerHTML = "";
		this.wrapper.appendChild(this.dataDiv);
		
		//Create Close
		this.closeButton = document.createElement("a");
		this.closeButton.className = "notifiClose";
		this.closeButton.innerHTML = "X";
		this.wrapper.appendChild(this.closeButton);
		var thisOm = this;
		this.closeButton.onclick = function() {
			thisOm.close();
		}
	},
	
	show: function() {
		this.wrapper.style.display = "block";
		this.wrapper.style.opacity = "0";
		var thisOm = this;
		
		setTimeout(function() {
			thisOm.wrapper.style.top = "15px";
			thisOm.wrapper.style.opacity = "1";
		}, 100)
	},
	
	close: function() {
		this.wrapper.style.top = "0";
		this.wrapper.style.opacity = "0";
		
		var thisOm = this;
		setTimeout(function() {
			thisOm.wrapper.style.display = "none";
		}, 750)
	},
	
	get: function(responseText) {
		this.dataDiv.innerHTML = responseText;	
		this.show();
	},
	
	set: function(setText, show) {
		this.dataDiv.innerHTML = setText;
		if(show) {this.show();}
	},
	
	setSession: function(setText) {
		var thisOm = this;
		ajaxPost({message: setText}, this.setUrl);
	},
	
	notifi: function() {
		var thisOm = this;
		ajaxGetPage(this.getUrl, function(responseText){
			thisOm.get(responseText);
		});
	}
}

var Notifications = new Notifi("Notifi");