<?php
//set up session
session_start();
$selected_id = "";
$current_desc = "";
$image_link = "";
$about_description = "";
$fileTooLargeError = "";
$wrongExtensionError = "";
$emptyDescriptionError = "";
$validImage = true;
$validForm = true;
	
if($_SESSION["user"] !== "admin")
{
	header("Location: EPS-Services.html");
}
else{
	include "localHostConnect.php";
	
	//load Services from DB
	$sql = "SELECT service_id, service_title FROM services";
	$result = $conn->query($sql);
	
	//upload service image
	if(isset($_FILES['update-service-image'])){
		$file_name = $_FILES['update-service-image']['name'];
		$file_size = $_FILES['update-service-image']['size'];
		$file_tmp = $_FILES['update-service-image']['tmp_name'];
		$file_type = $_FILES['update-service-image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['update-service-image']['name'])));
		$extensions = array("jpeg","jpg","png");
		$image_link = "images/services/".$_FILES['update-service-image']['name'];
	
		if(in_array($file_ext,$extensions)=== false && $file_ext != ""){
			$wrongExtensionError= "extension not allowed, please choose a JPEG or PNG file.<br>";
			$validImage = false;
		}
		if($file_size > 2097152){
			$fileTooLargeError = "file size must be less than 2 MB<br>";
			$validImage = false;
		}
		if($validImage){
			move_uploaded_file($file_tmp, $image_link);
		}
	}
}
?>
<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Emily's Pet Sitting</title>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Styles.css">
	<link rel="stylesheet" type="text/css" href="styles/EPS-ServicesUpdate.css">
	<link rel="stylesheet" href="http:://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="js/jquery-3.2.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/EPS-js.js"></script>
</head>
<body>
<?php 
	if($_SESSION["user"] == "admin"){
?>
	<div id="services-current">
		<h3><a href="EPS-Services.html" target="_blank">SEE THE CURRENT SERVICES PAGE</a></h3>
	</div>
<?php 
	//display all service titles with update/delete buttons
	foreach($result as $row) {		
?>
	<div class="service">
			<div class="service-title"><?php echo $row['service_title'];?></div>
			<a href="EPS-ServicesUpdate.php?id=<?php echo $row['service_id'];?>">UPDATE or DELETE</a>
	</div>
<?php }
	} 
	//if one of the services is chosen for update, store its id in the GET and load in the details
	if(isset($_GET['id'])){
		$return_service_id = $_GET['id'];
		$return_service_sql = "SELECT * FROM services WHERE service_id = '$return_service_id'";
		$return_service_results = $conn->query($return_service_sql);
		foreach($return_service_results as $return_service_row){
?>
	<div id="selected-service">
		<form action="EPS-ServicesUpdate.php?id=<?php echo $return_service_row['service_id'];?>" method="POST" enctype="multipart/form-data">
			image:<br><img class="service-image" src="<?php echo $return_service_row['service_photo_link'];?>"><br>
			<input type="file" name="update-service-image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
			title: <input type="text" name="update-service-title" placeholder="<?php echo $return_service_row['service_title'];?>"><br>
			price: § <input type="number" min="0.00" step=".50" name="update-service-price" placeholder="<?php echo $return_service_row['service_price'];?>"><br>
			description:<br>
			<input type="hidden" name="update-service-id" value="<?php echo $return_service_row['service_id'];?>">
			<textarea name="update-service-desc" cols=40 rows=20 placeholder="<?php echo $return_service_row['service_description'];?>"></textarea>
			<input type="submit" name="update-service-submit" value="Update Service">
		</form>
	</div>
<?php }
	}
?>
</body>
</html>




