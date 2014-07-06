<div class="adminContent homeModule" data="admins">

	<div class="actionNav">
		<a href="javascript:void(0)" id="helpIcon"><img src="_assets/icons/BeOS_Help_book.png"></a>
		<a href="#home"><img src="_assets/icons/BeOS_Home.png"></a>	
	</div>

	<h1>OmAdmin Config</h1>

	<div class="homePanel" id="serverPanel">
		<h3>Current Admins</h3>
		
		<table class="OmTable">
		
		<tr>
		<?php $currentUsers = new TableObject("om_admin");
			$userFields = $currentUsers->get_fields();
			foreach($userFields as $field) {
				if($field === "hash") {unset($field);continue;}
				echo "<th>{$field}</th>";
			}
			
			echo "</tr><tr>";
			
			$OmUsers = $currentUsers->find_all();
			foreach($OmUsers as $user) {
				foreach($userFields as $field) {
					if($field === "hash") {unset($field);continue;}
					echo "<td>{$user[$field]}</td>";
				}	
			}
		?>
		</tr>
		</table>
		
		<h3>Create New Admin</h3>
		
		<form action="./" method="post" class="OmTableForm">
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
			
			<button type="submit" name="admincreate">Create Admin</button>
		</form>


	</div>




</div>