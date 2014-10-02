<div class="adminContent homeModule" data="new">

	<div class="actionNav">
		<a href="javascript:void(0)" id="helpIcon"><img src="_assets/icons/BeOS_Help_book.png"></a>
		<a href="#home"><img src="_assets/icons/BeOS_Home.png"></a>	
	</div>

	<h1>New</h1>
	
	<div class="homePanel" id="newPanel">
	
		
		<!---------New Page--------->
		<div class="newContentWrap">
			<a href="javascript:void(0)"><img src="_assets/icons/BeOS_folder.png"></a>
			<div>
				<h2>New Page</h2>
				
				<h3>Title</h3>
				<input type="text" name="pageTitle">
				
				<h3 data="pageLayout">Layout</h3>
				<form action="../omstart/controllers.php" id="pageLayout">
					<input type="hidden" name="table" value="om_layout" placeholder="Sample Title">
					<h3>Create new layout or choose existing</h3>
					<h4>Title</h4>
					<input type="text" name="title">
					<h4>Upload or Create new</h4>
					<input type="file" name="_FILE_title">
				</form>
				
				<h3>Directory Location</h3>
				<input type="text" name="location" value="<?php echo SITE_ROOT.DS;?>">
				<button id="newPageSubmit">Create New Page</button>
			</div>
		
		</div>
		
		<!---------Table--------->
		<div class="newContentWrap">
			<a href="javascript:void(0)"><img src="_assets/icons/BeOS_Query.png" style="background: rgb(220,220,220);"></a>
			<div>
			<h2>New Table</h2>
			<h3>Title</h3>
				<input type="text" name="tableTitle" id="tableTitle">
			
			<h3>Data Fields</h3>
			<form>			
			<div class="tableCreate">
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
		</div>
		
		
		
		<!---------File--------->
		<div class="newContentWrap">
			<a href="javascript:void(0)"><img src="_assets/icons/BeOS_clipboard.png" style="background: rgb(255,255,255);"></a>
			<div>
			<h2>New File</h2>
			
			<h3>Title</h3>
				<input type="text" name="fileTitle">
			<h3>Directory Location</h3>
				<input type="text" name="location" value="<?php echo SITE_ROOT.DS;?>">
			<button id="newFileCreate">Create New File</button>
			</div>
			
		</div>
		
		
	</div>

</div>