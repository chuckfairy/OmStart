<link rel="stylesheet" href="_apps/editor/editor.css">
<div id="fileEditor">
	<span class="applicationToolbar">
		<div class="appContext">
			<a href="javascript:;">File</a>
			
			<span>
				<a href="javascript:;">New File...</a>
				<a href="javascript:;">Open</a>
				<a href="javascript:;">Upload</a>
			</span>
					
		</div>
	
	</span>

<!--
	<span id="editorNavWrap">
		<div id="editorNav">
			<span>
				<h4><span id="workingDir"></span></h4>
				<a href="javascript:;" id="fileEditorBack"><</a>
			</span>
			<div id="fileEditorDirs"></div>
		</div>
	</span>
-->
	
	
	<span id="fileEditorWrap">
		<div id="fileEditorContent">
			<span>
				<div class="fileEditorTab">
					<a href="javascript:;">x</a>
					<span>New</span>
				</div>			
			</span>
			<div id="fileInfo"></div>
			<div id="aceEditor"></div>
		</div>
	</span>			
</div>	

<script src="_apps/editor/ace/ace.js"></script>
<script src="_apps/editor/editor.js"></script>