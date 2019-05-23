<?php
//set up session
session_start();
$about_description = "";
$fileTooLargeError = "";
$wrongExtensionError = "";
$emptyDescriptionError = "";
$validImage = true;
$validForm = true;
	
if($_SESSION["user"] !== "admin")
{
	header("Location: EPS-About.html");
}
else{
	//image upload
	if(isset($_FILES["image"])){
		$file_name = $_FILES['image']['name'];
		$file_size = $_FILES['image']['size'];
		$file_tmp = $_FILES['image']['tmp_name'];
		$file_type = $_FILES['image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));
		$extensions = array("jpeg","jpg","png");
	
		if(in_array($file_ext,$extensions)=== false && $file_ext != ""){
			$wrongExtensionError= "extension not allowed, please choose a JPEG or PNG file.<br>";
			$validImage = false;
		}
		if($file_size > 2097152){
			$fileTooLargeError = "file size must be less than 2 MB<br>";
			$validImage = false;
		}
		if($validImage){
			move_uploaded_file($file_tmp, "images/about-page-image.".$file_ext);
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
			include "localHostConnect.php";
			$sql = "INSERT INTO about (about_desc) VALUES(:aboutDesc)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':aboutDesc', $desc);
			$desc = $about_description;
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
		<h3><a href="EPS-About.html" target="_blank">SEE THE CURRENT ABOUT PAGE</a></h3>
	</div>
	<form id="about-update-form"action="" method="POST" enctype="multipart/form-data">
		<div id="about-update-image">
			<img src="http://lorempixel.com/output/animals-q-c-640-480-3.jpg">
			<input type="file" name="image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
		</div>
		<div id="about-update-description">
		<p>UPDATE DESCRIPTION<br>
		<textarea cols=40 name="about-update-description" ></textarea></p>
		
		<p style="white-space: pre-wrap;"><?php echo $emptyDescriptionError; echo $about_description;?></p>
				
		</div>
		<input type="submit" name="about-update-submit" value="Update">
		</form>
	</div>
	</body>
</html>
<?php }?>