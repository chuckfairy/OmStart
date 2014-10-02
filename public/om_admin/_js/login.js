//Login Functionality
document.getElementById("loginSubmit").onclick = function(){loginAttempt();}
document.getElementById("loginSubmit").ontapstart = function(){loginAttempt();}

Notifications.close();

function loginAttempt() {
	var loginBox = document.getElementById("loginBox");
	var login_inputs = loginBox.getElementsByTagName("input");
		
	var login_post = {};
	for(var i = 0; i < login_inputs.length; i++) {
		var thisInput = login_inputs[i];
		var thisField = thisInput.getAttribute("name");
		login_post[thisField] = thisInput.value;
	}	
		
	//Attempt login
	var login_url = "../../controllers/core/admin_login.php";
	ajaxPost(login_post, login_url, function(responseText) {
		Notifications.notifi();
		if(trim(responseText) == "true") {
			location.href = "";
		}
	});
}