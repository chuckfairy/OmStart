<link rel="stylesheet" href="_apps/emulator/devices.min.css">
<link rel="stylesheet" href="_apps/emulator/emulator.css">
<div id="emulatorWrap">

	<span id="emulatorActions">
		<h2>Emulator</h2>
		
		<div id="emulatorBrowser">
			<input type="text" id="emulatorBrowserSearch" value="<?php echo SITE_ROOT;?>">
			<a href="javascript:;" id="emulatorSearchSubmit">?</a>	
		</div>
	
		<h3>Device Type 
			<select id="emulatorDeviceSelect">
				<optgroup label="Apple">
					<option>iphone6 black</option>
					<option>iphone6 gold</option>
					<option>iphone6 silver</option>
					
					<option>iphone6plus black</option>
					<option>iphone6plus gold</option>
					<option>iphone6plus silver</option>
					
					<option selected="true">iphone5c green</option>
					<option>iphone5c blue</option>
					<option>iphone5c yellow</option>
					<option>iphone5c red</option>
					
					<option>ipad silver</option>
					<option>ipad black</option>
					
					<option>iphone4s silver</option>
					<option>iphone4s black</option>
				</optgroup>
				
				<optgroup label="Other">
					<option>nexus5</option>
					
					<option>lumia920 white</option>
					<option>lumia920 black</option>
					<option>lumia920 yellow</option>
					<option>lumia920 red</option>
					<option>lumia920 blue</option>
					
					<option>s5 white</option>
					<option>s5 black</option>
					
					<option>htc-one</option>
				</optgroup>
				
			</select>	
		</h3>
		
		<h3>Landscape <input type="checkbox" id="emulatorLandscapeCheck"></h3>	
	</span>
	
	
	<div id="emulatorArea">
		<div id="emulator">
		    <div class="top-bar"></div>
		    <div class="sleep"></div>
		    <div class="volume"></div>
		    <div class="camera"></div>
		    <div class="sensor"></div>
		    <div class="speaker"></div>
		    
		    <div class="screen">
		        <iframe id="emulatorIframe"></iframe>
		    </div>
		    
		    <div class="home"></div>
		    <div class="bottom-bar"></div>
		</div>
	
	
	
	</div>
</div>
<script src="_apps/emulator/emulator.js"></script>