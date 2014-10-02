//Setup Page Navigation toggler
var setupLinksWrap = document.getElementById("setupLinks");
var setupLinks = setupLinksWrap.getElementsByTagName("a");

var setupWrap = document.getElementById("setupPages");
var setupDivs = setupWrap.getElementsByTagName("div");

var testOuput = document.getElementById("test");

var setupToggler = new PageToggler(setupLinks, setupDivs, {keyAttribute: "data"});

//Setup tables action
function setupTables() {
	ajaxGetPage("../../controllers/core/setupdb.php", function(responseText) {
		testOuput.innerHTML = responseText;
	});
}

//Create first user action 
var createAdminForm = document.getElementById("createAdminForm");
var createAdminSubmit = document.getElementById("createAdminSubmit");
var adminCreateUrl = "../../controllers/core/admin_create.php";

createAdminSubmit.onclick = function() {
	var inputs = createAdminForm.getElementsByTagName("input");
	var postData = {};
	
	for(var i = 0; i < inputs.length; i++) {
		var thisInput = inputs[i];
		var thisField = thisInput.getAttribute("name");
		postData[thisField] = thisInput.value;	
	}
	
	ajaxPost(postData, adminCreateUrl, function(responseText) {
		testOuput.innerHTML = (responseText);
	});
}


