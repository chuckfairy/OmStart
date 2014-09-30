//Emulator Customization
var emulatorSelect = document.getElementById("emulatorDeviceSelect");
var emulator = document.getElementById("emulator");

//Browser data
var emulatorIframe = document.getElementById("emulatorIframe");
var emulatorBrowserSearch = document.getElementById("emulatorBrowserSearch");
var emulatorSearchSubmit = document.getElementById("emulatorSearchSubmit");

//Landscape Check
var emulatorLandscapeCheck = document.getElementById("emulatorLandscapeCheck");


//Change emulator look
function changeEmulator() {
	var device = emulatorSelect[emulatorSelect.selectedIndex];
	var deviceType = device.innerHTML;
	
	emulator.style["will-change"] = "all";
	
	setTimeout(function(){
		emulator.className = "marvel-device " + deviceType;
	}, 100);
	
	setTimeout(function(){
		emulator.style["will-change"] = null;
	}, 1500);
	
	emulatorLandscapeCheck.checked = false;
}

//Go to landscape add landscape class
function goLandscape() {
	if(emulatorLandscapeCheck.checked) {
		emulator.className = emulator.className + " landscape";
	} else {
		changeEmulator();
	}
}

//Browser search add url to src of iframe
function emulatorSearch() {
	var url = emulatorBrowserSearch.value;
	emulatorIframe.src = url;
}

//open url into iframe
function emulatorOpen(url) {
	url = HOSTHOME + url;
	emulatorIframe.src = url;
	emulatorBox.show();
}

//Button Setup
emulatorSelect.onchange = function(){changeEmulator();}
emulatorSearchSubmit.onclick = function(){emulatorSearch();}
emulatorLandscapeCheck.onchange = function(){goLandscape();}

//INIT
changeEmulator();
emulatorSearch();