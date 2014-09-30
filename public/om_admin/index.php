<?php require_once("../../omstart/initialize.php");?>

<!DOCTYPE html> 
<html>
<head>
<title>OmStart CMS || Restart your web</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">

<link href="_css/os.css" rel="stylesheet">
<link href="_apps/notifi/notifi.css" rel="stylesheet">

<script src="_apps/OmBox/OmBox.js" type="text/javascript"></script>
<script src="_js/core.js" type="text/javascript"></script>
<?php if($Admin->is_logged_in()) {?>
<script>
var SRC = "<?php echo SRC;?>";
var HOSTHOME = location.href.split("/");
HOSTHOME.pop();
HOSTHOME.pop();
HOSTHOME.pop();
HOSTHOME = HOSTHOME.join("/") + "/";

var ADMIN_USERNAME = "<?php echo $_SESSION["admin_username"];?>";
var DB_USER = "<?php echo DB_USER;?>";
</script>
<?php }?>
</head>
<body class="desktopBody">
<!---------Notifi Notification system--------->
<div id="Notifi"></div>

<!---------Login Screen--------->
<?php if(!$Admin->is_logged_in()) {?>
<div id="loginScreen">
	<div></div>
	<span id="loginBox">
		<img src="_assets/logo.png">
		
		<h2>OmStart</h2>
		
		<h3>Username <input type="text" name="username"></h3>
		<h3>Password <input type="password" name="password"></h3>
		<button type="button" id="loginSubmit">Login!</button>
	</span>
</div>
<script src="_apps/notifi/notifi.js" type="text/javascript"></script>
<script src="_js/login.js" type="text/javascript" defer></script>
</body>
</html>
<?php return true;}?>

<!---------Settings and Loader Content--------->
<div id="initLoader"><?php include("_apps/loader/init.php");?></div>
<?php sleep(.5);?>
<span id="settingsWrap"><?php include("_apps/settings/init.php");?></span>

<!---------Background Content--------->
<span id="background"></span>

<span id="OmBoxTitle">
	<h1>OmStart</h1>
	<p>The OS CMS</p>
	<p>Version 1.0.0 <a href="https://i.imgur.com/TutfU8w.jpg" target="_blank">Fuji</a></p>
	<p><?php echo $Admin->username;?> <a href="javascript:logout();">Log out</a></p>
	<p id="OmClockOne"></p>
</span>

<span id="mobileHeader">
	<h3>Omstart <a href="https://i.imgur.com/TutfU8w.jpg" target="_blank">Fuji</a> <span id="OmClockTwo"></span></h3>
	
	<div>
		<div id="appsIconDiv">
			<img src="_assets/appsicon.jpg">
		</div>
		
		<div id="appsHamburger">
			<img src="_assets/hamburger.jpg">
		</div>
		
		<div id="appsSettings">
			<img src="_assets/gearicon.jpg">
			<span id="appSettingsPop">
				<span id="settingsTriangle"></span>
				<div>
				<h3><?php echo $_SESSION["admin_username"];?></h3>
				<hr/>
				
				<a href="javascript:settingsAni.show();">Settings</a>
				<a href="#">About</a>
				<a href="#">Help</a>
				
				<hr/>
				
				<button type="button" onclick="javascript:logout();">Log Out</button>
				</div>
			</span>
		</div>
	</div>
</span>


<!---------Apps Icon Links--------->
<div id="desktopApplications">

<span id="applicationsWrap">
<h2>Applications</h2>
<div class="applications">		
	<h3>System</h3>
	<a href="javascript:omniBox.show();">
		<img src="_assets/BeOS_NetPositive.png">
		Omni
	</a>
	
	<a href="javascript:fileEditorBox.show();">
		<img src="_assets/BeOS_paint.png">
		Editor
	</a>
	
	<a href="javascript:mediaBox.show();">
		<img src="_assets/BeOS_Jabber.png">
		Media
	</a>
	
	<a href="javascript:databaseBox.show();">
		<img src="_assets/BeOS_Hard_Drive.png">
		Database
	</a>	
	<div class="clearfloat"></div>	
</div>

<div class="applications">
	<h3>Config</h3>
	<a href="javascript:omshellBox.show();">
		<img src="_assets/BeOS_apple_terminal.png">
		OmShell
	</a>
	
	<a href="javascript:settingsAni.show();">
		<img src="_assets/BeOS_Customize_wrench.png">
		Settings
	</a>
	
	<a href="javascript:userBox.show();">
		<img src="_assets/BeOS_people.png">
		Users
	</a>
	<div class="clearfloat"></div>	
</div>

<div class="applications">
	<h3>Apps</h3>
	
	<a href="javascript:emulatorBox.show();">
		<img src="_assets/BeOS_Palm.png">
		Emulator
	</a>
	
	<a href="javascript:gravitBox.show();">
		<img src="_assets/gravit.png" alt="codepen">
		Gravit
	</a>
	<div class="clearfloat"></div>	
</div>
</span>

<!---------OmBoxes--------->
<div id="OmBoxes">
	<!---------Omni--------->
	<span id="omniBox">
		<?php include("_apps/omni/init.php");?>
	</span>

	<!---------File Editor--------->
	<span id="fileEditorBox">
		<?php include("_apps/editor/init.php");?>
	</span>
	
	<!---------Media Editor--------->
	<span id="mediaBox">
		<?php include("_apps/media/init.php");?>
	</span>

	<!---------Database Editor--------->
	<span id="databaseBox">
		<?php include("_apps/database/init.php");?>
	</span>

	<!---------OmShell Terminal--------->	
	<span id="omshellBox">
		<?php include("_apps/OmShell/init.php");?>
	</span>

	<!---------User Config--------->
	<span id="userBox">
		<?php include("_apps/user/init.php");?>
	</span>

	<!---------Emulator--------->
	<span id="emulatorBox">
		<?php include("_apps/emulator/init.php");?>
	</span>

	<!---------Codepen--------->
	<span id="gravitBox">
		<div><iframe src="http://hub.gravit.io/browser/"></iframe></div>
	</span>		
</div>

</div>

<script src="_js/os.js" type="text/javascript"></script>
<script src="_apps/notifi/notifi.js" type="text/javascript"></script>
</body>
</html> 