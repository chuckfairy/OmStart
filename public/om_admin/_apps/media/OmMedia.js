var mediaToggler;
var mediaSplashToggler;

function OmMedia() {
	//Set Up Togglers//
	//Pages
	var mediaKeys = document.getElementById("OmMediaPages").getElementsByTagName("a");
	var mediaDivs = document.getElementById("mediaEditContent").getElementsByTagName("div");
	this.mediaToggler = new PageToggler(mediaKeys, mediaDivs, {keyAttribute: "data"});
	mediaToggler = this.mediaToggler;
	
	//Splash Page Toggle
	var mediaSplashKeys = document.getElementById("mediaSplashKeys")
								  .getElementsByTagName("a");
								  
	var OmMediaSplashPages = document.getElementById("OmMediaSplashPages")
									 .getElementsByTagName("div");
	this.mediaSplashToggler = new PageToggler(mediaSplashKeys, OmMediaSplashPages, 
				{keyAttribute: "data", homeName: "galleries"});
	mediaSplashToggler = this.mediaToggler;
	
	//Used for editing deleteing and more
	this.currentTable;
		
	//HTML Elements
	this.newMediaDiv ="";
	this.newMediaToggle ="";
	this.mediaTable = "";
	this.deleteMediaButton = "";
	this.uploadForm = document.getElementById("uploadPictureForm");
	this.uploadSubmit = document.getElementById("uploadSubmit");
	this.mediaGalleries = document.getElementById("mediaGalleries");
	this.galleryDiv =  document.getElementById("OmMediaGallery");
	this.newGallerySubmit =	document.getElementById("newGallerySubmit");
	this.newGalleryForm = document.getElementById("newGalleryForm");

	//Controller URLS
	this.editUrl = "_apps/media/controllers/edit.php";
	this.deleteUrl = "_apps/media/controllers/delete-media.php";
	this.uploadUrl = "_apps/media/controllers/upload.php";
	this.getMediaUrl = "_apps/media/controllers/get-gallery.php";
	this.newGalleryUrl = "_apps/media/controllers/new-gallery.php";	
	
	//Initialize
	this.init();
}

OmMedia.prototype = {
	
	init: function() {
		this.buttonSetup();	
	},
	
	buttonSetup: function() {
		
		var thisOm = this;
	
		//Upload new media through  form
	    addEvent("click", this.mediaGalleries, function(e){
		    thisOm.getGallery(e);
	    });
	    
	    this.newGallerySubmit.onclick = function(){thisOm.newGallery();}
	    
	    
	},
	
	//Get gallery get-gallery.php grabs tds from gallery table
	getGallery: function(e) {
		//If string gallery is string or grab td element
		if(typeof(e) === "string") {
			galleryTable = e;
		} else {
			var thisElement = e.target;
			//Check if it is a td
			if(thisElement.tagName !== "TD") {return false;}
			var thisParent = thisElement.parentNode;
			var galleryTable = thisParent.getAttribute("data");
		}
		//Set url
		var mediaGet = this.getMediaUrl + "?table=" + galleryTable;
		
		var thisOm = this;
		ajaxGetPage(mediaGet, function(responseText){
			thisOm.currentTable = galleryTable;
			thisOm.loadGallery(responseText);
		});
		
	},
	
	loadGallery: function(responseText) {
		this.galleryDiv.innerHTML = responseText
		this.mediaToggler.setPage("gallery");
		
		this.newMediaDiv = document.getElementById("newMedia");
		this.newMediaToggle = document.getElementById("newMediaToggle");
		this.mediaTable = document.getElementById("mediaTable");;
		
		this.newMediaDiv.style.display = "none";
		
		var thisOm = this;
		this.newMediaToggle.onclick = function() {
			if(thisOm.newMediaDiv.style.display !== "none") {
				thisOm.newMediaDiv.style.display = "none";
			} else {
				thisOm.newMediaDiv.style.display = "block";			
			}
		}			
	},
	
	//New gallery created on splash page
	newGallery: function() {
		var newInputs = this.newGalleryForm.getElementsByTagName("input");
		var postData = {};
		for(var i = 0; i < newInputs.length; i++) {
			var thisInput = newInputs[i];
			var key = thisInput.getAttribute("name");
			var value = thisInput.value;
			postData[key] = value;
		}
		
		var thisOm = this;
		ajaxPost(postData, this.newGalleryUrl, function(responseText){
			console.log(responseText);
			/*thisOm.getGallery(postData["table_name"]); for success*/
		});
	},
	
	loadPictures: function(responseText) {
		console.log(responseText);
	},
	
	//Grab media using get
	editMedia: function(thisId) {
		
		thisId = parseInt(thisId);
		
		var postData = {
			table_name: this.currentTable,
			id: thisId
		}
		
		var thisOm = this;
		ajaxPost(postData, this.editUrl, function(responseText) {
			document.getElementById("editMedia").innerHTML = responseText;
			thisOm.mediaToggler.setPage("editmedia");
		});
	},
	
	//Delete by id and send it to edit
	deleteMedia: function(ids) {
		ajaxPost({
			table_name: this.currentTable,
			deleteMedia: "true",
			ids: ids
		},this.deleteUrl , function(responseText){
			alert(responseText);
		});
	}
}

var MediaObject = new OmMedia();