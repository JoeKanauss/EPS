<?php
	session_start();

?>

<!DOCTYPE html>
<head>
	<?php include "EPS-headContent.php";?>
	<link rel="stylesheet" type="text/css" href="styles/EPS-Contact.css">
	<link rel="stylesheet" type="text/css" href="styles/schedule.css">
	<title>CONTACT</title>
	
</head>
<body>
	<div id="header">
		<div class="menu-hamburger"><img src="images/menu2.png" onclick="showMenu();"></div>
		<div class="page-title"><h1>BOOKING CALENDAR</h1></div>
		<div class="logo"><img src="images/logo-ph.png" ></div>
	</div>
	
	<?php include "EPS-sidebar.php";?>
	
	<div id="main">
	<div id="date"></div>
        <div id="calendar">
            <div id="top">
                <input type="button" id="previous" value="< Last Month" onclick="displayLast()">
                <input type="button" id="next" value="Next Month >" onclick="displayNext()">
                <div id="monthYear"> 
                    <span id="month"></span> 
                    <span id="year"></span> 
                </div>
                
            </div>
            <div class='clear'></div>
            <div class='weekday'>Sun</div>
            <div class='weekday'>Mon</div>
            <div class='weekday'>Tue</div>
            <div class='weekday'>Wed</div>
            <div class='weekday'>Thur</div>
            <div class='weekday'>Fri</div>
            <div class='weekday'>Sat</div>
            <div class='clear'></div>
            <div id="days"></div>
        </div>
        <div class="clear"></div>
         <!--<div id="schedule-body">
            Date: <input type="text" id="schedule-date" oninput="disableSetEventButton();"><br>
            Event: <input type="text" id="schedule-event" oninput="disableSetEventButton();"><br>
           <input type='button' id='set-event-button' value='Set Event' onclick='setEvent();testSave();'><br>
           <input type='button' id='download-schedule' value='Download Event Schedule' onclick='downloadSchedule();'>
            <input type='file' id='upload-schedule' value='Upload Event Schedule'><br>
            <input type='button' id='enter-schedule' value='Show Event Schedule'onclick='enterEventSchedule();'>
		 -->
            <div id="event-display"></div>
         <!--   <div id="display"></div> -->
        </div>
    </body>

	<?php include "EPS-footer.php";?>

</body>
	<script src="js/schedule.js"></script>
</html>