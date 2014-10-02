<?php

if(isset($_POST["submit"])) {
	print_r($_POST);
	$fh = fopen("config.php", "w");
		
	$server_config = "defined('DB_SERVER') ? null : define('DB_SERVER', '{$_POST['server']}');";
	$user_config = "defined('DB_USER')   ? null : define('DB_USER',   '{$_POST['username']}');";
	$pass_config = "defined('DB_PASS')   ? null : define('DB_PASS',   '{$_POST['password']}');";
	$db_config = "defined('DB_NAME')   ? null : define('DB_NAME',   '{$_POST['database']}');";
	fwrite($fh, "<?php\n");
	fwrite($fh, $server_config."\n");
	fwrite($fh, $user_config."\n");
	fwrite($fh, $pass_config."\n");
	fwrite($fh, $db_config."\n");
	fwrite($fh, "?>");
	fclose($fh);
	chmod("config.php", 0755);
	echo "<br/>Deployment successful";
	return true;
}



?>

<html>
<head>
<title>Omstart Deployment</title>
</head>

<body>

<style type="text/css">

#main {
	width: 30em;
	padding: 10px;
	position: relative;
	margin: 0 auto;
	background: rgb(200,100,200);
	border: 4px solid rgb(0,0,0);
}

h1 { margin: 0;}

#main h2 {
	margin: 2px;
	font-size: 26px;
	text-shadow: 2px 2px 0 rgb(200,200,200);
}

#main table {
	background: rgb(0,0,0);
	color: rgb(255,255,255);
	box-shadow: 4px 4px 0 0 rgb(200,200,200);
	text-align: left;
	margin: 0 auto 10px auto;
	padding: 5px;
	width: 90%;
	position: relative;
}

#main table th {border: 1px solid rgb(200,100,200); padding:2px;}

input {width:100%;}


</style>

<h1>Omstart Deployment</h1>
<hr/>

<div id="main">

	<h2>Set up the Database</h2>

	<form action="deploy.php" method="post">
	<table>
	
		<tr>
			<th>Server Name</th>
			<td><input type="text" name="server" value="localhost"></td>
		</tr>
		
		<tr>
			<th>Username</th>
			<td><input type="text" name="username"></td>
		</tr>
		
		<tr>
			<th>Password</th>
			<td><input type="password" name="password"></td>
		</tr>
		
		<tr>
			<th>Database Name</th>
			<td><input type="text" name="database"></td>
		</tr>		
		
	</table>
	
	<input type="submit" name="submit" value="Setup!">
	</form>
		
</div>

</body>
</html>
