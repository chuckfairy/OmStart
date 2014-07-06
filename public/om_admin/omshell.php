<?php require_once("../../omstart/initialize.php");?>

<?php 

if(isset($_POST["query_submit"])) {
	$result = $database->query($_POST["query"]);
	print_r($result);
	unset($_POST["query_submit"]);
}
?>

<html>
<head>
<title>OmShell</title>
<link href="_css/omshell.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>

<body>

<h1>OmShell</h1>

<a href="./">Back</a>

<form action="omshell.php" method="post">
<pre id="shell">Query here</pre>
<div id="shellafter"></div>

<hr/>
<input type="hidden" name="query" id="inputquery">
<button type="submit" name="query_submit" id="submit">Submit Query</button>
</form>

<?php

//$connection = ssh2_connect("127.0.0.1", 22);
//ssh2_auth_password($connection, 'charlesabeling', 'poop1234');

//$stream = ssh2_exec($connection, " ls -la");
//stream_set_blocking($stream, true); 
//echo "<h3>".stream_get_contents($stream)."</h3>"; 


//$stream = ssh2_shell($connection, 'vt102', null, 80, 24, SSH2_TERM_UNIT_CHARS);

?>






<script src="_js/ace-src/ace.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    
var editor = ace.edit("shell");
editor.setTheme("ace/theme/terminal");
editor.session.setMode("ace/mode/sql");

$(document).ready(function() {
	$("#submit").hover(function() {
		var query = editor.getValue();
		var inputquery = document.getElementById("inputquery");
		inputquery.value = query;
		console.log($("#inputquery"));
	})
});
</script>
</body>
</html>