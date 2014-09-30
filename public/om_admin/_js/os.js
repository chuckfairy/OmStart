//Create OmBoxes
var omniBox = new OmBox("omniBox", {
	title: "Omni",
	theme: "wannaBe2"
});

var fileEditorBox = new OmBox("fileEditorBox", {
	title: "File Editor",
	theme: "wannaBe2"
});

var mediaBox = new OmBox("mediaBox", {
	title: "OmMedia",
	theme: "wannaBe2"
});

var databaseBox = new OmBox("databaseBox", {
	title: "Database Editor",
	theme: "wannaBe2"
});

var userBox = new OmBox("userBox", {
	title: "User Config",
	theme: "wannaBe2"
});

var omshellBox = new OmBox("omshellBox", {
	title: "OmShell",
	theme: "wannaBe2"
});

var settingsBox = new OmBox("settingsBox", {
	title: "Settings",
	theme: "wannaBe2"
});

var emulatorBox = new OmBox("emulatorBox", {
	title: "Mobile Emulator",
	theme: "wannaBe2"
});

var gravitBox = new OmBox("gravitBox", {
	title: "Gravit",
	theme: "wannaBe2"
});

//Logout
function logout() {
	ajaxPost({}, "../../controllers/core/admin_logout.php", function() {
		Notifications.notifi();
		location.href = "";
	});
}

//Views of Desktop and smaller screens
var OmStartView = "desktop";

var OmBoxTitle = document.getElementById("OmBoxTitle");
var mobileHeader = document.getElementById("mobileHeader");
var desktopApplications = document.getElementById("desktopApplications");
var applicationsWrap = document.getElementById("applicationsWrap");
var OmBoxesWrap = document.getElementById("OmBoxes");
var applicationsAni = new animateHTML(applicationsWrap, {
	classOn: "appsOn", 
	classOff: "appsOff",
	animationTime: 1000
});

//Set up header buttons
document.getElementById("appsIconDiv").onclick = function(){mobileApplications();}

desktopApplications.mobileShow = function() {
	desktopApplications.style.display = "block";	
}

//Mobile Pages and Functions
function mobileSetup() {
	OmBoxTitle.style.display = "none";
	mobileHeader.style.display = "block";
	
	//Change Desktop
	desktopApplications.className = "appsMobileMenu";
	document.body.className = "mobileBody";
	
	OmBoxesWrap.style.top = "2em";
	applicationsAni.clear();	
	applicationsAni.animator.style.zIndex = "100";
	
/* 	desktopApplications.style.paddingTop = "2.1em"; */

	addEvent("click", applicationsWrap, mobileApplications);
	//mobileApplications();	
}
//Mobile Settings popup
var appSettingsPop = document.getElementById("appSettingsPop");
var appsSettings = document.getElementById("appsSettings");
var settingsAnimator = new animateHTML(appSettingsPop, {
	classOn: "settingsPopOn",
	classOff: "settingsPopOff",
	animationTime: 750
});
appsSettings.onclick = function() {settingsAnimator.toggle();}


function mobileApplications() {
	if(applicationsWrap.style.display === "none") {
		applicationsAni.show();
		return true;	
	} else {
		applicationsAni.hide();
		return true;
	}
}

function desktopSetup() {
	OmBoxTitle.style.display = "block";
	mobileHeader.style.display = "none";
	desktopApplications.style.display = "block";
	
	if(OmStartView === "mobile") {
		applicationsWrap.removeEventListener("click", mobileApplications);		
	}
	OmBoxesWrap.style.left = null;
	applicationsAni.animator.style.zIndex = null;
	applicationsAni.on();
		
	desktopApplications.className = "desktopApps";
	document.body.className = "desktopBody";
}

function omstartResize() {
	if(window.innerWidth < 700 && OmStartView === "desktop") {
		mobileSetup();
		OmStartView = "mobile";
	} else if(window.innerWidth > 700) {
		desktopSetup();
		OmStartView = "desktop";		
	}
}

//getStyle(".desktopBody");


window.onresize = function() {omstartResize();}
window.onload = function(){omstartResize();}

//Clock
function OmClock(clockId) {
	this.thisClock = clockId;
	this.el = document.getElementById(this.thisClock);
    
    this.init();
}

OmClock.prototype = {
	init: function(){
		var that = this;
		this.t = setInterval(function(){
			that.update();	
		},6000);
		//Initial set
		this.update();
	},
	
	update: function() {
		var today=new Date();
		var h=today.getHours();
		var m=today.getMinutes();
		m = checkTime(m);
		this.el.innerHTML = h + ":" + m;	
	}
}


function checkTime(i) {
    if (i<10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

var OmClock1 = new OmClock("OmClockOne");
var OmClock2 = new OmClock("OmClockTwo");


//Init
dropDownSetup();