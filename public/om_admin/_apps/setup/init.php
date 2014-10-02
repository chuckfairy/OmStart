<!DOCTYPE html> 
<html>
<head>
<title>Setup OmStart CMS</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="_apps/setup/setup.css">

<script src="_js/core.js" type="text/javascript"></script>
</head>

<body>
<div id="setupWrap">

<div id="setupNav">
	<img src="_assets/logo.png">
	
	<span id="setupLinks">
		<a href="javascript:;" data="home">
			<span>1</span>
			<p>Setup database</p>
		</a>
		
		<a href="javascript:;" data="createuser">
			<span>2</span>
			<p>Create User</p>
		</a>
		
		<a href="javascript:;" data="start">
			<span>3</span>
			<p>Start OmStart</p>
		</a>
	</span>
</div>

<div id="setupPages">

	<div data="home">
		<h2>Create Required Tables</h2>
	
		<p>OmStart requires these tables</p>
		
		<div id="tableConflicts">
		<?php $table_conflicts = "";
		foreach(["om_config", "om_admin", "om_media"] as $table) {
			$table_conflicts.="<p>".$table;
			if($database->validate_table($table)) {
				$table_conflicts.=" <red>Will Overwrite</red>";
			}
			$table_conflicts.="</p>";
		}
		echo $table_conflicts;
		?>
		</div>
		
		<a href="javascript:setupTables();">Setup Tables</a>
	</div>

	<div data="createuser">
		<h2>Create OmStart Admin</h2>
		
		<form action="./" method="post" id="createAdminForm">
			<table>
				<tr>
					<td>Username</td>
					<td><input type="text" name="username"></td>
				</tr>
				
				<tr>
					<td>Email</td>
					<td><input type="text" name="email"></td>
				</tr>
				
				<tr>
					<td>Password</td>
					<td><input type="text" name="password"</td>
				</tr>
			</table>
			
			<button type="button" id="createAdminSubmit">Create Admin</button>
		</form>
	</div>

	<div data="start">
		<h2>Start using OmStart</h2>
		
		<p>To edit files in OmStart you will need to give writeable ownership to apache or whatever server you are using. A simple unix command is this.</p>
		<code>
sudo chgrp -R www-data YOUR_DIRECTORY <br/>#www-data is the server user<br/>
sudo chmod 775 YOUR_DIRECTORY
		</code>
		
		<a href="./" id="startOmstart">Login To Admin</a>
		
		<h2>Help</h2>
		<a href="http://omstart.io/doc">Docs</a>
		<a href="http://omstart.io">Omstart Homepage</a>
		<p>Happy Coding!</p>
	</div>
	
	</div>

</div>

<div id="test"></div>
</div>

<script src="_apps/setup/setup.js"></script>
</body>
</html>