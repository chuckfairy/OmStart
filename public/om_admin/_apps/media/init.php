<?php
$om_configs = new TableObject("om_config");
$media_configs = $om_configs::find_by("type", "media");
$om_configs->clear_filters();
?>
<link rel="stylesheet" href="_apps/media/OmMedia.css">
<!------------------OmMedia picture and media source------------------>
<div id="mediaEditWrap">
	
	<span class="applicationToolbar">
		<div class="appContext">
			<a href="javascript:;">Media</a>	
			
			<span>
				<a href="javascript:;">Home</a>
				<a href="javascript:;">Upload</a>
				<a href="javascript:;">Galleries</a>	
			</span>
		</div>
	</span>

	<div id="mediaEditContent" data="home">
	
		<!---------Choose gallery media from a gallery--------->
		<div data="home">
			<h2 id="OmMediaTitle">OmMedia</h2>
			
			<div id="OmMediaSplash">
				<span id="mediaSplashKeys">
					<a href="javascript:;" data="galleries">Galleries</a>
					<a href="javascript:;" data="newgallery">New Gallery</a>
				</span>
				
				<!--Splash pages-->
				<span id="OmMediaSplashPages">	
					<div data="galleries">
						<table class="mediaTable" id="mediaGalleries">
							<tr>
								<th>Table Name</th>
								<th>Directory</th>
							</tr>
						<?php foreach($media_configs as $media_config) {?>
							<tr data="<?php echo $media_config["om_table_name"];?>">
								<td><?php echo $media_config["om_table_name"];?></td>
								<td><?php echo $media_config["data"];?></td>
							</tr>
						<?php }?>
						</table>
					</div>
					
					
					<div data="newgallery">
						
						<h2>New Gallery</h2>
						<form id="newGalleryForm">
						<h3>Table Name <input type="text" name="table_name"></h3>
						<h3>Directory <input type="text" name="directory"></h3>
						<button type="button" id="newGallerySubmit">Create</button>
						</form>
						
					</div>
				</span>
			</div>			
		</div>
				
		<!---------Gallery gotten through media/controllers/get-gallery.php---------->		
		<div data="gallery" id="OmMediaGallery"></div>
		
		<!---------Edit media from a gallery--------->
		<div data="editmedia" id="editMedia"></div>
	</div>
	
	<span id="OmMediaPages">
		<a href="javascript:;" data="home"></a>
		<a href="javascript:;" data="gallery"></a>
		<a href="javascript:;" data="editmedia"></a>
	</span>
</div>

<script src="_apps/media/OmMedia.js"></script>