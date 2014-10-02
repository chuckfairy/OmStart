<link rel="stylesheet" href="_apps/settings/settings.css">
<div id="settingsScreen">

	<a href="javascript:settingsAni.hide();">X</a>
	
	<div id="settingsNavigation">
		<img src="_assets/logo.png">
		<a href="javascript:;" data="home">Home</a>
		<a href="javascript:;" data="server">Server</a>
		<a href="javascript:;" data="about">About</a>
	</div>

	<span id="settingsPagesWrap">
		<div>
		<div data="home" id="userSettings">
			<h2><?php echo $_SESSION["admin_username"];?></h2>
		
			<form action="_apps/settings/controllers/changeuser.php" method="post">
			<h3>Change Username</h3>
			<h4>New Username<input type="text" name="new_username"></h4>
			<h4>Password    <input type="password" name="password"></h4>
			<button type="submit">Submit</button>
			</form>
			
			<form action="_apps/settings/controllers/changepass.php" method="post">
			<h3>Change Password</h3>
			<h4>Current Password <input type="password" name="old_pass"></h4>
			<h4>New Password <input type="password" name="new_pass"></h4>
			<h4>Check New Password <input type="password" name="new_pass_check"></h4>
			<button type="submit">Submit</button>
			</form>
		</div>
	
		<div data="server">		
			<h3>Server Info</h3>
		
			<p><?php echo SRC;?></p>
		
			<p><?php echo SITE_ROOT;?></p>
		
			<p>
			<?php foreach($_SERVER as $server_key => $server_data) {
				echo $server_key."=>".$server_data."<br/>";
			}?>
			</p>
		
		</div>
		
		<div data="about">
			<h2>About Omstart</h2>
			
			<h3>Version <gray>1.0.0</gray></h3>
			<hr/>
			
			<p>OmStart CMS and framework built to be nice development environment for server and webpage use. Ever expanding and ever hackable OmStart is free and open-source software licensed under GPL and intended for all PHP and JavaScript users. For help user OmStart please visit the OmStart homepage listed below.</p>
			<a href="#">Github</a>
			<a href="#">Omstart</a>
		
		</div>
		</div>
	</span>

	


</div>
<script src="_apps/settings/settings.js"></script>