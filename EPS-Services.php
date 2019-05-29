<?php
session_start();

//bring in services from db
include "localHostConnect.php";
$servicesSql = "SELECT * FROM services";
$results = $conn->query($servicesSql);
?>

<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Services.css">
	<title>SERVICES</title>
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>SERVICES</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>
	
	<?php include "EPS-sidebar.php";?>
	
	<div id="main">
	
	<?php 
		foreach($results as $service){
	?>
		<div class="service-title">
			<a href="<?php echo $_SERVER['SCRIPT_NAME'];?>?id=<?php echo $service['service_id'];?>" class="service-title"><?php echo $service['service_title'];?></a>
		</div>
	
	<?php
	//if one of the services is chosen, store its id in the GET and load in the details
	if(isset($_GET['id']) && $_GET['id'] == $service['service_id']){
		$return_service_id = $_GET['id'];
		$return_service_sql = "SELECT * FROM services WHERE service_id = '$return_service_id'";
		$return_service_results = $conn->query($return_service_sql);
		foreach($return_service_results as $return_service_row){
	?>
	<div class="service">
			<!--<div class="service-title"><?php echo $return_service_row['service_title'];?></div>-->
			<div class="service-photo">
				<img class="service-image" src="<?php echo $return_service_row['service_photo_link'];?>">
			</div>
			<div class="service-price">PRICE: §<?php echo $return_service_row['service_price'];?></div>
			<div class="service-description">
				<p><?php echo $return_service_row['service_description'];?></p>
			</div>
			<div class="service-check">
				<input type="button" class="check-btn" value="CHECK AVAILABILITY"><br>
				<a href="EPS-Services.php"><input type="button" class="hide-btn" value="▲"></a>
			</div>
		</div>
	<?php }
		}
	}?>
	</div>

	<?php include "EPS-footer.php";?>

</body>
</html>