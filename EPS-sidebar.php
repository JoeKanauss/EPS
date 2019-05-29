	<div id="sidebar">
		<div class="sb-head"><h2>PET CARE BY EMILY</h2></div>
		<div class="sb-about"><h3><a href= "EPS-About.php">About</a></h3></div>
		<div class="sb-services"><h3><a href= "EPS-Services.php">Services</a></h3></div>
		<div class="sb-photos"><h3><a href= "EPS-Photos.php">Photos</a></h3></div>
		<div class="sb-calendar"><h3><a href= "EPS-Calendar.php">Booking Calendar</a></h3></div>
		<div class="sb-testimonials"><h3><a href= "EPS-Testimonials.php">Testimonials</a></h3></div>
		<div class="sb-contact"><h3><a href= "EPS-Contact.php">Contact</a></h3></div>
		<?php 
			if(array_key_exists('user', $_SESSION) && $_SESSION["user"] == "admin"){
		?>
			<a href="EPS-AdminLogin.php">ADMIN PAGE</a>
		<?php }
		?>
	</div>