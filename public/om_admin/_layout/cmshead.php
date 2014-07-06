<?php !isset($title)? $title = "Omstart CMS":null;?>
<!DOCTYPE html> 
<html>
<head>
<title><?php echo $title;?></title>
<link href="_css/cms.css" rel="stylesheet">
<?php echo isset($css)? $css: null;?>
<script src="_js/jquery-1.9.1.min.js"></script>
<script src="_js/cms.js" type="text/javascript"></script>
<meta charset="utf-8">
</head>
<body>

<!---------SIDEBAR--------->
<a href="javascript:void(0)" ><img src="_assets/omstart_logo.png" id="showStart"></a>

<div id="sidebar">
	<a href="javascript:void(0)" class="xbox">X</a>
	
	<img src="_assets/omstart_logo.png">
	<h2><a href="./">{OmStart}</a></h2>
	
	<h3 data="OmData">Databases</h3>
	<div class="navSection" id="OmData">
		<p><?php echo DB_NAME;?></p>
	</div>
	
	<h3 data="OmTables">Tables</h3>
	<div class="navSection" id="OmTables">
		<?php foreach($database->get_tables() as $table_name):?>
			<a href='table.php?table=<?php echo $table_name;?>'><?php echo $table_name;?></a>	
		<?php endforeach;?>
	</div>
	
	<h3 data="OmActions">Actions</h3>
	<div class="navSection" id="OmActions">
		<div id="new_nav_div">
			<a href="new.php">New</a>
			<a href="insert.php">Insert</a>
			<br/>
			<a href="javascript:void(0)" id="import">Import</a>
			<a href="export.php">Export</a>
			<a href="editor.php">Blog Editor</a>
			<a href="omshell.php">OmShell</a>
		</div>
	</div>
	
</div>


<!---------EDITOR DATA UI--------->
<div id="successBox">
	<img src="_assets/icons/BeOS_NetPositive.png" width="40" height="40" />
	<span>Save Successful</span>
	<div></div>
</div>

<div id="failBox">
	<img src="_assets/icons/BeOS_stop.png" width="40" height="40" />
	<span>Failure</span>
	<p>MESSAGE</p>
</div>