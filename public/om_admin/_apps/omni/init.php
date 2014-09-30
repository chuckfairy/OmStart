<?php
$om_config = new TableObject("om_config");
$om_medias = $om_config::find_by("type", "media");

?>

<link rel="stylesheet" href="_apps/omni/omni.css">

<div id="omniBoxWrap">

<!--
	<div id="omniNavigator">
	
		<span id="omniSearch">
			<button type="button">?</button>
			<input type="text" id="omniSearchInput">
		</span>
		
		<h2>Places</h2>
		
		<h3>Media</h3>
		
		<?php foreach($om_medias as $media) {?>
			<a href="javascript:;" data="<?php echo $media["data"];?>">
				<?php echo $media["om_table_name"];?>
			</a>
		<?php }?>
		
	
	</div>
-->


	<div id="omniFiles">
		<div id="omniWorkingDir"></div>		
		<div id="omniDir"></div>
	</div>
		
	<div id="omniFileInfo"></div>
	
	<span id="omniOpenPrompt">
		<div>
			<a class="omniPromptClose" href="javascript:OmniObject.closeFilePrompt();">X</a>
			<h3>Open With...</h3>
			
			<span id="omniOpenLinks">
				<a href="javascript:;" data="OmMedia">
					<img src="_assets/BeOS_Jabber.png">
					OmMedia
				</a>
		
				<a href="javascript:;" data="editor">
					<img src="_assets/BeOS_paint.png">
					Editor
				</a>		
				
				<a href="javascript:;" data="emulator">
					<img src="_assets/BeOS_Palm.png">
					Emulator
				</a>
			</span>
		</div>
	</span>

	<div id="omniContext" class="omContext">
		<span id="omniContextKeys">
			<a data="home"></a>
			<a data="file"></a>
		</span>
	
	
		<a href="javascript:;" data="mkdir">New Directory</a>
		<a href="javascript:;" data="touch">New File</a>
		<hr/>
		<a href="javascript:;" data="getinfo">Get Info</a>
		
		<div data="home"></div>
		
		<div data="file">
			<hr/>
			<a href="javascript:;" data="trash">Move To Trash</a>
			<a href="javascript:;" data="rename">Rename</a>
		</div>
	</div>

</div>
<script src="_apps/omni/omni.js"></script>