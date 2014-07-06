var ajaxTableData = document.getElementById("ajaxTableData");
console.log(ajaxTableData);
function opacityDown(){$("#successBoxSave").css("opacity", 0);}

$(document).ready(function() {
	
	//var saveI = 1;
	
	function saveBox(){
		$("#successBoxSave").css("opacity", 1);
		timer = setTimeout("opacityDown()",3000);
	};
	
	var ajaxData = ajaxTableData.innerHTML;
	var table = ajaxData.split("/");table = table[0].toString().toLowerCase();
	var id = ajaxData.split("%");id = parseInt(id[1], 10);
	
	
	$("#deleteIcon").click(function() {

		$.ajax({
		  type: "POST",
		  url: "delete.php",
		  data: {
		  	"table": table,
		  	"id": id
		  }
		}).done(function() {
		  	window.location = "./";
		});
		
		console.log(id);
	}),
	
	$("#saveIcon").click(function() {
		
		var dataRows = $("#dataTable").find("tr");
		var postData = {};
		$(dataRows).each(function() {
			var field = $(this).find("th").html();
			var dataValue = this.lastChild.firstChild;
			typeof dataValue.value == "undefined"?dataValue = "":dataValue = dataValue.value.toString();
			postData[field] = dataValue;
		});
		postData["table"] = table;
		console.log(postData);
		
		var ajaxResponse = $.ajax({
		  type: "POST",
		  url: "save.php",
		  data: postData,
		}).done(saveBox());
		
		console.log(ajaxResponse);
	});
})