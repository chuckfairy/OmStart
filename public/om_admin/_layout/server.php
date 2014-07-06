<div class="adminContent homeModule" data="server">

	<div class="actionNav">
		<a href="javascript:void(0)" id="helpIcon"><img src="_assets/icons/BeOS_Help_book.png"></a>
		<a href="#home"><img src="_assets/icons/BeOS_Home.png"></a>	
	</div>

	<h1>Server</h1>

	<div class="homePanel" id="serverPanel">
		<h3>Databases</h3>
		<?php echo DB_NAME;?>
		
		
		
		<h3>Server Info</h3>

		<p>
		<?php foreach($_SERVER as $server_key => $server_data) {
			echo $server_key."=>".$server_data."<br/>";
		}?>
		</p>

		<h3>PHP info</h3>
		
		<p></p>


	</div>




</div>