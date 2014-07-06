<?php require_once("../../omstart/initialize.php");?>

<?php if(isset($_GET["table"])) {
	$query_table = escape($_GET["table"]);
	if($database->validate_table($query_table)) {
		$table_object = new TableObject($query_table);
		$table_data = $table_object->find_all();
	}
} else {redirect_to("./");}

?>

<?php include_once(OMADMIN."_layout".DS."cmshead.php");?>

<!-- TABLE CONTENT -->
<div class="adminContent" id="tableContent">

	<h2>Table:<strong><?php echo $query_table;?></strong>
		<img src="_assets/icons/BeOS_Customize_wrench.png"/>
		<img src="_assets/icons/BeOS_Hard_Drive.png"/>
		<img src="_assets/icons/BeOS_Floppy.png"/>
	</h2>
	
	<table class="OmTable" id="OmTable">
		
		<tr>
			<?php foreach($table_object->get_fields() as $table_field) {echo "<th>".ucwords($table_field)."</th>";}?>
		</tr>
		
		<?php foreach($table_data as $table) :?>
		
		<tr id="<?php echo $table['id'];?>">
			<?php 
			foreach($table as $field => $value) {
				echo "<td>".substr($value, 0, 255)."</td>";
			}
			?>
		</tr>
		<?php endforeach;?>
	</table>

</div>

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

<script>new OmTable("<?php echo $query_table;?>", "OmTable");</script>


</body>
</html> 
