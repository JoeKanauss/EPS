<?php
//set up session
session_start();
$current_desc = "";
$about_description = "";
$fileTooLargeError = "";
$wrongExtensionError = "";
$emptyDescriptionError = "";
$validCalendar = true;
$validForm = true;
	
if($_SESSION["user"] !== "admin")
{
	header("Location: EPS-About.php");
}
else{
	include "localHostConnect.php";
	
	//calendar upload
	if(isset($_FILES["calendar"])){
		$file_name = $_FILES['calendar']['name'];
		$file_size = $_FILES['calendar']['size'];
		$file_tmp = $_FILES['calendar']['tmp_name'];
		$file_type = $_FILES['calendar']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['calendar']['name'])));
		$extensions = array("csv");
	
		if(in_array($file_ext,$extensions)=== false && $file_ext != ""){
			$wrongExtensionError= "extension not allowed, please choose a CSV file.<br>";
             $validCalendar    = false;
		}
		if($file_size > 2097152){
			$fileTooLargeError = "file size must be less than 2 MB<br>";
            $validCalendar    = false;
		}
        if ($validCalendar){
			move_uploaded_file($file_tmp, "calendar-events.".$file_ext);
		}
	}
	
}

?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-AboutUpdate.css">
	<title>CALENDAR UPDATE</title>
</head>
<body>
<?php 
	if($_SESSION["user"] == "admin"){
?>
	<div id="about-current">
		<h3><a href="EPS-Calendar.php" target="_blank">SEE THE CURRENT CALENDAR PAGE</a></h3>
	</div>
	<form id="calendar-update-form"action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data">
		<div id="calendar-update-csv">
			new events file:<br><input type="file" name="calendar"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
		</div>
		<input type="submit" name="calendar-update-submit" value="Update Contact Page">
		</form>
	</div>
	</body>
</html>
<?php }?>