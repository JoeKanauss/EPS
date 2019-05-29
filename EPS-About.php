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
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-About.css">
	<title>ABOUT</title>
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>PET CARE BY EMILY</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>

	<?php include "EPS-Sidebar.php";?>
	
	<div id="main">
		<img id="about-image" src="images/about/about-page-image.png">
		<div id="about-description">
			<p><?php echo $about_description;?></p>
		</div>
	</div>
	
	<?php include "EPS-Footer.php";?>

</body>
</html>