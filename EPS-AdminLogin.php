<?php
//set up session
session_start();
$msg = "";

if(isset($_POST["login-submit"])){
	$user = $_POST["login-user"];
	$pass = $_POST["login-pass"];
	//connect to database
	include "localHostConnect.php";
	//set up SELECT query for user/pass
	$sql = "SELECT admin_u FROM admin WHERE admin_u = '$user' AND admin_p = '$pass'";
	//run query
	$result = $conn->query($sql);
	if($result->rowCount() == 1)
	{
		$_SESSION["user"] = "admin";
		$msg = "LOGGED IN!";
	}
	else{
		$msg = "Invalid information. Please try again.";
	}
}

if(isset($_POST["login-logout"])){
	session_destroy();
}
?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<title>ADMIN</title>
</head>
<body>
<?php 
	if(array_key_exists('user', $_SESSION) && $_SESSION["user"] == "admin"){
?>
	<H1>ADMINISTRATION BACK-END</h1>
	<a href="EPS-AboutUpdate.php">Update ABOUT page</a><br>
	<a href="EPS-ServicesUpdate.php">Update SERVICES page</a><br>
	<a href="EPS-PhotosUpdate.php">Update PHOTOS page</a><br>
	<a href="">Update BOOKING CALENDAR page</a><br>
	<a href="EPS-TestimonialsUpdate.php">Update TESTIMONIALS page</a>
	
	<form action="" method="POST">
		<input type="submit" id="login-logout" name="login-logout" value="Log out">
	</form>
<?php
	}else{
?>		
	<form id="login" action="EPS-AdminLogin.php" method="POST">
	Username: <input type="text" id="login-username" name="login-user"><br>
	Password: <input type="password" id="login-password" name="login-pass"><br>
	<input type="submit" id="login-submit" name="login-submit" value="Log in"> <input type="reset" id="login-reset" name="login-reset" value="reset">
	</form>
	<?php echo $msg;?>
<?php } ?>
	</body>
</html>
