<link rel="stylesheet" href="_apps/user/user.css">
<div id="userConfig">

	<h2>OmAdmin Config</h2>

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
			
			echo "</tr>";
			
			$OmUsers = $currentUsers->find_all();
			foreach($OmUsers as $user) {
				echo "<tr>";
				foreach($userFields as $field) {
					if($field === "hash") {unset($field);continue;}
					echo "<td>{$user[$field]}</td>";
				}	
				echo "</tr>";
			}
		?>
		</tr>
		</table>
		
		<h3>Create New Admin</h3>
		
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
</div>
<script type="text/javascript" src="_apps/user/user.js"></script>