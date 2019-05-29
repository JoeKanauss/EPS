<?php
session_start();

?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Photos.css">
	<title>PHOTOS</title>
	
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>PHOTOS</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>

	<?php include "EPS-sidebar.php";?>
	
	<div id="main">
		<div class="photos-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-8.jpg" alt="caption">
			<div class="photos-caption">CAPTION</div>
		</div>
		<div class="photos-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-8.jpg" alt="caption">
			<div class="photos-caption">CAPTION</div>
		</div>
		<div class="photos-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-8.jpg" alt="caption">
			<div class="photos-caption">CAPTION</div>
		</div>
		<div class="photos-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-8.jpg" alt="caption">
			<div class="photos-caption">CAPTION</div>
		</div>
		<div class="photos-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-8.jpg" alt="caption">
			<div class="photos-caption">CAPTION</div>
		</div>
		<div class="photos-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-8.jpg" alt="caption">
			<div class="photos-caption">CAPTION</div>
		</div>
		<div id="clear"></div>
	</div>
	
	<?php include "EPS-footer.php";?>
	
</body>
</html>