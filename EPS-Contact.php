<?php
session_start();

	$contactFirstName = "";
	$contactLastName = "";
	$contactPet = "";
	$contactPetType = "";
	$contactPhone = "";
	$contactEmail = "";
	$contact1 = "";
	$contact2 = "";
	$contact3 = "";
	$contactStartDate = "";
	$contactStartTime = "";
	$contactEndDate = "";
	$contactEndTime = "";
	$contactDetails = "";
	$sendFail = "";
	$sendSuccess = "";
	$inPastError = "";
	$noPetTypeError = "";
	$negativeTimeError = "";
	$sameTimeError = "";
	$pleaseFixMsg = "";
	$noContactError = "";

if(isset($_POST["contact-submit"]))
{
	//set form variables
	$contactFirstName = $_POST["contact-first-name"];
	$contactLastName = $_POST["contact-last-name"];
	$contactPet = $_POST["contact-pet"];
	$contactPetType = $_POST["contact-pet-type"];
	$contactPetTypeString = "";
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

	//verify at least one contact method
	if(empty($_POST["contact1"])&& empty($_POST["contact2"]) && empty($_POST["contact3"])){
		$validForm = false;
		$noContactError = "Please include at least one (1) contact method";
	}

	if(empty($_POST["contact1"])){
		$contact1 = "DO NOT CALL";
	}
	else{
		$contact1 = $_POST["contact1"];
	}
	if(empty($_POST["contact2"])){
		$contact2 = "DO NOT TEXT";
	}
	else{
		$contact2 = $_POST["contact2"];
	}
	if(empty($_POST["contact3"])){
		$contact3 = "DO NOT EMAIL";
	}
	else{
		$contact3 = $_POST["contact3"];
	}

	//combine pet types
	if(empty($contactPetType)){
		$contactPetTypeString = "Pet type(s) unspecified";
	}
	else{
		// foreach($contactPetType as $petType){
		// 	$contactPetTypeString .= "|".$petType."| "; 
		// }
		for($i=0;$i<sizeof($contactPetType);$i++){
			if($contactPetType[$i] != $contactPetType[sizeof($contactPetType)-1]){
				$contactPetTypeString .= $contactPetType[$i].", ";
			}
			else{
				$contactPetTypeString .= $contactPetType[$i];
			}
		}
	}

	

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
	$message = "[CLIENT INFORMATION]\n\t\tClient name: ".$contactFirstName." ".$contactLastName."\n\t\tClient phone: ".$contactPhone."\n\t\tClient email: ".$contactEmail."\n\t\tMethod(s) of Contact: ".$contact1." | ".$contact2." | ".$contact3."\n\n[PET INFORMATION]\n\t\tPet(s) name(s): ".$contactPet."\n\t\tPet(s) type(s): ".$contactPetTypeString."\n\n[PET SITTING DATES]\n\t\tFROM: ".$formattedStartDate." - ".$formattedStartTime."\n\t\tTO: ".$formattedEndDate." - ".$formattedEndTime."\n\n[ADDITIONAL DETAILS]\n\t\t".$contactDetails;
	
	//confirmation email
	$toConfirm = $contactEmail;
	$subjectConfirm = "[EMILY'S PET SITTING] Contact Info Sent!";
	//---->change fromEmail
	$fromConfirm = "from: web@joekanauss.info"; 
	$messageConfirm = "Hi ".$contactFirstName.",\n\nYour contact request has been sent to Emily. You will get a response shortly.\n\nThank you for considering Pet Care by Emily for your pet sitting needs!";
	
	//send email
	if($validForm){
		if(mail($to, $subject, $message, $from) && mail($toConfirm, $subjectConfirm, $messageConfirm, $fromConfirm)){
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
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Contact.css">
	<title>CONTACT</title>
	
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>CONTACT</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>
	
	<?php include "EPS-sidebar.php";?>
	
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
			First Name<br> <input type="text" id="contact-first-name" name="contact-first-name" value="<?php echo $contactFirstName;?>" size="18.5" required><br>
			Last Name<br> <input type="text" id="contact-last-name" name="contact-last-name" value="<?php echo $contactLastName;?>" size="18.5" required><br>
			Name of Pet(s)<br> <input type="text" id="contact-pet" name="contact-pet" value="<?php echo $contactPet;?>" size="18.5" required><br>
			Pet Type<br><span class="petTypeInfo">(For multiple Pet Types, hold ctrl and click on the options)</span><Br>
					  <select id="contact-pet-type" name="contact-pet-type[]" multiple size=6 required>
 						 <option value="dog">Dog</option>
 						 <option value="cat">Cat</option>
 						 <option value="fish">Fish</option>
						 <option value="exotic">Exotic (please define in details)</option>
						 <option value="other">Other (please define in details)</option>
					  </select></br>
		</div>
		<div id="contact-communication">
			Phone Number<br> <input type="tel" name="contact-phone" pattern ="[0-9]{3}-[0-9]{3}-[0-9]{4}" placeholder="ex: 123-555-6789" value="<?php echo $contactPhone;?>" size="21" required><br>
			Email:<br> <input type="email" name="contact-email" placeholder="ex: janedoe@mail.com" value="<?php echo $contactEmail;?>" size="21" required><br>
			Can Contact by...<br>
			<input type="checkbox" name="contact1" value="CALL">Call<br>
			<input type="checkbox" name="contact2" value="TEXT">Text<br>
			<input type="checkbox" name="contact3" value="EMAIL">Email<br>
			<?php echo $noContactError;?>
		</div>
		<div id="contact-details">
			Desired Start Date<br> <input type="date" name="start-date" value="<?php echo $contactStartDate;?>" style="background-color:<?php echo $startDateErrorColor;?>"  required><br>
			Desired Start Time<br> <input type="time" name="start-time" value="<?php echo $contactStartTime;?>" style="background-color:<?php echo $startTimeErrorColor;?>" required><br>
			Desired End Date<br> <input type="date" name="end-date" value="<?php echo $contactEndDate;?>" style="background-color:<?php echo $endDateErrorColor;?>" required><br>
			Desired End Time<br> <input type="time" name="end-time" value="<?php echo $contactEndTime;?>" style="background-color:<?php echo $endTimeErrorColor;?>" required><br>
			<?php echo $inPastError.$negativeTimeError.$sameTimeError.$pleaseFixMsg;?>
			Additional Details<br> <textarea id="contact-additional" name="contact-additional" rows=10 cols=30><?php echo $contactDetails;?></textarea>
		</div>
		<div id="contact-buttons">
			<input type="submit" name="contact-submit" value="Contact">
			<input type="reset" name="contact-reset" value="Reset Form">
		</div>
	</form>
</div>
<?php } ?>
</div>

	<?php include "EPS-footer.php";?>

</body>
</html>




	