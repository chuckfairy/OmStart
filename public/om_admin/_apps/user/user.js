var createAdminForm = document.getElementById("createAdminForm");
var createAdminSubmit = document.getElementById("createAdminSubmit");
var adminCreateUrl = "_apps/user/controllers/create.php";

createAdminSubmit.onclick = function() {
	var inputs = createAdminForm.getElementsByTagName("input");
	var postData = {};
	
	for(var i = 0; i < inputs.length; i++) {
		var thisInput = inputs[i];
		var thisField = thisInput.getAttribute("name");
		postData[thisField] = thisInput.value;	
	}
	
	ajaxPost(postData, adminCreateUrl, function(responseText) {
		console.log(responseText);
		
		
	});
}