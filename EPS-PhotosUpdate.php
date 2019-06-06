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
	header("Location: EPS-Photos.php");
}
else{
	include "localHostConnect.php";
	
	//load Photos from DB
	$sql = "SELECT * FROM photos";
	$result = $conn->query($sql);
	
	//update photo
	if(isset($_POST["update-photos-submit"])){
	try{
		$photoId = $_POST['update-photos-id'];
		$photoCaption = filter_var($_POST['update-photos-caption'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
		$updateSql = "UPDATE photos SET photos_caption = '$photoCaption' WHERE photos_id = $photoId";
		$update = $conn->prepare($updateSql);
		$update->execute();
		header("Refresh:0");
	}
	catch(PDOException $e)
    {
		echo $sql . "<br>" . $e->getMessage();
    }
	}
	
	//upload new image
	if(isset($_FILES['add-photos-image'])){
		$file_name = $_FILES['add-photos-image']['name'];
		$file_size = $_FILES['add-photos-image']['size'];
		$file_tmp = $_FILES['add-photos-image']['tmp_name'];
		$file_type = $_FILES['add-photos-image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['add-photos-image']['name'])));
		$extensions = array("jpeg","jpg","png");
		$image_link = "images/photos/".$_FILES['add-photos-image']['name'];
	
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
	
	//add photo
	if(isset($_POST['add-photos-submit'])){
		try{	
			if($_FILES['add-photos-image']['name'] == ""){
				 echo "PLEASE UPLOAD AN IMAGE";
			}
			else{
				$addPhotosImage = "images/photos/".$_FILES['add-photos-image']['name'];
				$addPhotosCaption = filter_var($_POST['add-photos-caption'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
				$addPhotosSql = $conn->prepare("INSERT INTO photos (photos_link, photos_caption) VALUES (:addPhotosImage, :addPhotosCaption)");
		
				$addPhotosSql->bindParam(':addPhotosImage', $addPhotosImage);
				$addPhotosSql->bindParam(':addPhotosCaption', $addPhotosCaption);

		
				$addPhotosSql->execute();
				header("Refresh:0");
			}
		}
		catch(PDOException $e)
		{
			echo $sql . "<br>" . $e->getMessage();
		}
	}
	//delete photo
	if(isset($_POST['update-photos-delete'])){
		try{
			$photoIdToDelete = $_POST['update-photos-id'];
			$deletePhotoSql = "DELETE FROM photos WHERE photos_id = $photoIdToDelete";
			$deletePhoto = $conn->query($deletePhotoSql);
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
	<link rel="stylesheet" type="text/css" href="styles/EPS-PhotosUpdate.css">
	<title>PHOTOS UPDATE</title>
</head>
<body>
<?php 
	if($_SESSION["user"] == "admin"){
?>
	<div id="photos-current">
		<h3><a href="EPS-Photos.php" target="_blank">SEE THE CURRENT PHOTOS PAGE</a></h3>
	</div>
	
<?php 
	//display all service titles with update/delete buttons
	foreach($result as $photo) {		
?>
	<!--Make each photo its own section, so the UPDATE/DELETE link leads to that section, instead of top of page -->
	<a name="photo-<?php echo $photo['photos_id'];?>">
	<div class="photos-image">
			<img src="<?php echo $photo['photos_link'];?>" alt="<?php echo $photo['photos_caption'];?>">
			<div class="photos-caption"><?php echo $photo['photos_caption'];?><br>
			<a href="EPS-PhotosUpdate.php?id=<?php echo $photo['photos_id'];?>#photo-<?php echo $photo['photos_id'];?>">UPDATE or DELETE</a></div>
<?php 
		//if one of the services is chosen for update, store its id in the GET and load in the details
		if(isset($_GET['id'])&& $_GET['id'] == $photo['photos_id']){
		$return_photos_id = $_GET['id'];
		$return_photos_sql = "SELECT * FROM photos WHERE photos_id = '$return_photos_id'";
		$return_photos_results = $conn->query($return_photos_sql);
		foreach($return_photos_results as $return_photos_row){
?>
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>?id=<?php echo $return_photos_row['photos_id'];?>" method="POST" enctype="multipart/form-data">
			description:<br>
			<input type="hidden" name="update-photos-id" value="<?php echo $return_photos_row['photos_id'];?>">
			<textarea name="update-photos-caption" cols=40 rows=10"><?php echo $return_photos_row['photos_caption'];?></textarea><br>
			<input type="submit" name="update-photos-submit" value="Update Photo">
			<input type="submit" class="delete-btn" name="update-photos-delete" value="DELETE PHOTO"><br>
			<a href="EPS-PhotosUpdate.php"><input type="button" class="hide-btn" value="&#9650;"></a>
		</form>
<?php
				}
			}
?>
			</div>
	</a>
<?php
		}
	}
?>
	<hr>
	<form class="add-photos-form" action="<?php echo $_SERVER['SCRIPT_NAME'];?>#add-photo-section" method="POST" enctype="multipart/form-data"><input type="submit" name="add-photo" value= "Add New Photo"></form>
<?php
	if(isset($_POST['add-photo'])){
?>
	<a name="add-photo-section">
	<div id="selected-photo">
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data">
			image:<input type="file" name="add-photos-image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
			caption:<br>
			<textarea name="add-photos-caption" cols=40 rows=20"></textarea><br>
			<input type="submit" name="add-photos-submit" value="Add Photo">
		</form>
	</div>
	</a>
<?php }?>

</body>
</html>




