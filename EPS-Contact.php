<?php
if(isset($_POST["contact-submit"]))
{
	//set form variables
	$contactName = $_POST["contact-name"];
	$contactPet = $_POST["contact-pet"];
	$contactPhone = $_POST["contact-phone"];
	$contactEmail = $_POST["contact-email"];
	$contactStartDate = $_POST["start-date"];
	$contactStartTime = $_POST["start-time"];
	$contactEndDate = $_POST["end-date"];
	$contactEndTime = $_POST["end-time"];
	$contactDetails = $_POST["contact-additional"];
	$sendFail = "";
	$sendSuccess = "";
	$inPastError = "";
	$negativeTimeError = "";
	$sameTimeError = "";
	$pleaseFixMsg = "";
	$startDateErrorColor = "";
	$endDateErrorColor = "";
	$startTimeErrorColor = "";
	$endTimeErrorColor = "";
	$validForm = true;
	$today = new DateTime();

	//convert form date to month, dd, yyyy
	$startDate =  date_create($contactStartDate);
	$endDate =  date_create($contactEndDate);
	$formattedStartDate = date_format($startDate,"F, d, Y");
	$formattedEndDate = date_format($endDate,"F, d, Y");
	
	//convert form time to 12 hour time with am/pm
	$formattedStartTime = date("g:i a", strtotime($contactStartTime));
	$formattedEndTime = date("g:i a", strtotime($contactEndTime));
	
	//combine dates and times for validation
	$startDayTime = date(strtotime("$contactStartDate $contactStartTime"));
	$endDayTime = date(strtotime("$contactEndDate $contactEndTime"));
	
	//validate dates
	if($startDate < $today)
	{
		$inPastError = "Start Date is earlier than today<br>";
		$validForm = false;
		$startDateErrorColor = "peachpuff";
	}
	if($endDate < $startDate)
	{
		$negativeTimeError = "Your End Date comes before your Start Date<br> ";
		$validForm = false;
		$startDateErrorColor = "peachpuff";
		$endDateErrorColor = "peachpuff";
	}
	if($startDayTime == $endDayTime){
		$sameTimeError = "Start Date and Time are the same as End Date and Time<br>";
		$validForm = false;
		$startDateErrorColor = "peachpuff";
		$endDateErrorColor = "peachpuff";
		$startTimeErrorColor = "peachpuff";
		$endTimeErrorColor = "peachpuff";
	}
	
	//set up email to ToEmail
	//----> change toEmail
	$to = "penpaperjoe@gmail.com"; 
	$subject = "[EMILY'S PET SITTING] ".$contactPet." | ".$formattedStartDate."-".$formattedEndDate;
	//---->change fromEmail
	$from = "from: web@joekanauss.info"; 
	$message = "FROM: ".$formattedStartDate." - ".$formattedStartTime."\nTO: ".$formattedEndDate." - ".$formattedEndTime;
	
	//send email
	if($validForm){
		if(mail($to, $subject, $message, $from)){
			$sendSuccess = "Your contact form was sent!";
			$sendFail = "";
		}
		else{
			$sendSuccess = "";
			$sendFail = "UH-OH! Your contact form was not sent! Please try again later!";
		}
	}
	else{
		$pleaseFixMsg = "Please fix the errors and resubmit the contact form<br>";
	}
		
}
?>
<!DOCTYPE html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Emily's Pet Sitting</title>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Styles.css">
	<link rel="stylesheet" type="text/css" href="styles/EPS-Contact.css">
	<link rel="stylesheet" href="http:://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="js/jquery-3.2.1.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/EPS-js.js"></script>
	
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>PAGE TITLE</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>
	<div id="sidebar">
		<div class="sb-head"><h2>EMILY'S PET SITTING</h2></div>
		<div class="sb-about"><h3><a href= "">About</a></h3></div>
		<div class="sb-services"><h3><a href= "">Services</a></h3></div>
		<div class="sb-photos"><h3><a href= "">Photos</a></h3></div>
		<div class="sb-calendar"><h3><a href= "">Booking Calendar</a></h3></div>
		<div class="sb-testimonials"><h3><a href= "">Testimonials</a></h3></div>
		<div class="sb-contact"><h3><a href= "">Contact</a></h3></div>
	</div>
	<div id="main">
	
<?php
//if the contact form has been submitted...
if(isset($_POST["contact-submit"]) && $validForm)
{
	//display message
?>
<h2 class="contactMsg"><?php echo$sendFail."<br>".$sendSuccess."<br><a href='homepage.php'>[Go to the Homepage]</a>"?></h2>

<?php
}
else{
?>

<div id="contact-form">
	<form action="EPS-Contact.php" method="post" id="orderForm">
		<div id="contact-names"> 
			Name: <input type="text" id="contact-name" name="contact-name" value="<?php echo $contactName;?>" size="18.5">
			Name of Pet: <input type="text" id="contact-pet" name="contact-pet" value="<?php echo $contactPet;?>" size="18.5">
		</div>
		<div id="contact-communication">
			Phone Number: <input type="tel" name="contact-phone" pattern ="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="ex: 123-555-6789" value="<?php echo $contactPhone;?>" size="21">
			Email: <input type="email" name="contact-email" placeholder="ex: janedoe@mail.com" value="<?php echo $contactEmail;?>" size="21">
		</div>
		<div id="contact-details">
			Start Date: <input type="date" name="start-date" value="<?php echo $contactStartDate;?>" style="background-color:<?php echo $startDateErrorColor;?>">
			Start Time: <input type="time" name="start-time" value="<?php echo $contactStartTime;?>" style="background-color:<?php echo $startTimeErrorColor;?>">
			End Date: <input type="date" name="end-date" value="<?php echo $contactEndDate;?>" style="background-color:<?php echo $endDateErrorColor;?>">
			End Time: <input type="time" name="end-time" value="<?php echo $contactEndTime;?>" style="background-color:<?php echo $endTimeErrorColor;?>">
			<?php echo $inPastError.$negativeTimeError.$sameTimeError.$pleaseFixMsg ?>
			Additional Details: <textarea id="contact-additional" name="contact-additional" rows=10 cols=30><?php echo $contactDetails;?></textarea>
		</div>
		<div id="contact-buttons">
			<input type="submit" name="contact-submit" value="Contact">
			<input type="reset" name="contact-reset" value="Reset Form">
		</div>
	</form>
</div>
<?php } ?>
</div>
	<div id="footer">
		<div class="left-foot">
			<a href="">About</a> | <a href="">Services</a><br>
			<a href="">Photos</a> | <a href="">Booking Calendar</a><br>
			<a href="">Testimonials</a> | <a href="">Contact</a>
		</div>
		<div class="social-media-links">
		<p>SOCIAL MEDIA LINKS WILL GO HERE!!!</p>
		</div>
	</div>
</body>
</html>




	