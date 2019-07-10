<?php
session_start();

include "localHostConnect.php";
$photosSql = "SELECT * FROM photos";
$results = $conn->query($photosSql);
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
		<?php 
			foreach($results as $photo){
		?>
		<div class="photos-image">
			<a href="EPS-photoCaption.php?id=<?php echo $photo['photos_id'];?>"> <img src="<?php echo $photo['photos_link'];?>" title="<?php echo $photo['photos_caption'];?>" /></a>
			
		</div>
		<?php
			}
		?>
	</div>
	
	<?php include "EPS-footer.php";?>
	
</body>
</html>