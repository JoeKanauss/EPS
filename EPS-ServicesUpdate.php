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
	header("Location: EPS-Services.php");
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
	
	//update service
	if(isset($_POST["update-service-submit"])){
	try{
		$serviceId = $_POST['update-service-id'];
		$serviceImage = $_FILES['update-service-image']['name'] ;
		$serviceTitle = filter_var($_POST['update-service-title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$servicePrice = number_format($_POST['update-service-price'], 2);
		$serviceDescription = filter_var($_POST['update-service-desc'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		// if the image is not set, don't update the image (in case of keeping the same image as before, this will not make the image blank)
		if($serviceImage == ""){
			$updateSql = "UPDATE services SET service_title = '$serviceTitle', service_price = '$servicePrice', service_description = '$serviceDescription' WHERE service_id = $serviceId";
		}
		else{
			$updateSql = "UPDATE services SET service_photo_link = 'images/services/$serviceImage', service_title = '$serviceTitle', service_price = '$servicePrice', service_description = '$serviceDescription' WHERE service_id = $serviceId";
		}
		$update = $conn->prepare($updateSql);
		$update->execute();
		header("Refresh:0");
		
	}
	catch(PDOException $e)
    {
		echo $sql . "<br>" . $e->getMessage();
    }
	}
	
	//upload image
	if(isset($_FILES['add-service-image'])){
		$file_name = $_FILES['add-service-image']['name'];
		$file_size = $_FILES['add-service-image']['size'];
		$file_tmp = $_FILES['add-service-image']['tmp_name'];
		$file_type = $_FILES['add-service-image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['add-service-image']['name'])));
		$extensions = array("jpeg","jpg","png");
		$image_link = "images/services/".$_FILES['add-service-image']['name'];
	
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
	
	//add service
	if(isset($_POST['add-service-submit'])){
		try{	
			$addServiceImage = "images/services/".$_FILES['add-service-image']['name'];
			$addServiceTitle = filter_var($_POST['add-service-title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$addServicePrice = $_POST['add-service-price'];
			$addServiceDescription = filter_var($_POST['add-service-desc'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
			$addServiceSql = $conn->prepare("INSERT INTO services (service_photo_link, service_title, service_price, service_description) VALUES (:addServiceImage, :addServiceTitle, :addServicePrice, :addServiceDescription)");
		
			$addServiceSql->bindParam(':addServiceImage', $addServiceImage);
			$addServiceSql->bindParam(':addServiceTitle', $addServiceTitle);
			$addServiceSql->bindParam(':addServicePrice', $addServicePrice);
			$addServiceSql->bindParam(':addServiceDescription', $addServiceDescription);
		
			$addServiceSql->execute();
			header("Refresh:0");
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
	}
	//delete service
	if(isset($_POST['update-service-delete'])){
		try{
			$serviceIdToDelete = $_POST['update-service-id'];
			$deleteServiceSql = "DELETE FROM services WHERE service_id = $serviceIdToDelete";
			$deleteService = $conn->query($deleteServiceSql);
			header("Refresh:0");
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
	}
}
?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-ServicesUpdate.css">
	<title>SERVICES UPDATE</title>
</head>
<body>
<?php 
	if($_SESSION["user"] == "admin"){
?>
	<div id="services-current">
		<h3><a href="EPS-Services.php" target="_blank">SEE THE CURRENT SERVICES PAGE</a></h3>
	</div>
<?php 
	//display all service titles with update/delete buttons
	foreach($result as $row) {		
?>
	<div class="service">
			<div class="service-title"><?php echo $row['service_title'];?></div>
			<a href="EPS-ServicesUpdate.php?id=<?php echo $row['service_id'];?>">UPDATE or DELETE</a>
	</div>
<?php  
	//if one of the services is chosen for update, store its id in the GET and load in the details
	if(isset($_GET['id'])&& $_GET['id'] == $row['service_id']){
		$return_service_id = $_GET['id'];
		$return_service_sql = "SELECT * FROM services WHERE service_id = '$return_service_id'";
		$return_service_results = $conn->query($return_service_sql);
		foreach($return_service_results as $return_service_row){
?>
	<div id="selected-service">
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>?id=<?php echo $return_service_row['service_id'];?>" method="POST" enctype="multipart/form-data">
			<h2><?php echo $return_service_row['service_title'];?></h2>
			current image:<br><img class="service-image" src="<?php echo $return_service_row['service_photo_link'];?>"><br>
			new image:<br><input type="file" name="update-service-image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
			title:<br><input type="text" name="update-service-title" value="<?php echo $return_service_row['service_title'];?>"><br>
			price:<br>§ <input type="text" pattern="^\d*(\.\d{0,2})?$" name="update-service-price" value="<?php echo $return_service_row['service_price'];?>"><br>
			description:<br>
			<input type="hidden" name="update-service-id" value="<?php echo $return_service_row['service_id'];?>">
			<textarea name="update-service-desc" cols=40 rows=20"><?php echo $return_service_row['service_description'];?></textarea><br>
			<input type="submit" name="update-service-submit" value="Update Service">
			<input type="submit" class="delete-btn" name="update-service-delete" value="DELETE SERVICE"><br>
			<a href="EPS-ServicesUpdate.php"><input type="button" class="hide-btn" value="&#9650;"></a>
		</form>
	</div>
<?php }
	}
	}
	}
?>
	<hr>
	<form class="add-service-form" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data"><input type="submit" name="add-service" value= "Add New Service"></form>
<?php
	if(isset($_POST['add-service'])){
?>
	<div id="selected-service">
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data">
			image:<input type="file" name="add-service-image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
			title: <input type="text" name="add-service-title"><br>
			price: § <input type="number" min="0.00" step=".50" name="add-service-price"><br>
			description:<br>
			<textarea name="add-service-desc" cols=40 rows=20"></textarea><br>
			<input type="submit" name="add-service-submit" value="Add Service">
		</form>
	</div>
	<?php }?>
</body>
</html>




