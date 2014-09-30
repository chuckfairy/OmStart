<style>
#initLoader {
	width: 100%;
	height: 100%;
	display: none;
	position: absolute;
	top:0;
	z-index: 1000;
	background: rgb(255,255,255);
	
}

.initOn {
	-webkit-transform: translateX(0);
	-moz-transform: translateX(0);
	-ms-transform: translateX(0);
}

.initOff {
	-webkit-transition: all 1.5s ease-out;
	-moz-transition: all 1.5s ease-out;
	-ms-transition: all 1.5s ease-out;
	-webkit-transform: translateX(50px);
	-moz-transform: translateX(50px);
	-ms-transform: translateX(50px);
}

#initLoader img {
	display: block;
	margin: 0 auto;
	padding-top: 10%;
	width: 50%;
	max-width: 20em;
}

#initLoader h2 {
	text-align: center;
	font-size: 2em;
	width: 50%;
	margin: 0 auto;
}

#initLoader p {
	text-align: center;
	font-size: 1em;
	margin: 0 auto;
	width: 50%;
}
</style>

<img src="_assets/logo.png">
<h2>Omstart</h2>
<p id="loadingText">Loading...</p>

<script>
var initLoaderAni = new animateHTML("initLoader", {
	classOn: "initOn",
	classOff: "initOff",
	animationTime: 1500,
	showDefault: true
});

addEvent("load", window, function() {
	setTimeout(function() {
		initLoaderAni.hide();
	}, 1000);
});

//Loading text
var loadingText = document.getElementById("loadingText");
document.onreadystatechange = function() {
	if(document.readyState === "complete") {
		loadingText.innerHTML = "Namaste";
	}
}
</script>