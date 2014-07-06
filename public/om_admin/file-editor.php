<?php require_once("../../omstart/initialize.php");?>

<?php
if(isset($_POST["submit"])) {
	
}


$title = "Omstart File Editor";
$css = "<link href='_css/editor.css' rel='stylesheet'>";
?>

<?php include_once(OMADMIN."_layout".DS."cmshead.php");?>

<menu id="fileEditorMenu">
	<a href="./"><img src="_assets/icons/BeOS_Home.png"></a>	
	<a href="javascript:void(0)" id="HDIcon"><img src="_assets/icons/BeOS_Firewire_HD.png"/></a>
	<a href="#save" id="saveIcon"><img src="_assets/icons/BeOS_Floppy.png"/></a>
	<a href="#delete" id="deleteIcon"><img src="_assets/icons/BeOS_Trash_full.png"></a>
	
	<div id="fileTabs">
		<span>
			<a href="#" id="tabScroll">V</a>
			<h3>Sample.html</h3>
		</span>
		
		<div id="fileTabsLinks"></div>		
	</div>
</menu>


<!---------Iframe Render Browser--------->
<div id="renderWrap">
	<div>
		<a href="javascript:void(0)" >X</a>
		<a href="javascript:void(0)">[^]</a>
		<a href="javascript:void(0)">page</a>
	</div>
	<span></span>
</div>

<!---------Ace Code editor--------->
<div id="writerWrap">
	<div id="col-resize"></div>
	<pre id="editor" contentEditable="true"></pre>
</div>


<!---------File Browser--------->
<span class="OmBox" id="fileBrowser">
	<div class="OmBoxTop" id="fileBrowserTop">
		<a href="javascript:void(0)" class="OmBoxX">X</a>
		<a href="javascript:void(0)" class="OmBoxMinimize">[^]</a>
		<a href="javascript:void(0)" class="OmBoxFullsize">[ ]</a>
		
		<a href="javascript:void(0)" class="OmBoxHeader"><h3>File Browser</h3></a>
	</div>
	
	<div class="OmBoxContent">

		<span id="fileActions">
			<h2>Your Server Files</h2>
			
			<span id="serverFileSearch">
				<a href="javascript:void(0)">??</a>
				<input type="text" placeholder="Search">
			</span>
			
			<div>
				<h3>Upload</h3>
				<input type="file" id="uploadFile">
				<a id="uploadSubmit" href="javascript:void(0)">Upload File</a>
			</div>
		
		</span>
		
		<!--File Links-->
		<div id="browserFiles">
			<!-- <h3><?php echo SITE_SRC;?></h3> -->
			
			<table class="OmTable" id="fileTable">
				<?php 
				//Load initial Files and Directories
				$currentDir = "public";
				$dirPath = SITE_SRC.$currentDir;	
				$dirHandle = opendir($dirPath);
				while(false !== ($filename = readdir($dirHandle))){
					//Remove .DS_STORE files
					if($filename == ".") {continue;}
					if(preg_match("/.DS_STORE/i", $filename)) {
						continue;
					}
					
					//Check if dir or file
					if(is_dir($dirPath.DS.$filename)) {
						echo "<tr><td><a href='#dir-{$currentDir}/{$filename}' class='file-dir'>{$filename}</a></td>".
							 "<td>Uh</td>".
							 "<td>Directory</td>";
					} else {
						echo "<tr><td><a href='#file-{$currentDir}/{$filename}' class='file-link'>{$filename}</a></td>". 
							 "<td>Uh</td>".
							 "<td>File</td></tr>";
					}
				}
				
				?>
			</table>
		</div>
	</div>
</span>









<script src="_js/ace-src/ace.js" type="text/javascript"></script>
<script src="_js/file-editor.js" type="text/javascript"></script>

</body>
</html>