/****************************Utils****************************/
function isset(value) {
	if(typeof(value) == "undefined") {return false;}
	else if(value == null) {return false;}
	else {return true;}
}

function trim(text) {return text.replace(/^\s+|\s+$/g, '');}

function in_array(needle, haystack, argStrict) {

	var key = '',
	strict = !! argStrict;
	if (strict) {
		for(key in haystack) {if (haystack[key] === needle) {return true;}}
	} else {
		for(key in haystack) {if (haystack[key] == needle) {return true;}}
	}
	return false;
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

function htmlDecode(input){
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

function addEvent(evnt, elem, func) {
	if (elem.addEventListener) {
		elem.addEventListener(evnt,func,false);
	} else if (elem.attachEvent) { 
			elem.attachEvent("on"+evnt, func);
	} 
	else { elem[evnt] = func;}
}

//Get Elements By Class Name
function GEBCN(cn){
    if(document.getElementsByClassName) // Returns NodeList here
        return document.getElementsByClassName(cn);

    cn = cn.replace(/ *$/, '');

    if(document.querySelectorAll) // Returns NodeList here
        return document.querySelectorAll((' ' + cn).replace(/ +/g, '.'));

    cn = cn.replace(/^ */, '');

    var classes = cn.split(/ +/), clength = classes.length;
    var els = document.getElementsByTagName('*'), elength = els.length;
    var results = [];
    var i, j, match;

    for(i = 0; i < elength; i++){
        match = true;
        for(j = clength; j--;)
            if(!RegExp(' ' + classes[j] + ' ').test(' ' + els[i].className + ' '))
                match = false;
        if(match)
            results.push(els[i]);
    }

    // Returns Array here
    return results;
}

function getStyle(className) {
    var classes = document.styleSheets[0].rules || document.styleSheets[0].cssRules;
    var classStyle;
    for (var x = 0; x < classes.length; x++) {
        if (classes[x].selectorText == className) {
            classStyle = classes[x].cssText || classes[x].style.cssText;
        }
    }
    return classStyle;
}

function animateHTML(animator, customize) {
	if(typeof(animator) === "string") {this.animator = document.getElementById(animator);}
	else if(typeof(animator) === "object") {
		this.animator = animator;
	} else {
		console.log("Not object or string for animator.");
		return false;	
	}
	
	customize = customize || {};
	
	//Animation Data
	this.classOn = customize.classOn || "";
	this.classOff = customize.classOff || "";
	this.aniTime = customize.animationTime || 750;
	this.showDefault = customize.showDefault || false;
	//Wrapper Data
	this.isOn = false;
		
	this.init();
}

animateHTML.prototype = {
	init: function() {
		if(!this.showDefault) {this.clear();}	
		else {
			this.isOn = true;
			this.animator.className = this.classOn;	
			this.animator.style.display = "block";
		}
	},
	
	show: function() {
		if(this.isOn) {return true;}
		
		this.animator.style.display = "block";
		this.animator.style.opacity = "0";
		
		var thisOm = this;
		setTimeout(function() {
			thisOm.animator.className = thisOm.classOn;
			thisOm.animator.style.opacity = "1";
		}, 100);
		this.isOn = true;
	},
	
	on: function() {
		this.animator.className = this.classOn;
		this.animator.style.display = "block";
		this.animator.style.opacity = "1";
		this.isOn = true;
	},
	
	hide: function() {
		if(!this.isOn) {return true;}
		
		this.animator.className = this.classOff;
		this.animator.style.opacity = "0";
		
		var thisOm = this;
		setTimeout(function() {
			thisOm.animator.style.display = "none";
		}, thisOm.aniTime);
		this.isOn = false;
	},
	
	clear: function() {
		this.animator.style.display = "none";
		this.animator.className = this.classOff;
		this.isOn = false;
	},
	
	toggle: function() {
		if(this.isOn) {
			this.hide();
		} else {
			this.show();
		}
	}
}


/****************************Ajax Functions****************************/

function ajaxGetPage(page, callback) {
	page = page || "";
	
	var request = new XMLHttpRequest();
	request.open("GET", page, true);	
	request.setRequestHeader("Content-Type", "application/html");
	//request.responseType = "text";
	request.onreadystatechange = function() {
		if(request.readyState === 4 && request.status===200) {
			var responseText = trim(this.response);
			callback(responseText);
		}
	}
	request.send(null);
}


function ajaxPost(postData, url, callback) {	
	var thisOm = this;
	var request = new XMLHttpRequest();
	request.open("POST", url);
	//request.responseType = "document";
	request.onreadystatechange = function() {
		if(request.readyState === 4 && request.status===200) {
			console.dir(this);
			var responseText = trim(this.response);
			if(typeof(callback) != "undefined") {
				callback(responseText);
			}
		}
	};
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	postData = "json_data=" + encodeURIComponent(JSON.stringify(postData));
	request.send(postData);
}

/****************************App Drop Down****************************/
function dropDownSetup() {
	var dropDowns = document.getElementsByClassName("appContext");
	for(var i = 0; i < dropDowns.length; i++) {
		var thisDrop = dropDowns[i];
		dropEnable(thisDrop);
	}
}

function dropEnable(thisElement) {
	var topLink = thisElement.getElementsByTagName("a")[0];
	
	var insideSpan = thisElement.getElementsByTagName("span")[0];
	insideSpan.style.display = "none";
	
	topLink.onclick = function something() {
		if(insideSpan.style.display === "none") {
			topLink.className = "selected";
			insideSpan.style.display = "block";			
		} else {
			topLink.className = "";
			insideSpan.style.display = "none";
		}
	}
}

//Context Menu
function OmContext(idOrObject) {
	
	//Check if object
	if(typeof(idOrObject) === "object") {this.contextBox = idOrObject;}
	//Check if id get from DOM
	else if(typeof(idOrObject) === "string") {
		this.contextBox = document.getElementById(idOrObject);
	} 
	//No data set
	else {
		console.log("No context id or object set");
		return false;
	}
	
	//Elements
	this.contextParent = this.contextBox.parentNode;
	
	this.init();
}
//Prototype
OmContext.prototype = {
	
	init:function() {
		this.setUpMenu();
		return this;
	},
	
	setUpMenu: function() {
		
		
	},
	
	//Show on give context
	show:function(e) {
		this.contextBox.style.display = "block";
		var mousePosition = getMouseXY(e);
		
		this.contextBox.style.top = (mousePosition["y"] / 1.6) + "px";
		this.contextBox.style.left = (mousePosition["x"] / 1.6) + "px";
		
		var thisOm = this;
		setTimeout(function(){
			
			thisOm.contextParent.onclick = function() {
				thisOm.contextBox.style.display = "none";	
				thisOm.contextParent.onclick = null;
			}
		}, 300);
	},
	
	//Adds context event to element and
	//returns data from Anchor tag for the callback
	addContext: function(contextElement, callback) {
		var thisOm = this;
		var contextE
		contextElement.oncontextmenu = function(e) {
			e.preventDefault();
			thisOm.show(e);
			contextE = e;
		}
		
		//Add click for context
		addEvent("click", thisOm.contextBox, function(e) {
			if(e.target.tagName === "A") {
				var eData = e.target.getAttribute("data");
				callback(eData, contextE);
			}		
		});
	}
}



/****************************Page Toggler****************************/
//Use for page tabs and loading
//Expects links to have data or href to find divs
//Divs finds all with matching key as data attribute
function PageToggler(keys, toggleDivs, customize) {
	
	//Check customizeable interface
	//classes are for display, attribute if for grabbing
	if(typeof(customize) == "undefined") {customize = false;}
	this.classOn  = customize.classOn  || "opacityOn";
	this.classOff = customize.classOff || "opacityOff";
	this.keyAttribute = customize.keyAttribute || "href";
	this.homeName = customize.homeName || "home";
	
	if(!isset(keys)) {return false;}
	if(!isset(toggleDivs)) {return false;}
	
	this.keys = keys;	
	keysArray=[];
	for(var i=0; i<this.keys.length; i++) {
		keyData = this.keys[i].getAttribute(this.keyAttribute);
		if(this.keyAttribute == "href") {
			keyData = keyData.split("#")[1];	
		}
		keysArray.push(keyData);
	}
	
	var pages = [];
	for(var i=0; i < toggleDivs.length; i++) {
		var page = toggleDivs[i];
		var pageData = page.getAttribute("data");
		if(in_array(pageData, keysArray)) {	
			pages.push(page);
		}
	}
	
	this.toggleDivs = pages;
	this.init();
}

PageToggler.prototype = {
	init: function() {
		var t = this;
		this.clear();
		this.buttonEnable();
		if(this.keyAttribute !== "href") {
			this.setPage(this.homeName);
		} else {
			this.setPage(this.homeName);
			var hashChange = t.hrefLoad;
			hashChange = hashChange.bind(t);
		}
	},


	buttonEnable: function() {
		var thisToggler = this;
		
		for(var i=0; i<this.keys.length;i++) {
			this.keys[i].onclick = function(){
				thisToggler.show(this);
			}
		}
	},

	clear: function() {
		for(var i=0; i<this.toggleDivs.length;i++) {
			this.toggleDivs[i].removeAttribute("class", this.classOn);
			this.toggleDivs[i].setAttribute("class", this.classOff);
		}
		
		for(var i=0; i<this.keys.length;i++) {
			this.keys[i].className = "";
		}
	},
	
	show: function(keyA) {
		this.clear();
		var keyData = keyA.getAttribute(this.keyAttribute);
		if(this.keyAttribute == "href") {
			keyData = keyData.split("#")[1];
		}
		
		for(var i=0; i<this.toggleDivs.length;i++) {
			if(this.toggleDivs[i].getAttribute("data") === keyData) {
				this.toggleDivs[i].removeAttribute("class", this.classOff);
				this.toggleDivs[i].setAttribute("class", this.classOn);
			}
		}
		keyA.className = "selected";
	},
	
	setPage: function(keyData) {
		this.clear();
		for(var i=0; i<this.toggleDivs.length;i++) {
			if(this.toggleDivs[i].getAttribute("data") === keyData) {
				this.toggleDivs[i].removeAttribute("class", this.classOff);
				this.toggleDivs[i].setAttribute("class", this.classOn);
			}
		}
		for(var i = 0; i < this.keys.length; i++) {
			if(this.keyAttribute === "href") {
				keyRef = this.keys[i].href.split("#")[1];
			} else{
				keyRef = this.keys[i].getAttribute(this.keyAttribute);
			}
			
			if(keyRef === keyData) {this.keys[i].className = "selected";}
		}
	},
	
	addPage: function(toggleDiv) {
		this.toggleDivs.push(toggleDiv);
	},
	
	//Load a page by href
	hrefLoad: function(hash) {
		
		var hash = location.href.split("#")[1] || "";
		//Find key
		if(hash == "") {hash = this.homeName;}
		for(var i = 0; i < this.toggleDivs.length;i++) {
			var keyData = this.toggleDivs[i].getAttribute("data");
			if(keyData == hash) {
				this.setPage(keyData);
				return true;
			}
		}	
	}
}