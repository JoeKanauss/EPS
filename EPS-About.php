<?php
session_start();

include "localHostConnect.php";
$sql = "SELECT about_desc FROM about WHERE about_id=1";
$result = $conn->query($sql);
foreach($result as $row){
	$about_description = $row["about_desc"];
}
?>
<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Emily's Pet Sitting</title>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Styles.css">
	<link rel="stylesheet" type="text/css" href="styles/EPS-About.css">
	<link rel="stylesheet" href="http:://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="js/jquery-3.2.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/EPS-js.js"></script>
	
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>EMILY'S PET SITTING</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>
	<div id="sidebar">
		<div class="sb-head"><h2>EMILY'S PET SITTING</h2></div>
		<div class="sb-about on-page"><h3><a href= "">About</a></h3></div>
		<div class="sb-services"><h3><a href= "">Services</a></h3></div>
		<div class="sb-photos"><h3><a href= "">Photos</a></h3></div>
		<div class="sb-calendar"><h3><a href= "">Booking Calendar</a></h3></div>
		<div class="sb-testimonials"><h3><a href= "">Testimonials</a></h3></div>
		<div class="sb-contact"><h3><a href= "">Contact</a></h3></div>
		<?php 
			if(array_key_exists('user', $_SESSION) && $_SESSION["user"] == "admin"){
		?>
			<a href="EPS-AdminLogin.php">ADMIN PAGE</a>
		<?php }
		?>
	</div>
	<div id="main">
		<img id="about-image" src="images/about/about-page-image.png">
		<div id="about-description">
			<p><?php echo $about_description;?></p>
		</div>
	</div>
	<div id="footer">
		<div class="left-foot">
			<a href="" class="on-page">About</a> | <a href="">Services</a><br>
			<a href="">Photos</a> | <a href="">Booking Calendar</a><br>
			<a href="">Testimonials</a> | <a href="">Contact</a>
		</div>
		<div class="social-media-links">
		<p>SOCIAL MEDIA LINKS WILL GO HERE!!!</p>
		</div>
	</div>
</body>
</html>