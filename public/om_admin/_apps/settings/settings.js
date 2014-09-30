var settingsWrap = document.getElementById("settingsWrap");

var settingsNavigation = document.getElementById("settingsNavigation");
var settingsKeys = settingsNavigation.getElementsByTagName("a");
var settingsPagesWrap = document.getElementById("settingsPagesWrap");
var settingsPages = settingsPagesWrap.getElementsByTagName("div");

var settingsToggler = new PageToggler(settingsKeys, settingsPages, {keyAttribute:"data"});

var settingsAni = new animateHTML(settingsWrap, {
	classOn: "settingsWrapOn",
	classOff: "settingsWrapOff",
	animationTime: 1000,
});

