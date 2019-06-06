<?php 
session_start();

include "localHostConnect.php";
$testimonialsSql = "SELECT * FROM testimonials";
$results = $conn->query($testimonialsSql);
?>
<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Testimonials.css">
	<title>TESTIMONIALS</title>
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>TESTIMONIALS</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>
	
	<?php include "EPS-sidebar.php";?>
	
	<div id="main">
		<div id="testimonial-description">
			<p>Vivamus sed euismod nibh. Nunc et mauris blandit, dictum neque vitae, cursus diam. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Donec viverra nisi dui, id euismod massa elementum eu.</p>
		</div>
		<hr>
		<?php 
			$counter = 0;
			foreach($results as $testimonial){
				if($counter % 2 == 0){
		?>
		<div class="testimonial-l">
			<img class="testimonial-image-l" src="<?php echo $testimonial['testimonials_photo_link'];?>">
			<p class="testimonial-quote">"<?php echo $testimonial['testimonials_quote'];?>"</p>
			<p class="testimonial-name">- <?php echo $testimonial['testimonials_name'];?></p>
		</div>
		<hr>
		<?php
				}
				else{
		?>
		<div class="testimonial-r">
			<img class="testimonial-image-r" src="<?php echo $testimonial['testimonials_photo_link'];?>">
			<p class="testimonial-quote">"<?php echo $testimonial['testimonials_quote'];?>"</p>
			<p class="testimonial-name">- <?php echo $testimonial['testimonials_name'];?></p>
		</div>
		<hr>
		<?php 
					}
					$counter++;
			}
		?>
	</div>
		
	
	<?php include "EPS-footer.php";?>

</body>
</html>