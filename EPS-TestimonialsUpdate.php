<?php
session_start();
if($_SESSION["user"] !== "admin")
{
	header("Location: EPS-Testimonials.php");
}
?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<title>TESTIMONIALS UPDATE</title>
</head>
<body>

</body>
</html>