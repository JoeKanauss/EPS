<?php
session_start();

$fileTooLargeError = "";
$wrongExtensionError = "";
$validImage = true;

if($_SESSION["user"] !== "admin")
{
	header("Location: EPS-Testimonials.php");
}
else{
	include "localHostConnect.php";
	
	//load Testimonials from DB
	$sql = "SELECT testimonials_id, testimonials_name  FROM testimonials";
	$result = $conn->query($sql);
	
	//upload testimonials image
	if(isset($_FILES['update-testimonial-image'])){
		$file_name = $_FILES['update-testimonial-image']['name'];
		$file_size = $_FILES['update-testimonial-image']['size'];
		$file_tmp = $_FILES['update-testimonial-image']['tmp_name'];
		$file_type = $_FILES['update-testimonial-image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['update-testimonial-image']['name'])));
		$extensions = array("jpeg","jpg","png");
		$image_link = "images/testimonials/".$_FILES['update-testimonial-image']['name'];
	
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
	
	//update testimonial
	if(isset($_POST["update-testimonial-submit"])){
		try{
			$testimonialId = $_POST['update-testimonial-id'];
			$testimonialImage = $_FILES['update-testimonial-image']['name'] ;
			$testimonialName = filter_var($_POST['update-testimonial-name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$testimonialQuote= filter_var($_POST['update-testimonial-quote'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			
			// if the image is not set, don't update the image (in case of keeping the same image as before, this will not make the image blank)
			if($testimonialImage == ""){
				$updateSql = "UPDATE testimonials SET testimonials_name = '$testimonialName', testimonials_quote = '$testimonialQuote' WHERE testimonials_id = $testimonialId";
			}
			else{
				$updateSql = "UPDATE testimonials SET testimonials_photo_link = 'images/testimonials/$testimonialImage', testimonials_name = '$testimonialName', testimonials_quote = '$testimonialQuote' WHERE testimonials_id = $testimonialId";
			}
			$update = $conn->prepare($updateSql);
			$update->execute();
			header("Refresh:0");
		}
		catch(PDOException $e)
		{
			echo $updateSql . "<br>" . $e->getMessage();
		}
	}
	
	//upload image
	if(isset($_FILES['add-testimonial-image'])){
		$file_name = $_FILES['add-testimonial-image']['name'];
		$file_size = $_FILES['add-testimonial-image']['size'];
		$file_tmp = $_FILES['add-testimonial-image']['tmp_name'];
		$file_type = $_FILES['add-testimonial-image']['type'];
		$file_ext = strtolower(end(explode('.', $_FILES['add-testimonial-image']['name'])));
		$extensions = array("jpeg","jpg","png");
		$image_link = "images/testimonials/".$_FILES['add-testimonial-image']['name'];
	
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
	
	//add testimonial
	if(isset($_POST['add-testimonial-submit'])){
		try{	
			$addTestimonialImage = "images/testimonials/".$_FILES['add-testimonial-image']['name'];
			$addTestimonialName = filter_var($_POST['add-testimonial-name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$addTestimonialQuote = filter_var($_POST['add-testimonial-quote'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
			$addTestimonialSql = $conn->prepare("INSERT INTO testimonials (testimonials_photo_link, testimonials_name, testimonials_quote) VALUES (:addTestimonialImage, :addTestimonialName, :addTestimonialQuote)");
		
			$addTestimonialSql->bindParam(':addTestimonialImage', $addTestimonialImage);
			$addTestimonialSql->bindParam(':addTestimonialName', $addTestimonialName);
			$addTestimonialSql->bindParam(':addTestimonialQuote', $addTestimonialQuote);
		
			$addTestimonialSql->execute();
			header("Refresh:0");
		}
		catch(PDOException $e)
		{
			echo $addTestimonialsql . "<br>" . $e->getMessage();
		}
	}
	//delete testimonial
	if(isset($_POST['update-testimonial-delete'])){
		try{
			$testimonialIdToDelete = $_POST['update-testimonial-id'];
			$deleteTestimonialSql = "DELETE FROM testimonials WHERE testimonials_id = $testimonialIdToDelete";
			$deleteTestimonial = $conn->query($deleteTestimonialSql);

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
	<link rel="stylesheet" type="text/css" href="styles/EPS-TestimonialsUpdate.css">
	<title>TESTIMONIALS UPDATE</title>
</head>
<body>
	<?php 
		if($_SESSION["user"] == "admin"){
	?>
	<div id="testimonials-current">
		<h3><a href="EPS-Testimonials.php" target="_blank">SEE THE CURRENT TESTIMONIALS PAGE</a></h3>
	</div>
	<?php 
		//display all testimonial names with update/delete buttons
		foreach($result as $row) {		
	?>
	<div class="testimonial">
			<div class="testimonial-name"><?php echo $row['testimonials_name'];?></div>
			<a href="EPS-TestimonialsUpdate.php?id=<?php echo $row['testimonials_id'];?>">UPDATE or DELETE</a>
	</div>
	<?php 
		//if one of the services is chosen for update, store its id in the GET and load in the details
		if(isset($_GET['id'])&& $_GET['id'] == $row['testimonials_id']){
			$return_testimonial_id = $_GET['id'];
			$return_testimonial_sql = "SELECT * FROM testimonials WHERE testimonials_id = '$return_testimonial_id'";
			$return_testimonial_results = $conn->query($return_testimonial_sql);
			foreach($return_testimonial_results as $return_testimonial_row){
	?>
	<div id="selected-testimonial">
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>?id=<?php echo $return_testimonial_row['testimonials_id'];?>" method="POST" enctype="multipart/form-data">
			<h2><?php echo $return_testimonial_row['testimonials_name'];?></h2>
			current image:<br><img class="testimonial-image" src="<?php echo $return_testimonial_row['testimonials_photo_link'];?>"><br>
			new image:<br><input type="file" name="update-testimonial-image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
			name:<br><input type="text" name="update-testimonial-name" value="<?php echo $return_testimonial_row['testimonials_name'];?>"><br>
			quote:<br>
			<input type="hidden" name="update-testimonial-id" value="<?php echo $return_testimonial_row['testimonials_id'];?>">
			<textarea name="update-testimonial-quote" cols=40 rows=20"><?php echo $return_testimonial_row['testimonials_quote'];?></textarea><br>
			
			<input type="submit" name="update-testimonial-submit" value="Update Testimonial">
			<input type="submit" class="delete-btn" name="update-testimonial-delete" value="DELETE TESTIMONIAL"><br>
			<a href="EPS-TestimonialsUpdate.php"><input type="button" class="hide-btn" value="&#9650;"></a>
		</form>
	</div>
	<?php
				}
			}
		}
	}
	?>
	<hr>
	<form class="add-testimonials-form" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data"><input type="submit" name="add-testimonial" value= "Add New Testimonial"></form>
	<?php
		if(isset($_POST['add-testimonial'])){
	?>
	<div id="selected-testimonial">
		<form action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="POST" enctype="multipart/form-data">
			new image:<br><input type="file" name="add-testimonial-image"><br>
			<?php echo $wrongExtensionError; echo $fileTooLargeError;?>
			name:<br><input type="text" name="add-testimonial-name" ><br>
			quote:<br>
			<textarea name="add-testimonial-quote" cols=40 rows=20"></textarea><br>
			
			<input type="submit" name="add-testimonial-submit" value="Add Testimonial">
			<a href="EPS-TestimonialsUpdate.php"><input type="button" class="hide-btn" value="&#9650;"></a>
		</form>
	</div>
	<?php }?>
</body>
</html>