<?php require_once("../../omstart/initialize.php");?>

<?php 
//REQUESTS

if(isset($_POST["admincreate"])) {
	$adminTable = new TableObject("om_admin");
	$adminTable->username = $_POST["username"];
	$adminTable->hash = password_encrypt($_POST["password"]);
	$adminTable->email    = $_POST["email"];
	$adminTable->save();
}


?>


<?php include_once(OMADMIN."_layout".DS."cmshead.php");?>

<!---------Home Content--------->

<?php 
	$session->output_messages("li");
?>

<div class="adminContent" data="home">
	<h1>Welcome Chuck</h1>

	<div class="homePanel">
	
		<h3>Actions</h3>
		<a href="#new"><img src="_assets/icons/BeOS_clipboard.png">New</a>
		<a href="#export"><img src="_assets/icons/BeOS_Query.png">Export/Backup</a>
		<a href="#delete"><img src="_assets/icons/BeOS_Trash_full.png">Delete</a>
		
		<h3>Pages</h3>
		<a href="file-editor.php"><img src="_assets/icons/BeOS_Globe_HTML_Editor.png">File Editor</a>
		<a href="#pages"><img src="_assets/icons/BeOS_paint.png">Pages</a>	
		<a href="#layouts"><img src="_assets/icons/BeOS_folder.png">Layouts</a>		

		<h3>Config</h3>
		<a href="omshell.php"><img src="_assets/icons/BeOS_apple_terminal.png">OmShell</a>
		<a href="#settings"><img src="_assets/icons/BeOS_Customize_wrench.png">Settings</a>
		<a href="#server"><img src="_assets/icons/BeOS_Globe.png">Server</a>		
		<a href="#admins"><img src="_assets/icons/BeOS_people.png">Admins</a>
	</div>
</div>

<!---------New Edit--------->
<?php include(OMADMIN."_layout".DS."new.php");?>

<!---------Server Details--------->
<?php include(OMADMIN."_layout".DS."server.php");?>

<!---------User Config--------->
<?php include(OMADMIN."_layout".DS."userconfig.php");?>

<!---------Windows and UI--------->
<div id="importPanel">
	<a href="javascript:void(0)" id="importExit">X</a>
	<h2>Import Theme</h2>
	
	<form action="./" method="post" enctype="multipart/form-data">
		<h3>Theme Title</h3>
		<input type="text" name="title" placeholder="Title">
		<h3>CSS File</h3>
		<input type="file" name="css">
		<button type="submit" name="submit">Create New Theme!</button>
	</form>
	<p>Don't worry. You can always edit later.</p>
</div>
</body>
</html> 

