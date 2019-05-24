<?php
//set up session
session_start();
$current_desc = "";
$about_description = "";
$fileTooLargeError = "";
$wrongExtensionError = "";
$emptyDescriptionError = "";
$validImage = true;
$validForm = true;
	
if($_SESSION["user"] !== "admin")
{
	header("Location: EPS-About.php");
}
else{
	include "localHostConnect.php";
	
	//load in current About page description
	$sql = "SELECT about_desc FROM about WHERE about_id = 1";
	$result = $conn->query($sql);
	foreach($result as $row){
		$current_desc = $row["about_desc"];
	}
	
	//image upload
	if(isset($_FILES["image"])){
		$file_name = $_FILES['image']['name'];
		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$file_type = $_FILES['image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
		$extensions = array("png");
	
		if(in_array($file_ext,$extensions)=== false && $file_ext != ""){
			$wrongExtensionError= "extension not allowed, please choose a PNG file.<br>";
			$validImage = false;
		}
		if($file_size > 2097152){
			$fileTooLargeError = "file size must be less than 2 MB<br>";
			$validImage = false;
		}
		if($validImage){
			move_uploaded_file($file_tmp, "images/about/about-page-image.".$file_ext);
		}
	}
	//description upload
	if(isset($_POST["about-update-submit"])){
		$about_description = $_POST["about-update-description"];
		if($about_description == ""){
			$emptyDescriptionError = "Please enter a description.";
			$validForm = false;
		}else{
			$about_description = filter_var($about_description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		}
		if($validForm && $validImage){
			$sql = "UPDATE about SET about_desc = '$about_description' WHERE about_id = 1";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
		}
		
	}
}

?>
<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Emily's Pet Sitting</title>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Styles.css">
	<link rel="stylesheet" type="text/css" href="styles/EPS-AboutUpdate.css">
	<link rel="stylesheet" href="http:://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="js/jquery-3.2.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/EPS-js.js"></script>
</head>
<body>
<?php 
	if($_SESSION["user"] == "admin"){
?>
	<div id="about-current">
		<h3><a href="EPS-About.php" target="_blank">SEE THE CURRENT ABOUT PAGE</a></h3>
	</div>
	<form id="about-update-form"action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data">
		<div id="about-update-image">
			<img src="images/about/about-page-image.png">
			<input type="file" name="image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
		</div>
		<div id="about-update-description">
			<p>UPDATE DESCRIPTION<br>
			<textarea cols=40 name="about-update-description" placeholder="<?php echo $current_desc;?>" style="white-space: pre-wrap"></textarea></p>
			<p><?php echo $emptyDescriptionError;?></p>	
		</div>
		<input type="submit" name="about-update-submit" value="Update">
		</form>
	</div>
	</body>
</html>
<?php }?>