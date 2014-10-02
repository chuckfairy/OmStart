<?php require_once("../../../../../omstart/initialize.php");?>

<?php if(isset($_GET["table"])) {
	$query_table = escape($_GET["table"]);
	if($database->validate_table($query_table)) {
		$table_object = new TableObject($query_table);
		$table_data = $table_object->find_all();
	}
} else {
	//No table get set
	$session->set_message("No table GET set.");
	return false;	
}

?>

<!-- TABLE CONTENT -->
<div id="databaseTableContent">

	<h2>Table:<strong><?php echo $query_table;?></strong>
		<a href="javascript:;" onclick="databasePageToggler.setPage('home');"><red>Other Tables</red></a>
	</h2>
	
	<span>
		<h3>Fields <blue><?php echo count($database::$table_data[$query_table]);?></blue></h3>
		<table class="OmTable">
		<?php foreach($database::$table_data[$query_table] as $field => $data_array){?>
			<tr>
				<th><?php echo $field;?></th>
				
				<?php foreach($data_array as $data_field => $data) {?>
				<td><?php echo $data_field.": <weak>".$data."</weak>";?></td>
				<?php }?>
			</tr>
		<?php } ?>
		</table>
	</span>
	
	
	<span>
	<h3>Records <blue><?php echo count($table_data);?></blue></h3>
	
	<div id="tableActionKeys">
		<a href="javascript:;" data="insert">Insert+</a>
		<a href="javascript:;" data="search">Search?</a>
		<a href="javascript:;" id="deleteTablesButton"><red>Delete</red></a>
		<a href="javascript:;" data="home">X</a>
	</div>
	
	<span id="tableActions">	
		<div id="databaseInsert" data="insert">
			<form action="" method="post" id="databaseInsertForm">
			<?php foreach($database::$table_data[$query_table] as $field => $data_array){?>
				<h3>
					<?php echo $field;?>
					<input type="text" name="<?php echo $field;?>">
					<?php if($data_array["null"] === "NO") {echo "<span>NO NULL</span>";}?>
				</h3> 
			<?php }?>
			</form>
			
			<button type="button" id="tableInsertSubmit">Insert</button>
		</div>
		
		<div data="search">
		
		
		</div>
		
	</span>
		
	<table class="OmTable" id="OmTable">
		
		<tr>
			<?php foreach($table_object->get_fields() as $table_field) {echo "<th>".ucwords($table_field)."</th>";}?>
		</tr>
		
		<?php foreach($table_data as $table) :?>
		
		<tr data="<?php echo $table['id'];?>">
			<?php 
			foreach($table as $field => $value) {
				echo "<td>".substr($value, 0, 255)."</td>";
			}
			?>
		</tr>
		<?php endforeach;?>
	</table>
	</span>
	
	<span>
	<h3>Table Data</h3>
	</span>

</div>
<!--

<script type="text/javascript">var omtableInstance = new OmTable("<?php echo $query_table;?>", "OmTable");</script>
-->