<link rel="stylesheet" href="_apps/database/database.css">
<div id="databaseEditorWrap">
	<span class="applicationToolbar">
		<div class="appContext">
			<a href="javascript:;">File</a>
			
			<span>
				<a href="javascript:;">Table</a>
			</span>
		
		</div>
	
		<div class="appContext">
			<a href="javascript:;">Export</a>
			
			<span>
				<a href="javascript:;">CSV</a>
			</span>
		
		</div>
		
		<div class="appContext">
			<a href="javascript:;">Import</a>
			
			<span>
				<a href="javascript:;">CSV</a>
				<a href="javascript:;">SQL</a>
			</span>
		
		</div>
	</span>

	
	<!--Database Home-->
	<div data="home" id="databaseHome">
	
	<span>
		<h3>Database <blue><?php echo DB_NAME;?></blue></h3>
	</span>
	
	<span id="databaseTables">
		<h3>Tables</h3>
		<a href="javascript:;" id="newTableToggle">New Table</a>
		
		<!--Table Creator-->
		<div id="tableCreatorWrap">
			<h2>New Table</h2>
			<h3>Title</h3>
				<input type="text" name="tableTitle" id="tableTitle">
			
			<h3>Data Fields</h3>
			<form>			
			<div id="tableCreate">
				<div>
					<a href="javascript:void(0)" class="removeTable">X</a>
					
					<input value="id" type="text" name="fieldName">
					
					<select name="fieldType">
						<option selected>INT</option>
						<option>TINYINT</option>
						<option>VARCHAR</option>
						<option>TEXT</option>
						<option>TIMESTAMP</option>
					</select>
					
					<input value="11" style="width:4em"type="text" name="fieldTypeValue">
					
					<span>Extra</span>
					<input value="NOT NULL AUTO_INCREMENT PRIMARY KEY"style="width:10em" type="text" name="fieldExtra">
				</div>
				
									
			</div>
				
				<a id="addDataField" href="javascript:void(0)">+</a>
								
			</form>
			<button type="submit" id="newTableCreate">Create New Table</button>
		</div>
	
		<!--Active Tables-->
		<span id="databaseTableLinks">

		<?php foreach($database::$tables as $table) {
			echo "<a href='javascript:;'>{$table}</a>";
		}
		?>

		</span>
	</span>

	<!--Om Configs-->
	<span>
		<h3>Omstart Configs</h3>
	</span>
	
	</div>
	
	<!--Database Editor for various pages-->
	<div data="editor" id="databaseEditorDiv"></div>
	
	<span id="databasePagesLinks">
		<a href="#home" data="home"></a>
		<a href="#editor" data="editor"></a>
	</span>

</div>


<script src="_apps/database/database.js"></script>